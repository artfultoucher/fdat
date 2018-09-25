<?php

namespace App\Http\Composers;

use Illuminate\View\View;
/**
 * Class GlobalComposer.
 */
class GlobalComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('logged_in_user', auth()->user());
        $total_projects = \App\Project::where('visibility', '>', 0)->count();
        $view->with('total_projects', $total_projects);
        $total_lecturers = \App\Models\Auth\User::role('lecturer')->count();
        $view->with('total_lecturers', $total_lecturers);
        $total_students = \App\Models\Auth\User::role('student')->count();
        $view->with('total_students', $total_students);
    }
}
