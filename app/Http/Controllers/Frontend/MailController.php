<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Frontend\Contact\SendContact;
use App\Http\Requests\Frontend\Contact\SendContactRequest;
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
        return view('frontend.mail', ['recipients' => $rec]);
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
        return view('frontend.mail', ['recipients' => $rec]);
    }


    public function mail_post(SendContactRequest $request)
    {
        Mail::send(new SendContact($request));

        return redirect()->back()->withFlashSuccess('Mail sent.');
    }
}
