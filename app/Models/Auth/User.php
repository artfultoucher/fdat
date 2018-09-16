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

// Here we define the subscription tags. TODO: Do this properly with a config file.

    public static $matter_bit_masks = ['BSc' => 0b1, 'MScCS' => 0b10, 'MScDA' => 0b100, 'MScIM' => 0b1000, 'BADHIT' => 0b10000];

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
      foreach ($arr as $matter) {
        $this->subscribe_matter($matter);
      }
      $this->save();
    }

    public function uploads_for_project($project_id) {
        return \App\Deliverable::where('project_id', $project_id)->where('uploader_id', $this->id)->get();
    }

    public function has_subscribed($matter) {
      $flag = self::$matter_bit_masks[$matter];
      return (($this->subscr_mask & $flag) == $flag);
    }

    public function yielded_projects() { // projects created by this user but supervised by someone else or none
        return $this->hasMany('App\Project', 'author')->whereRaw('supervisor <> author')->get()->filter(function ($p){return $p->is_visible();});
    }

    public function supervised_projects() {
      return $this->hasMany('App\Project', 'supervisor')->get()->filter(function ($p){return $p->is_visible();}); // these are *semester* projects!
    }

    public function co_supervised_projects() {
      return $this->hasMany('App\Project', 'secondreader')->get()->filter(function ($p){return $p->is_visible();});
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

    public function shares_tags() { // Does this user share subscription tags with the logged in user?
        return ((int) ($this->subscr_mask)) & ((int) (Auth::user()->subscr_mask)); // bitwise AND
        // Without the integer casting it breaks!! The types are STRING for some reason (sqlite?)
        // Also [lhs] != 0 is not working either!
        // The implementation below works also.
        /*
        foreach (self::matters() as $tag) {
            if ($this->has_subscribed($tag) && Auth::user()->has_subscribed($tag)) {
                return true;
            }
        }
        return false;
        */
    }

    public function project_html() { // horrible programming style but it keeps things simple
        if ($this->sproject_id == 0) {
            return '';
        }
        $project = \App\Project::findOrFail($this->sproject_id);
        if ($project->is_visible(true)) { // we ignore subscriptions!
            return '<a class="badge badge-success" href="' . route('frontend.project.show', $project->id ) . '">Working on</a>' . ' <i>' . $project->title . '</i>';
            }
        return '<span class="badge badge-success">Working on</span> <i class="text-muted">Non-public project</i>';
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
