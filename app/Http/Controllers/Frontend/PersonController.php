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

   public function show_students() {
       return view('frontend.person_list', ['persons' => $this->select_users('student', false),
       'breadcrumb_name' => 'students',
       'title' => 'Students who share subscriptions with you']);
   }

   /*
   TODO: Show all students without project who share some subscription with viewer
   public function show_free_students() {
   }
   */

   public function show_all_students() {
       return view('frontend.person_list', ['persons' => $this->select_users('student', true),
       'breadcrumb_name' => 'all_students',
       'title' => 'All registered students']);
   }

   public function show_lecturers() {
       return view('frontend.person_list', ['persons' => $this->select_users('lecturer', false),
       'breadcrumb_name' => 'lecturers',
       'title' => 'Lecturers who share subscriptions with you']);
   }

   public function show_all_lecturers() {
       return view('frontend.person_list', ['persons' => $this->select_users('lecturer', true),
       'breadcrumb_name' => 'all_lecturers',
       'title' => 'All registered Lecturers']);
   }

   public function show_students_of_supervisor($id) {
       $supervisor = User::findOrFail($id);
       return view('frontend.supervised_students', ['supervisor' => $supervisor]);
   }

   private function select_users ($role, $ignore_subscriptions) {
       $bitmask = $ignore_subscriptions ? 0b1111111111111111 : Auth::user()->subscr_mask; // works for up to 16 matters
       return User::role($role)->get()->filter(function ($u) use ($bitmask) {return ($bitmask & $u->subscr_mask);})->all();
   }
}
