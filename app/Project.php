<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth\User;
use Laravel\Scout\Searchable;

class Project extends Model
{
    use Searchable;
    // these columns can be modified together at once (aka mass assignable)
    protected $fillable = ['title', 'type', 'author', 'supervisor', 'visibility', 'abstract', 'description'];

    public function toSearchableArray() { // which fields are searched. The id field MUST always be included!
        return array_only($this->toArray(), ['id', 'title', 'abstract', 'description']);
    }

    public function owner() {
      return $this->supervisor == 0 ? $this->author : $this->supervisor;
    }

    public function avatar(){ // the avatar of the owner
        return User::findOrFail($this->owner())->picture;
    }

    public function is_owner() { // is logged in user owner of this project?
      return Auth::check() && Auth::user()->id == $this->owner();
    }

    public function is_student() { // is logged in user undertaking this project
        return Auth::check() && $this->assigned_students()->pluck('id')->contains(Auth::user()->id);
    }

    public function is_examiner(){ // is logged in user involved in grading?
        return Auth::check() && (Auth::user()->id == $this->supervisor || Auth::user()->id == $this->secondreader);
    }

    public function is_orphan() { // no supervisor
      return $this->supervisor == 0;
    }

    public function is_available() { // available for students to take on
        // this is *not* the opposite of is_taken()
      if ($this->is_orphan() || $this->assigned_students()->isNotEmpty() )  {
          return false;
      }
      // return $this->visibility > 0; // not really need or..?
      return true;
    }

    public function is_taken() {
        return $this->assigned_students()->isNotEmpty();
    }

    public function user_can_assign_students() {
        return Auth::check() && Auth::user()->id == $this->supervisor && $this->visibility > 0; // must also return false for orphan!
    }

    public function assigned_students(){
        return $this->hasMany('App\Models\Auth\User', 'sproject_id', 'id')->get();
    }

    public function attached_deliverables() {
        return $this->hasMany('App\Deliverable', 'project_id', 'id')->get();
    }

    public function has_deliverables() {
        return $this->attached_deliverables()->isNotEmpty();
    }

    public function is_new(){
      return $this->created_at->diffInDays() < 3;
    }

    public function is_updated(){
      return !$this->is_new() && $this->updated_at->diffInDays() < 3;
    }

    public function is_visible($ignore_subscriptions = false){ // public projects are HIDDEN when logged in and not subscribed
      if (Auth::guest()) {
        return $this->visibility == 2;
      }
      /*
      // commented out upon MvD's request
      // If commented out then own but unsubscribed projects are only shown in the dashboard]
      if ($this->is_owner()){
        // return true;
        return $this->visibility > 0; // private projects hidden everywhere except in dashboard
      }
      */
      return ($this->visibility > 0) && Auth::user()->hasPermissionTo('view projects') && ($ignore_subscriptions || Auth::user()->has_subscribed($this->type));
    }


    public function author_name()
      {
        if ($this->author == 0) {
          return 'DEBUG CASE';
        } else {
          return User::findOrFail($this->author)->full_name; // see trait UserAttribute, not a function!
        }
      }


    public function supervisor_name()
      {
        if ($this->supervisor == 0) {
          return '';
        } else {
          return User::findOrFail($this->supervisor)->full_name;
        }
      }


    public function secondreader_name()
      {
        if ($this->secondreader == 0) {
          return '';
        } else {
          return User::findOrFail($this->secondreader)->full_name;
        }
      }


    public function owner_name()
      {
        return User::findOrFail($this->owner())->full_name;
      }


    public static $possible_types = ['BSc', 'MScCS', 'MScDA', 'MScIM', 'BADHIT']; // each type must also be a matter in User.php


    public function colors () { // for use in views
      if ($this->visibility == 0 ) {
          return ['bg-col' => 'bg-danger', 'text-col' => 'text-white']; // red
      }
      if ($this->is_owner()) {
        return ['bg-col' => 'bg-success', 'text-col' => 'text-white']; // green
      }
      if ($this->supervisor == 0) { // orphan
        return ['bg-col' => 'bg-warning', 'text-col' => 'text-dark']; // yellow
      }
      // insert test for small projects here!
      if ($this->visibility == 2) { // public, this case can be deleted in favour of small projects
        return ['bg-col' => 'bg-primary', 'text-col' => 'text-white']; // blue
      }
      return ['bg-col' => 'bg-info', 'text-col' => 'text-white']; // cyan, remaining cases
    }


    public function icons () {
      switch ($this->visibility) {
        case 0:
          return '<i class="fas fa-lock"></i>';
          break;
        case 1:
          return '<i class="fas fa-user-friends"></i>';
          break;
        case 2:
          return '<i class="fas fa-lock-open"></i>';
      }
    }
}
