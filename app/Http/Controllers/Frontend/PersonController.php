<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth\User;

// I don't like the name 'UserController' since it could be confused with authentication and password handling.
// This controller is used to show lists of persons.

class PersonController extends Controller
{
    public function show($id) {
      $person = User::findOrFail($id);
      return view('frontend.single_person_view', ['person' => $person]);
    }


   public function show_all_students() {
       $students =  User::role('student')->get()->all();
       return view('frontend.person_list', ['persons' => $students, 'breadcrumb_name' => 'all_students',
        'title' => 'All registered students']);
    }


   public function show_students() {
      $students =  User::role('student')->get()->filter( function ($u) {return $u->shares_tags();} )->all();
      return view('frontend.person_list', ['persons' => $students, 'breadcrumb_name' => 'students',
     'title' => 'All students who share subscriptions with you']);
   }

   public function show_busy_students(){
       $students =  User::role('student')->get()->filter( function ($u) {return $u->shares_tags() && $u->sproject_id > 0;} )->all();
       return view('frontend.person_list', ['persons' => $students, 'breadcrumb_name' => 'busy_students',
      'title' => 'Students with projects who share subscriptions with you']);
   }

   public function show_free_students(){
       $students =  User::role('student')->get()->filter( function ($u) {return $u->shares_tags() && $u->sproject_id == 0;} )->all();
       return view('frontend.person_list', ['persons' => $students, 'breadcrumb_name' => 'free_students',
      'title' => 'Students without projects who share subscriptions with you']);
   }


   public function show_all_lecturers() {
       $lecturers =  User::role('lecturer')->get()->all();
       return view('frontend.person_list', ['persons' => $lecturers, 'breadcrumb_name' => 'all_lecturers',
       'title' => 'All registered Lecturers']);
   }


   public function show_lecturers() {
       $lecturers =  User::role('lecturer')->get()->filter( function ($u) {return $u->shares_tags();} )->all();
       return view('frontend.person_list', ['persons' => $lecturers, 'breadcrumb_name' => 'lecturers',
       'title' => 'Lecturers who share subscriptions with you']);
   }


   public function show_students_of_supervisor($id) {
       $supervisor = User::findOrFail($id);
       $students = $supervisor->supervised_students();
       return view('frontend.person_list', ['persons' => $students, 'title' => 'Supervisees of '. $supervisor->full_name,
       'breadcrumb_name' => 'supervised_students', 'breadcrumb_object' => $supervisor]);
   }

}
