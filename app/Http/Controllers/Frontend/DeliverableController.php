<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeliverable;
use Illuminate\Http\Request; // stock requests
use App\Project;
use App\Deliverable;
//use App\Models\Auth\User;
use Illuminate\Support\Facades\Auth;


class DeliverableController extends Controller
{
    public function upload_form($pid) {
        $project = Project::findOrFail($pid);
        //abort_unless($project->is_student(), 403, 'Trying your luck?'); // TODO take care  of deliverable type
        return view('frontend.deliverable', ['project' => $project]);
    }

    public function store(StoreDeliverable $request, $pid) {
        //TODO check if we should overwrite existing
        $deliverable  = new Deliverable;
        $path = $request->file('document')->store('deliverables');
        $deliverable->path = $path;
        \Log::info('Stored file: ' . $path);
        $deliverable->project_id = $pid;
        $deliverable->uploader_id = Auth::user()->id;
        //request_id is not handled yet
        $deliverable->save();
        return redirect()->route('frontend.project.show', $pid)->withFlashSuccess('Deliverable received. Thank you.');
    }

    public function delete(Request $r, $pid, $rid){
        $deliverables = Deliverable::where('project_id', $pid)->where('request_id', $rid)->get();
        $count = $deliverables->count();
        foreach ($deliverables as $d) {
            $d->delete();
        }
        return back()->withFlashInfo($count .' deliverables deleted.');
    }

    private $deliverable_requests;

    public function __construct() {
        $fs = New \Illuminate\Filesystem\Filesystem();
        $json_file = $fs->get(app_path('deliverable_requests.json'));
        $this->deliverable_requests = json_decode($json_file);
    }

}
