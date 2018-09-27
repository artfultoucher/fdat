<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProject;
use App\Http\Requests\UpdateProject;
use App\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth\User;


class ProjectController extends Controller
{

   public function index_all()
    {
        $arr = Project::latest()->get()->filter(function ($p) {return $p->is_visible(true);})->all();
        return view('frontend.project_list', ['projects' => $arr, 'page_title' => 'All projects',
        'breadcrumb_name' => 'projects_all']);
    }

   public function index()
   {
       $arr = Project::latest()->get()->filter(function ($p) {return $p->is_visible();})->all();
       return view('frontend.project_list', ['projects' => $arr, 'page_title' => 'Projects matching your subscription tags',
       'breadcrumb_name' => 'projects_relevant']);
   }

   public function index_free()
   {
       $arr= Project::latest()->get()->filter(function ($p){return $p->is_available() && $p->is_visible();})->all();
       return view('frontend.project_list', ['projects' => $arr, 'page_title' => 'Unassigned projects matching your subscription tags',
       'breadcrumb_name' => 'projects_available']);
   }

   public function index_taken()
   {
       $arr= Project::latest()->get()->filter(function ($p){return $p->is_taken() && $p->is_visible();})->all();
       return view('frontend.project_list', ['projects' => $arr, 'page_title' => 'Undertaken projects matching your subscription tags',
       'breadcrumb_name' => 'projects_taken']);
   }

   public function index_orphan()
   {
       $arr= Project::latest()->get()->filter(function ($p){return $p->is_orphan() && $p->is_visible();})->all();
       return view('frontend.project_list', ['projects' => $arr, 'page_title' => 'Projects without Supervisor',
       'breadcrumb_name' => 'projects_taken']);
   }

    public function create()
    {  // no real need to guard this here against anauthorized project creation
        if (Auth::guest() || ! Auth::user()->hasPermissionTo('write projects')) {
            abort(403,'Nice try! You do\'nt have permission to create projects.');
        }
        return view('frontend.create_project');
    }

    public function store(StoreProject $request)
    {
      $project = new Project;
      $project->title = $request->title;
      $project->abstract = $request->abstract;
      $project->description = $request->description;
      $project->type = $request->type;
      $project->author = $request->user()->id;
      if ($request->user()->hasPermissionTo('supervise projects')) {
        $project->supervisor = $request->user()->id; // if possible then author is also supervisor by default
      }
      // visibility not set in form, remains 0 (private)
      // semester_project always true for now
      $project->save();
      $request->user()->subscribe_matter($request->type); // we QUIETLY autosubscribe the user!
      return redirect()->route('frontend.project.show', $project->id)->withFlashSuccess('New project created. ONLY YOU can see it.');
    }

    public function update(UpdateProject $request, $id) { // all checks already done in request
        $project = Project::findOrFail($id);
        $project->title = $request->title;
        $project->abstract = $request->abstract;
        $project->description = $request->description;
        $project->type = $request->type;
        $project->save();
        $request->user()->subscribe_matter($request->type); // autosubscribe as in store()
        return redirect()->route('frontend.project.show', $id)->withFlashSuccess('Project updated.');
        }

   public function show ($id) {
      $project = Project::findOrFail($id);
      if ($project->is_visible(true) || $project->is_owner()) { // here we ignore subscriptions
        return view('frontend.single_project_view', ['project' => $project]);
      }
      else {
        abort(403,'Not allowed or not logged in.');
      }
   }

    public function edit($id)
    {
       $project = Project::findOrFail($id);
       if ($project->is_owner()) {
         return view('frontend.edit_project', ['project' => $project]);
       }
       return back()->withFlashDanger('You don\'t have permission to edit this project.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        if ($project->is_owner()) {
          if ($project->assigned_students()->isNotEmpty())
          {
              return back()->withFlashDanger('You must first release all students before you can delete a project.');
          }
          if ($project->secondreader !=0 ) {
              return back()->withFlashWarning('You must dismiss the second reader before deleting this project.');
          }
          if ($project->has_deliverables()) { // in theory this should always fail because attached file uploads imply attached students
              return back()->withFlashDanger('You must first delete all attached deliverables.');
          }
          $project->delete();
          return redirect()->route('frontend.project.index')->withFlashDanger('Project deleted.'); // this should be changed to "My Projects" when ready
        }
          return back()->withFlashDanger('You cannot delete someone else\'s project.');
    }

    public function set_visibility($id, $vis){ // I know this should be guarded by middleware and received by PUT request
      $project = Project::findOrFail($id);
      if ($project->is_owner() && ($vis >= 0) && ($vis <= 2)) { // takes care of Auth too
        if ( ($vis == 0) && ($project->secondreader != 0) ) {
            return back()->withFlashWarning('A project with a second reader engaged cannot be private.');
        }
        if ( $vis == 0 && count($project->assigned_students()) > 0 ) {
            return back()->withFlashDanger('A project with students engaged cannot be private.');
        }
        // I believe we do not need to test against attached deliverables because deliverables entail students assigned
        $project->timestamps = false; // temporarily so
        $project->visibility = $vis;
        $project->save();
        return back()->withFlashSuccess('Visibility modified.');
        }
      else {
        abort(403, 'I know you are messing with URLs!');
       }
    }

    public function supervised_projects($user_id) {
        $person = User::findOrFail($user_id);
        $arr = $person->supervised_projects();
        return view('frontend.project_list', ['projects' => $arr,
        'page_title' => 'Projects supervised by ' . $person->full_name,
        'breadcrumb_name' => 'supervised_projects', 'person' => $person]);
    }

    public function sr_projects($user_id) {
        $person = User::findOrFail($user_id);
        $arr = $person->co_supervised_projects();
        return view('frontend.project_list', ['projects' => $arr,
        'page_title' => 'Projects where ' . $person->full_name . ' is Second Reader',
        'breadcrumb_name' => 'sr_projects', 'person' => $person]);
    }

    public function yielded_projects($user_id) {
        $person = User::findOrFail($user_id);
        $arr = $person->yielded_projects();
        return view('frontend.project_list', ['projects' => $arr,
        'page_title' => 'Projects created but not supervised by ' . $person->full_name,
        'breadcrumb_name' => 'yielded_projects', 'person' => $person]);
    }

    public function search(Request $request) {
        //dd($request->all());
        if (isset($request->needle)) {
            //dd($request->all());
            $needle = $request->needle;
            if (isset($request->search_all)) {
                $result = Project::search($needle)->get()->filter(function ($p) {return $p->is_visible(true);})->all();
                return view('frontend.search', ['needle' => $needle, 'hits' => $result, 'search_all' => 'yes']);
            } else {
                $result = Project::search($needle)->get()->filter(function ($p) {return $p->is_visible(false);})->all();
                return view('frontend.search', ['needle' => $needle, 'hits' => $result]);
            }
        }
        else {
            return view('frontend.search', ['hits' => null, 'needle' => '']);
        }
    }

}
