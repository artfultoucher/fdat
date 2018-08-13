<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Project;
use App\Models\Auth\User;

class EngagementController extends Controller
{
    // for engaging and feeing persons to projects

    public function supervise (Request $req, $project_id) { // method is PATCH
      $user = $req->user();
      if (!$user->hasPermissionTo('supervise projects')) {
        return back()->withFlashDanger('You do not have permission to supervise projects.');
      }
      $project = Project::findOrFail($project_id);
      if ($project->supervisor != 0) {
        return back()->withFlashWarning('This project is already being supervised.');
      }
      $project->timestamps = false; // don't modify timestamps
      $project->supervisor = $user->id;
      $project->save();
      return back()->withFlashSuccess('You are now the supervisor of this project.');
    }


    public function unsupervise (Request $req, $project_id) {
      $project = Project::findOrFail($project_id);
      $user = $req->user();
      if ($project->supervisor != $user->id) {
        return back()->withFlashWarning('You are not the supervisor if this project.');
      }
      // TODO return with warning if others are still engaged
      $project->timestamps = false;
      $project->supervisor = 0;
      $project->secondreader = 0; // quietly dismissed without messaging
      $project->save();
      return back()->withFlashSuccess('You are now no longer supervising this project.');
    }


    public function become_second_reader (Request $req, $project_id) {
      $user= $req->user();
      if (!$user->hasPermissionTo('supervise projects')) {
        return back()->withFlashDanger('You do not have permission to become second reader.');
      }
      $project = Project::findOrFail($project_id);
      if ($project->supervisor == 0) {
        return back()->withFlashWarning('You can only do that if the project has a supervisor.');
      }
      if ($project->secondreader != 0) {
        return back()->withFlashWarning('This project has already a second reader.');
      }
      if ($project->supervisor == $user->id) {
        return back()->withFlashWarning('You cannot become second reader of your own project.');
      }
      $project->timestamps = false;
      $project->secondreader = $user->id;
      $project->save();
      return back()->withFlashSuccess('You are now the second reader of this project.');
    }


    public function dismiss_second_reader (Request $req, $project_id) {
      $project = Project::findOrFail($project_id);
      $user_id = $req->user()->id;
      if ($project->secondreader == 0) {
        return back()->withFlashWarning('This project does not have a second reader.');
      }
      if ( ($user_id != $project->supervisor) && ($user_id != $project->secondreader)) {
        return back()->withFlashWarning('Only supervisor or second reader of this project can do that.');
      }
      // TODO test against engaged students
      $project->timestamps = false;
      $project->secondreader = 0;
      $project->save();
      return back()->withFlashSuccess('Second reader dismissed.');
    }


    public function student_form ($id) { // TODO: Access control
        $project = Project::findOrFail($id);
        $users = User::all();
        $available_ids = array(); // associative array; id => full_name
        $assigned_ids = array(); // array of integer
        foreach ($users as $user) {
            if ($user->hasPermissionTo('undertake projects') && $user->has_subscribed($project->type))  {
                if ($user->sproject_id == 0) { // no semester project assigned
                    $available_ids[$user->id] = $user->studentid . ' - ' . $user->full_name;
                }
                elseif ($user->sproject_id == $project->id) { // already working on this project
                    $available_ids[$user->id] = $user->studentid . ' - ' . $user->full_name;
                    $assigned_ids[] = $user->id; // additionally put this id to array of currently assigned students
                }
            }
        }
        return view('frontend.assign_students', ['project' => $project, 'students' => $available_ids, 'selected_ids' => $assigned_ids]);
    }

    public function reassign_students (Request $req, $project_id) {

        // TODO

    }

}
