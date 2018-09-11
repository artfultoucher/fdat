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
          'title' => 'required|unique:projects|min:7|max:120',
          'abstract' => 'required|min:15|max:350',
          'description' => 'required|min:50|max:3000',
           'type' => 'required|' . Rule::in(Project::$possible_types), // pay attention! syntax errors throw weird eceptions here
          'semester_project' => 'sometimes|in:yes,no',
        ];
    }
}
