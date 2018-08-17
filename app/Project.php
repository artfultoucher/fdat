<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth\User;

class Project extends Model
{
    // these columns can be modified together at once (aka mass assignable)
    protected $fillable = ['title', 'type', 'author', 'supervisor', 'visibility', 'abstract', 'description'];


    public function owner() {
      return $this->supervisor == 0 ? $this->author : $this->supervisor;
    }


    public function is_owner() {
      return Auth::check() && Auth::user()->id == $this->owner();
    }


    public function is_orphan() { // no supervisor
      return $this->supervisor == 0;
    }


    public function is_available(){ // available for students to take on
      // return !($this->is_assigned() || $this->is_orphan()); // ah De Morgan...
      if ($this->is_orphan() || $this->assigned_students()->isNotEmpty() )  {
          return false;
      }
      // return $this->visibility > 0; // not really need or..?
      return true;
    }


    public function user_can_assign_students() {
        return Auth::check() && Auth::user()->id == $this->supervisor && $this->visibility > 0; // must also return false for orphan!
    }

    public function assigned_students(){
        return $this->hasMany('App\Models\Auth\User', 'sproject_id', 'id')->get();
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
      if ($this->is_owner()){
        // return true;
        return $this->visibility > 0; // private projects hidden everywhere except in dashboard
      }
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
