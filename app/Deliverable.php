<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Project;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Auth;


class Deliverable extends Model
{
use Http\Controllers\Frontend\DeliverableRequests;
// conveninence functions for use in views:

    public function project_title(){
        $project = Project::findOrFail($this->project_id);
        return $project->title;
    }

    public function uploader_name(){
        $uploader = User::findOrFail($this->uploader_id);
        return $uploader->full_name;
    }

    public function request_name() {
        return $this->request_by_id($this->request_id)->name;
    }

    public function is_marker() { // can the logged in user mark this
        $req = $this->request_by_id($this->request_id);
        $project = Project::findOrFail($this->project_id);
        $u_id = Auth::user()->id;
        if ($req->marked_by_supervisor && $project->supervisor == $u_id) {
            return true;
        }
        elseif ($req->marked_by_secondreader && $project->secondreader== $u_id) {
            return true;
        }
        else {
            return false;
        }
    }

    public function is_supervisor(){ // is logged in user supervisor of the associated project
        $project = Project::findOrFail($this->project_id);
        return Auth::user()->id == $project->supervisor;
    }

    public function is_examiner() { // is logged in user supervisor or second reader
        $project = Project::findOrFail($this->project_id);
        return $project->is_examiner();
    }

// only use this for object deletion:
    public function my_delete() {
        Storage::delete($this->path);
        \Log::info('Deleted file: ' . $this->path);
        $this->delete();
    }

/*  //This is not working
    public static function boot()
    {
        parent::boot();
        static::deleted(function($model) {
            Storage::delete($model->path);
            \Log::info('Deleted file: ' . $model->path);
            });
    }
*/
}
