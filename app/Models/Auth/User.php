<?php

namespace App\Models\Auth;

use App\Models\Traits\Uuid;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use App\Models\Auth\Traits\Scope\UserScope;
use App\Models\Auth\Traits\Method\UserMethod;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Auth\Traits\SendUserPasswordReset;
use App\Models\Auth\Traits\Attribute\UserAttribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Auth\Traits\Relationship\UserRelationship;
use Illuminate\Support\Facades\Auth;

/**
 * Class User.
 */
class User extends Authenticatable
{
    use HasRoles,
        Notifiable,
        SendUserPasswordReset,
        SoftDeletes,
        UserAttribute,
        UserMethod,
        UserRelationship,
        UserScope,
        Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'avatar_type',
        'avatar_location',
        'password',
        'password_changed_at',
        'active',
        'confirmation_code',
        'confirmed',
        'timezone',
        'subscr_mask',
        'interests',
    ];

    public static $matter_bit_masks = ['BSc' => 1, 'MScCS' => 2, 'MScDA' => 4, 'MScIM' => 8, 'BADHIT' => 16];

    public static function matters () {
      return array_keys(self::$matter_bit_masks);
    }

    public function subscribe_matter ($matter) {
      $this->subscr_mask |= self::$matter_bit_masks[$matter];
      $this->save();
    }

    public function unsubscribe_matter ($matter) {
      $this->subscr_mask &= ~self::$matter_bit_masks[$matter];
      $this->save();
    }

    public function set_subscriptions_to_array ($arr) {
      $this->subscr_mask = 0; // unsubscribe all
      foreach ($arr  as $matter) {
        $this->subscribe_matter($matter);
      }
      $this->save();
    }

    public function has_subscribed($matter) {
      $flag = self::$matter_bit_masks[$matter];
      return (($this->subscr_mask & $flag) == $flag);
    }

    // these methods should perhaps be moved to PersonController

   // Decide if these functions return arrays or collections

   // Decide where to place them

/*

    The functions below filter against privacy setting but not against subscription matters

    Private projects are not shown if author is logged in!

*/

    public function yielded_projects() { // projects created by this user but supervised by someone else or none
        return $this->hasMany('App\Project', 'author')->where('visibility','>',0)->whereRaw('supervisor <> author')->get();
    }

    public function supervised_projects() {
      return $this->hasMany('App\Project', 'supervisor')->where('visibility','>',0)->get(); // these are *semester* projects!
    }

    public function co_supervised_projects() {
      return $this->hasMany('App\Project', 'secondreader')->where('visibility','>',0)->get();
    }

    public function supervised_students() {
        // BROKEN, perhaps the problem is the same key occuring multiple times
        /*
        $result = collect(array());
        $projects = $this->supervised_projects()->all();
        foreach ($projects as $project) {
            $result = $result->union($project->assigned_students()->all());
        }
        return $result->all();
        */
        // The implementation below inserts a student multiple times if they work on multiple projects
        // this can't happen with semester projects
        $result = array();
        $projects = $this->supervised_projects()->all();
        foreach ($projects as $project) {
            $students = $project->assigned_students()->all();
            foreach ($students as $student) {
                $result[] = $student;
            }
        }
        return $result;
    }

    public function my_projects() { // Only used in dashboard! This is the only function that returns private projects
        return \App\Project::all()->filter(function($p) {return $p->is_owner();});
    }

/* not used yet
    public function project_status() { // 0 no project, 1 platform project, 2 public project
        return $this->sproject_id == 0 ? 0 : \App\Project::findOrFail($this->sproject_id)->visibility;
    }
*/
    public function link_to_sproject() { // not elegant to generate HTML in model :-(. TODO: use helper or trait
        $project = \App\Project::findOrFail($this->sproject_id);
        if ($project->visibility == 2 || Auth::check() && Auth::user()->hasPermissionTo('view projects')) {
            return '<a href="' . route('frontend.project.show', $project->id ) . '">' . $project->title . '</a>';
        }
        else {
            return '<span class="text-info">Non-public project</span>';
        }
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     * @var array
     */
    protected $appends = ['full_name'];
}
