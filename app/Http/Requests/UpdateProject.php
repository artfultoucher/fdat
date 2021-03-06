<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Project;
use Illuminate\Validation\Rule;

class UpdateProject extends FormRequest
{

    public function authorize()
    {
        return Project::findOrFail($this->route('project'))->is_owner();
    }

    public function rules()
    {
        return [
          /*
          'title' must be unique in table 'projects' ignoring the row number of the project being updated`
          */
          'title' => 'required|min:7|max:120|' . Rule::unique('projects')->ignore($this->route('project')), // 'project' is the name of the route parameter
          'abstract' => 'required|min:15|max:350',
          'description' => 'required|min:50|max:5000',
           'type' => 'required|' . Rule::in(Project::$possible_types), // pay attention! syntax errors throw weird errors here
          'semester_project' => 'sometimes|in:yes,no',
        ];
    }
}
