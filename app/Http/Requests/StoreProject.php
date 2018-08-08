<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Project;
use Illuminate\Validation\Rule;

class StoreProject extends FormRequest
{

    public function authorize()
    {
        return Auth::check() && $this->user()->hasPermissionTo('write projects');
    }

    public function rules()
    {
        return [
          'title' => 'required|unique:projects|min:7|max:150',
          'abstract' => 'required|min:20|max:500',
          'description' => 'required|min:30|max:2000',
           'type' => 'required|' . Rule::in(Project::$possible_types), // pay attention! syntax errors throw weird eceptions here
          'semester_project' => 'sometimes|in:yes,no',
        ];
    }
}
