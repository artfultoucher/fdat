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

    public function is_visible () {
      // TODO: allow adminstrator
      if (Auth::check()) { // IMO, permissions should only be tested in conroller
        if ($this->visibility == 0) { // private project
          return Auth::user()->id == $this->owner(); // always visible to owner, regardless of matter subscriptions
        } else { // public or platform project
          return Auth::user()->has_subscribed($this->type); // someone not logged in may see more projects!
        }
      } else {
        return $this->visibility == 2; // public project
      }
    }

    public function author_name()
      {
        if ($this->author == 0) {
          return 'DEBUG CASE';
        } else {
          return User::findOrFail($this->author)->full_name; // see trait UserAttribute
        }
      }

    public function supervisor_name()
      {
        if ($this->supervisor == 0) {
          return '-none-';
        } else {
          return User::findOrFail($this->supervisor)->full_name; // see trait UserAttribute
        }
      }

    public function second_reader_name()
      {
        if ($this->second_reader == 0) {
          return '-none-';
        } else {
          return User::findOrFail($this->second_reader)->full_name; // see trait UserAttribute
        }
      }

    public function is_editable() {
      return $this->is_owner() && Auth::user()->hasPermissionTo('write projects');
    }

    public function is_orphan() { // no supervisor
      return $this->supervisor == 0;
    }

    public static $possible_types = ['BSc', 'MScCS', 'MScDA', 'MScIM', 'BADHIT']; // each type must also be a matter in User.php
}
