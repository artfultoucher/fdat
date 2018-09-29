<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserMail;
use Illuminate\Http\Request;
use App\Models\Auth\User;
use App\Project;
use Illuminate\Support\Facades\Auth;


class MailController extends Controller
{

    public function mail_user(Request $request, $uid) // a get method but we need request for back url
    {  // TODO fix the return route with session key or similar
        if(Auth::guest())
        {
            abort(403, 'You must be logged in to compose mail.');
        }
        if (Auth::user()->id == $uid) {
            return back()->withFlashWarning('It makes no sense to message yourself.');
        }
        $rec = array();
        $user = User::findOrFail($uid);
        $rec[] = $user;
        //session('backurl', $request->server('HTTP_REFERER'));
        return view('frontend.mail', ['recipients' => $rec, 'intro' => 'Hello ' . $rec[0]->full_name .','.PHP_EOL,
        'bc_name' => 'mail_user', 'bc_object' => $user]);
    }

    public function mail_project(Request $request, $pid) // a get method but we need request for back url
    {
        if(Auth::guest())
        {
            abort(403, 'You must be logged in to compose mail.');
        }
        $my_id = Auth::user()->id;
        $rec = array();
        $p = Project::findOrFail($pid);
        if ($p->author != $my_id) { // author is never 0 and always valid
            $rec[] = User::findOrFail($p->author);
        }
        $svid = $p->supervisor;
        if ($svid > 0 && $svid != $p->author && $svid != $my_id) {
            $rec[] = User::findOrFail($svid);
        }
        $srid = $p->secondreader;
        if ($srid > 0 && $srid != $my_id) {
            $rec[] = User::findOrFail($srid);
        }
        foreach ($p->assigned_students() as $student) { // I know there are union and merge functions...
            if ($student->id != $p->author) {
                $rec[] = $student;
            }
        }
        if (empty($rec)) {
            return back()->withFlashWarning('It makes no sense to message only yourself.');
        }
        //session('backurl', $request->server('HTTP_REFERER'));
        return view('frontend.mail', ['recipients' => $rec,
        'intro' => 'Hello all,'.PHP_EOL.'I refer to project ' . route('frontend.project.show', $pid),
        'bc_name' => 'mail_project', 'bc_object' => $p]);
    }
/*
    public function mail_many(Request $request){ // Recipient ids are in request. Compute and show mail view.
    // this implementation uses the tickbox mail view
        if(Auth::guest())
        {
            abort(403, 'You must be logged in to compose mail.');
        }
        $rec = User::find($request->ids)->all(); // we pass an array of primary keys as arg to find
        return view('frontend.mail', ['recipients' => $rec, 'intro' => 'Hello all,', 'bc_name' => 'mail_many', 'bc_object' => null]);
    }
*/

    public function mail_many(Request $request){ // Recipient ids are in request. Compute and show mail view.
        if(Auth::guest()) {
            abort(403, 'You must be logged in to compose mail.');
        }
        $lecturers =  User::role('lecturer')->orderBy('last_name')->get()->all();
        $students =  User::role('student')->orderBy('last_name')->get()->all();
        $selected = $request->ids;
        return view('frontend.mail_many', compact('lecturers', 'students', 'selected'));

    }

    public function mail_post(Request $request)
    {
        //dd($request->all());
        if (Auth::guest()) {
            abort(403, 'Haha! Nice try.');
        }
        if (! $request->has('to_ids')) {
            return redirect()->back()->withFlashWarning('Select at least one recipient!');
        }
        $destinations = User::findMany($request->to_ids); // nice :-)
        if (isset($request->cc)) {
            Mail::to($destinations)->cc($request->user())->send(new UserMail($request));
        }
        else {
            Mail::to($destinations)->send(new UserMail($request));
        }
        return redirect()->route('frontend.user.dashboard')->withFlashSuccess('Mail sent.');
        //return redirect(session('backurl'));
    }
}
