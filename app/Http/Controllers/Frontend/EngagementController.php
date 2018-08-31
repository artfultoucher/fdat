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
      $user->subscribe_matter($project->type); // in theory the user could still not be subscribed to this matter.
      return back()->withFlashSuccess('You are now the supervisor of this project.');
    }


    public function unsupervise (Request $req, $project_id) {
      $project = Project::findOrFail($project_id);
      $user = $req->user();
      if ($project->supervisor != $user->id) {
        return back()->withFlashWarning('You are not the supervisor if this project.');
      }
      if($project->secondreader != 0) {
          return back()->withFlashWarning('You cannot abandon this project as long as there is still second reader attached.');
      }
      if(count($project->assigned_students()) > 0) {
          return back()->withFlashDanger('You cannot abandon this project as long as there are still students attached.');
      }
      $project->timestamps = false;
      $project->supervisor = 0;
      $project->secondreader = 0; // quietly dismissed without messaging
      $project->save();

      // TODO redirect to project view
      // check where view returns should be changed to redirects!

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
      $user->subscribe_matter($project->type); // there are cases.. at least in theory..
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


    public function reassign_students (Request $req, $project_id) { 
        $project = Project::findOrFail($project_id);
        if (!$project->user_can_assign_students()) {
            abort (403, 'You can\'t do that. Period.');
            }
        if ($project->has_deliverables()) {
            abort (403, 'You cannot move students around after the project has already received deliverables.');
        }
        foreach ($project->assigned_students() as $student) {
             $student->sproject_id = 0; // first we dismiss all currently assigned students
             $student->save();
         }
         if ($req->has('assigned_ids')) {
             foreach ($req->assigned_ids as $std_id) {
                 $student = User::findOrFail($std_id);
                 $student->sproject_id = $project_id;
                 $student->subscribe_matter($project->type); // just in case, might be needed at some stage
                 $student->save();
             }
            }
        return redirect()->route('frontend.project.show', $project_id)->withFlashSuccess('Students successfully reassigned.');
        }


    public function dismiss_students (Request $req, $project_id) {
        $project = Project::findOrFail($project_id);
        if ($req->user()->id != $project->supervisor) {
            return back()->withFlashDanger('Only the supervisor can assign and release students.');
        }
        if ($project->has_deliverables()) {
            return back()->withFlashDanger('You cannot do that on a project that has still deliverables attached.');
        }
        $students = $project->assigned_students();
        if ($students->isEmpty()) {
            return back()->withFlashWarning('There are no students assigned to this project.');
        }
        foreach ($students as $student) {
            $student->sproject_id = 0;
            $student->save();
         }
        return redirect()->route('frontend.project.show', $project_id)->withFlashSuccess('All assigned students have been freed from this project.');
    }


    public function student_form ($id, $pool = 'some') {
        $project = Project::findOrFail($id);
        $offer_all_students = ($pool == 'all_students' ? true : false);
        if ( ! $project->user_can_assign_students()) {
            return back()->withFlashWarning('You must be supervisor and the project must not be private.');
        }
        if ($project->has_deliverables()) {
            return back()->withFlashDanger('You cannot move students around after the project has already received deliverables. Delete those first.');
        }
            $users = User::orderBy('last_name')->get()->all();
            $available_ids = array(); // associative array; id => full_name
            $assigned_ids = array(); // array of integer
            foreach ($users as $user) {
                if ($user->sproject_id == $project->id) { // aready assigned to this project. permissions and subscriptions *must* be ignored here!!
                    $available_ids[$user->id] = 'StdID '. $user->studentid . ' - ' . $user->full_name;
                    $assigned_ids[] = $user->id; // additionally put this id to array of currently assigned students
                }
                elseif ($user->sproject_id == 0 && $user->hasPermissionTo('undertake projects') && ($offer_all_students || $user->has_subscribed($project->type))) {
                    // not yet assigned && can work on projects && has subscribed to this proejct type
                    $available_ids[$user->id] = 'StdID '. $user->studentid . ' - ' . $user->full_name;
                }
            }
        if (empty($available_ids)) {
            return back()->withFlashDanger('A rare case! No free student with proper subscription available!');
        }
        return view('frontend.assign_students', ['project' => $project, 'students' => $available_ids, 'selected_ids' => $assigned_ids]);
    }

}
