<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeliverable;
use Illuminate\Http\Request; // stock requests
//use App\Project;
use App\Deliverable;
//use App\Models\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class DeliverableController extends Controller
{
    public function upload_form($pid) {
        $project = Project::findOrFail($pid);
        //abort_unless($project->is_student(), 403, 'Trying your luck?'); // TODO take care  of deliverable type
        return view('frontend.deliverable', ['project' => $project]);
    }

    public function store(StoreDeliverable $request, $pid) {
        //TODO take care of RID, deadlines and already marked deliverables
        $rid = 0;
        $deliverable = Deliverable::where('project_id', $pid)->where('request_id', $rid)->first(); // TODO set request ID from form
        if ($deliverable) {
            Storage::delete($deliverable->path);
            $update = true;
        } else {
            $deliverable  = new Deliverable;
            $update = false;
        }
        $deliverable->project_id = $pid;
        $deliverable->request_id = $rid;
        $deliverable->uploader_id = Auth::user()->id;
        $path = $request->file('document')->store('deliverables');
        $deliverable->path = $path;
        $deliverable->save();
        if ($update) {
            \Log::info('Replaced existing file: ' . $path);
            return redirect()->route('frontend.project.show', $pid)->withFlashSuccess('Replaced previously uploaded file. Thank you.');
            } else {
            \Log::info('Stored new file: ' . $path);
            return redirect()->route('frontend.project.show', $pid)->withFlashSuccess('Deliverable received. Thank you.');
        }
    }

    public function delete(Request $r, $pid, $rid){ // delete all hand ups for this PID/RID
        $deliverables = Deliverable::where('project_id', $pid)->where('request_id', $rid)->get();
        $count = $deliverables->count();
        foreach ($deliverables as $d) {
            $d->delete();
        }
        return back()->withFlashInfo($count .' deliverables deleted.');
    }

    public function view_requests($code){
        $result = array();
        foreach ($this->d_requests as $d) {
            if ($d->project_type == $code) {
                $result[]=$d;
            }
        }
        return view('frontend.request_list', ['requests' => $result, 'code' => $code, 'id_of_next' => $this->id_of_next($code)]);
    }

    private function id_of_next($code) { // The ID of the deliverable with the closest future deadline for a given project type
        //returns -1 if all in the past
        $result = -1;
        $closest = Carbon::createFromFormat('d/m/Y', '30/12/2100'); // must be something in the distant future
        foreach ($this->d_requests as $obj) {
            $testing = $obj->due_date;
            if ($obj->project_type == $code && $testing->isFuture() && $testing->lt($closest)) {
                $closest = $testing;
                $result = $obj->id;
            }
        }
        return $result;
    }

    private $d_requests;

    public function __construct() {
        $fs = New \Illuminate\Filesystem\Filesystem();
        $json_file = $fs->get(app_path('deliverable_requests.json'));
        $this->d_requests = json_decode($json_file);
        foreach ($this->d_requests as $key => $obj) { // replace the string dates with Carbon instances
            $this->d_requests[$key]->due_date = Carbon::createFromFormat('d/m/Y', $obj->due_date);
            $this->d_requests[$key]->feedback_date = Carbon::createFromFormat('d/m/Y', $obj->feedback_date);
        }
    }

}
