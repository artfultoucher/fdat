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
}
