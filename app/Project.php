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

  //  public function is_editable() {
  //    return $this->is_owner() && Auth::user()->hasPermissionTo('write projects');
  //  }


    public function is_available(){
      return !($this->is_assigned() || $this->is_orphan()); // ah De Morgan...
    }

    public function is_assigned() {
      return false; // TODO
    }

    public function is_new(){
      return $this->created_at->diffInDays() < 3;
    }

    public function is_updated(){
      return !$this->is_new() && $this->updated_at->diffInDays() < 3;
    }

    public function is_visible () {
      if ($this->visibility == 2 || $this->is_owner()) { // public or owned
        return true;
      }
      // private or platform project of some other user
      if (Auth::check()) {
        return ($this->visibility > 0) && Auth::user()->hasPermissionTo('view projects') && Auth::user()->has_subscribed($this->type); // grrrr.... :-(
      }
      return false;
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

    public function second_reader_name()
      {
        if ($this->second_reader == 0) {
          return '';
        } else {
          return User::findOrFail($this->second_reader)->full_name;
        }
      }

    public static $possible_types = ['BSc', 'MScCS', 'MScDA', 'MScIM', 'BADHIT']; // each type must also be a matter in User.php

    public function colors () { // for use in views
      if ($this->is_owner()) { // top priority
        return ['bg-col' => 'bg-success', 'text-col' => 'text-white'];
      }
      if ($this->supervisor == 0) { // orphan
        return ['bg-col' => 'bg-warning', 'text-col' => 'text-dark'];
      }
      // insert test for small projects here!
      if ($this->visibility == 2) { // public, this case can be deleted in favour of small projects
        return ['bg-col' => 'bg-primary', 'text-col' => 'text-white'];
      }
      return ['bg-col' => 'bg-info', 'text-col' => 'text-white']; // remaining cases
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
