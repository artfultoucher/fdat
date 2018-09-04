<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeliverable;
use Illuminate\Http\Request; // stock requests
use App\Project;
use App\Deliverable;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeliverableController extends Controller
{
    use DeliverableRequests;

    public function upload_form($pid) {
        $project = Project::findOrFail($pid);
        abort_unless($project->is_student(), 403, 'Trying your luck?'); // TODO take care  of deliverable type
        $request_obj = $this->upcoming_deliverable($project->type);
        if (! $request_obj) {
            return back()->withFlashDanger('There is no open deliverable request for this project. Deadline in the past?');
        }

        return view('frontend.deliverable', ['project' => $project, 'document_title' => $request_obj->name]);
    }

    public function store(StoreDeliverable $request, $pid) {
        $project = Project::findOrFail($pid);
        if (!$project->is_student()) {
            return back()->withFlashDanger('You can only upload deliverables for projects which you undertake yourself as an assigned student.');
        }
        $request_obj = $this->upcoming_deliverable($project->type);
        if (! $request_obj) {
            return back()->withFlashDanger('There is no open deliverable request for this project. Deadline in the past?');
        }
        $rid = $request_obj->id;
        $deliverable = Deliverable::where('project_id', $pid)->where('request_id', $rid)->first();
        if ($deliverable) {
            // TODO lots of stuff here
            // TODO check if this deliverable has already been marked
            // go back if already marked!
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

    public function my_deliverables() {
        // This implmentation assumes that users cannot have student and lecturer roles together
        $user = Auth::user();
        if ($user->hasRole('student')) {
            $result = Deliverable::where('uploader_id', $user->id)->get()->all();}
        elseif ($user->hasRole('lecturer')) {
            // TODO I know this can be rewritten for the quey builder.. will do later
            $result = array();
            $deliverables = Deliverable::all();
            foreach ($deliverables as $d) {
                $project = Project::findOrFail($d->project_id);
                if ($project->supervisor == $user->id || $project->secondreader == $user->id) {
                    $result[] = $d;
                }
            }
        }
        return view('frontend.my_deliverables', ['docs' => $result]);
    }

    public function download(Request $request){
        // TODO guard this properly
        $deliverable = Deliverable::findOrFail($request->doc_id);
        abort_unless ($request->path == $deliverable->path, 403, "Data corruption.");
        $uploader = User::findOrFail($deliverable->uploader_id);
        $filename = $this->request_by_id($deliverable->request_id)->name;
        $filename .= '-';
        $filename .= $uploader->last_name;
        return Storage::download($request->path, snake_case($filename));
    }

    public function delete(Request $r, $rid=null){ // delete all hand ups or all those with request ID specified
        // entries must be deleted at *model* level rather than just deleting records.
        if (! Auth::user()->hasRole('administrator')) {
            return back()->withFlashDanger('You must be Adminstrator');
        }
        if($rid) {
            $deliverables = Deliverable::where('request_id', $rid)->get();
            }
        else {
            $deliverables = Deliverable::all();
        }
        $count = $deliverables->count();
        foreach ($deliverables as $d) {
            $d->delete();
        }
        return back()->withFlashInfo($count .' deliverables deleted.');
    }

    public function index(){
        return view('frontend.request_index', ['requests' => $this->d_requests]);
    }

/*
    public function view_requests($code){ // Maybe change this back to a view of all types
       // older version with delierables per matter code
        $result = array();
        foreach ($this->d_requests as $d) {
            if ($d->project_type == $code) {
                $result[]=$d;
            }
        }
        return view('frontend.request_list', ['requests' => $result, 'code' => $code, 'id_of_next' => $this->id_of_next($code)]);
    }
*/

}
