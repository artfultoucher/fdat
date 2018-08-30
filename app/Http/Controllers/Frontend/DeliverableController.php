<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Foundation\Http\FormRequest;
use App\Project;
use App\Models\Auth\User;


class DeliverableController extends Controller
{
    public function upload_form($pid) {
        $project = Project::findOrFail($pid);
        abort_unless($project->is_student(), 403, 'Trying your luck?'); // TODO take care  of deliverable type
        return view('frontend.deliverable', ['project' => $project]);
    }

    public function store(Request $request, $pid) {
        //TODO write  proper FormRequest to handle all warnings and permissions
        $path = $request->file('deliv')->store('deliverables');
        return $path;

    }
}
