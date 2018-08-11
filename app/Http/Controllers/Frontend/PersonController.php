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
       return view('frontend.person_list', ['persons' => $this->select_users('student', false), 'title' => 'Students who share subscriptions with you']);
   }

   public function show_all_students() {
       return view('frontend.person_list', ['persons' => $this->select_users('student', true), 'title' => 'All registered students']);
   }

   public function show_lecturers() {
       return view('frontend.person_list', ['persons' => $this->select_users('lecturer', false), 'title' => 'Lecturers who share subscriptions with you']);
   }

   public function show_all_lecturers() {
       return view('frontend.person_list', ['persons' => $this->select_users('lecturer', true), 'title' => 'All registered Lecturers']);
   }

   private function select_users ($role, $ignore_subscriptions) {
       $bitmask = $ignore_subscriptions ? 0b1111111111111111 : Auth::user()->subscr_mask; // works for up to 16 matters
       return User::role($role)->get()->filter(function ($u) use ($bitmask) {return ($bitmask & $u->subscr_mask);});
   }
}
