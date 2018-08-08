<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use App\Http\Requests\StoreProject;
use App\Http\Requests\UpdateProject;
use App\Project;
use Illuminate\Support\Facades\Auth;


class ProjectController extends Controller
{

    public function index()
    {
        $result = array();
        foreach (Project::all() as $p) {
          if ($p->is_visible()) {
            $result[] = $p;
          }
        }
        return view('frontend.project_list', ['projects' => $result, 'page_title' => 'All Projects', 'breadcrumb_name' => 'projects']);
    }

    public function index_free()
    {
        $result = array();
        foreach (Project::all() as $p) {
          if ($p->is_available() && $p->is_visible()) {
            $result[] = $p;
          }
        }
        return view('frontend.project_list', ['projects' => $result, 'page_title' => 'Available Projects' , 'breadcrumb_name' => 'projects_free']);
    }

    public function create()
    {  // no real need to guard this here against anauthorized project creation
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
      return redirect()->route('frontend.project.show', $project->id)->withFlashSuccess('New project created. Only you can see it.');
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
      if ($project->is_visible()) {
        return view('frontend.single_project_view', ['project' => $project]);
      }
      else {
        abort(403,'Not allowed or not logged in.');
      }
   }

/*
// This version assumes permission is not checked in is_visible()
// It reports detailed 403 messages.

    public function show ($id) {
      $project = Project::findOrFail($id);
      if ($project->visibility == 2) {
        return view('frontend.single_project_view', ['project' => $project]); // public project
      }
      if (Auth::guest()) {
        abort(403,'You must at least be logged in to view this project.');
      }
      if ($project->visibility == 1) { // platform project and logged in
        if (Auth::user()->hasPermissionTo('view projects')) {
          if (Auth::user()->has_subscribed($project->type)) {
            return view('frontend.single_project_view', ['project' => $project]);
          } else {
            abort(403,'You are not subscribed to matter ' . $project->type . '.');  // even if user is owner. fix that?
          }
        } else {
          abort(403,'Insufficient permission. You can only view public projects.');
        }
      } else { // private project and logged in
        if (Auth()->user()->id == $project->owner()) {
          return view('frontend.single_project_view', ['project' => $project]);
        } else {
          abort(403,'You cannot view someone else\'s private projects.');
        }
      }
    }
*/
    /**
     * Show the form for editing the specified resource.

     */
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
          // TODO take care of engaged students and maybe the second reader
          // make sure function returns from here
          $project->delete();
          return redirect()->route('frontend.user.dashboard')->withFlashDanger('Project deleted.');
        }
          return back()->withFlashDanger('You cannot delete someone else\'s project.');
    }

    public function set_visibility($id, $vis){ // I know this should be guarded by middleware and received by PUT request
      $project = Project::findOrFail($id);
      if ($project->is_owner() && ($vis >= 0) && ($vis <= 2)) { // takes care of Auth too
        if ( ($vis == 0) && ($project->secondreader != 0) ) {
            return back()->withFlashWarning('A private project must not have a second reader engaged.');
        }
        // TODO test against engaged students
        $project->timestamps = false; // temporarily so
        $project->visibility = $vis;
        $project->save();
        return back()->withFlashSuccess('Visibility modified.');
        }
      else {
        abort(403, 'I know you are messing with URLs!');
       }
    }

}
