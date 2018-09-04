<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\DeliverableRequests;

use Illuminate\Support\Facades\Auth;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    use DeliverableRequests;

    public function index()
    {
        $my_projects = \App\Project::all()->filter(function($p) {return $p->is_owner() || $p->id == Auth::user()->sproject_id;});
        return view('frontend.user.dashboard', ['my_projects' => $my_projects]);
    }


}
