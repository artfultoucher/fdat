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
    /**
     * @return \Illuminate\View\View
     */
    public function mail_user($uid)
    {
        if(Auth::guest())
        {
            abort(403, 'You must be logged in to compose mail.');
        }
        if (Auth::user()->id == $uid) {
            return back()->withFlashWarning('It makes no sense to message yourself.');
        }
        $rec = array();
        $rec[] = User::findOrFail($uid);
        return view('frontend.mail', ['recipients' => $rec, 'intro' => 'Hello ' . $rec[0]->full_name .','.PHP_EOL]);
    }

    public function mail_project($pid)
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
        return view('frontend.mail', ['recipients' => $rec,
        'intro' => 'Hello all,'.PHP_EOL.'I refer to project ' . route('frontend.project.show', $pid)]);
    }


    public function mail_post(Request $request)
    {
        if (Auth::guest()) {
            abort(403, 'Haha! Nice try.');
        }
        if (! $request->has('to_ids')) {
            return redirect()->back()->withFlashWarning('Select at least one recipient!');
        }
        $destinations = User::findMany($request->to_ids);
        if (isset($request->cc)) {
            Mail::to($destinations)->cc($request->user())->send(new UserMail($request));
        }
        else {
            Mail::to($destinations)->send(new UserMail($request));
        }
        return redirect()->back()->withFlashSuccess('Mail sent.');
    }
}
