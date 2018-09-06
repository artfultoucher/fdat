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
            if ($deliverable->graded) {
                return back()->withFlashDanger('You cannot override a file submission once it has been graded by its examiner(s).');
            }
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
            }
        else {
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
                if ($project->is_examiner()) {
                    $result[] = $d;
                }
            }
        }
        return view('frontend.my_deliverables', ['docs' => $result]);
    }

    public function feedback_form($did){
        $doc = Deliverable::findOrFail($did);
        return view('frontend.feedback_form', ['doc' => $doc]);
    }

    public function feedback_process(Request $request, $did){
        $doc = Deliverable::findOrFail($did);
        if (! $doc->is_examiner()) {
            return redirect()->route('frontend.deliverable.my')->withFlashDanger('You are not an examiner for this document.');
        }
        if ($doc->graded) {
            return redirect()->route('frontend.deliverable.my')->withFlashDanger('Once you have finalized the feedback, you cannot change it again.');
        }
        if ($request->mark < 0) {
            return redirect()->route('frontend.deliverable.my')->withFlashDanger('The awarded mark must be greater than zero.');
        }
        if ($request->mark > 100) {
            return redirect()->route('frontend.deliverable.my')->withFlashDanger('The awarded mark must be less than 100.');
        }
        $doc->comment = $request->comment;
        $doc->private_comment = $request->private_comment;
        if ($doc->is_marker()) { // In case of a malicious second reader who goes through the lenghts to alter a from :-)
            $doc->graded = isset($request->graded) ? true : false;
            $doc->mark = $request->mark;
        }
        $doc->save();
        return redirect()->route('frontend.deliverable.my')->withFlashSuccess('Feedback saved and published.');
    }

    public function download(Request $request){
        // TODO guard this properly
        $deliverable = Deliverable::findOrFail($request->doc_id);
        abort_unless ($request->path == $deliverable->path, 403, "Data corruption.");
        $uploader = User::findOrFail($deliverable->uploader_id);
        $filename = $this->request_by_id($deliverable->request_id)->name . '-' . $uploader->last_name . '.';
        $filename .= pathinfo($request->path, PATHINFO_EXTENSION);
        return Storage::download($request->path, snake_case($filename));
        // files are downloaded as request_name-last_name_of_uploader.extension
    }

    public function delete(Request $request){ // TODO guard this properly
        $deliverable = Deliverable::findOrFail($request->doc_id);
        abort_unless ($request->path == $deliverable->path, 403, "Data corruption.");
        if (! $deliverable->is_supervisor()) {
            return back()->withFlashDanger('No cigar. :-P Only the supervisor can do that.');
        }
        $deliverable->my_delete();
        return back()->withFlashSuccess('Uploaded file removed.');
    }

    public function delete_many(Request $r, $rid=null){ // delete all hand ups or all those with request ID specified
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
            $d->my_delete();
        }
        return back()->withFlashInfo($count .' deliverables deleted.');
    }

    public function index(){
        $count = array();
        $reqs = $this->d_requests;
        foreach ($reqs as $req) {
            $count[$req->id] = Deliverable::where('request_id', $req->id)->count();
        }
        return view('frontend.request_index', ['requests' => $reqs, 'count' => $count]);
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
