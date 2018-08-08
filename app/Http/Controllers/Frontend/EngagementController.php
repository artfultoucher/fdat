<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Project;

class EngagementController extends Controller
{
    // for engaging and feeing persons to projects

    public function supervise (Request $req, $project_id){ // method is PATCH
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

    public function unsupervise (Request $req, $project_id){
      $project = Project::findOrFail($project_id);
      $user = $req->user();
      if ($project->supervisor != $user->id) {
        return back()->withFlashWarning('You are not the supervisor if this project.');
      }
      // TODO return with warning if others are still engaged
      $project->timestamps = false;
      $project->supervisor = 0;
      $project->save();
      return back()->withFlashSuccess('You are now no longer supervising this project.');
    }
}
