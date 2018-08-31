<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Project;

class StoreDeliverable extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $project_id = $this->route('pid');
        $request_id = $this->route('rid');
        $project = Project::findOrFail($project_id);
        return $project->is_student(); // TODO request_id is currently ignored
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
    /*
    If Laravel throws PostTooLargeException, then check in php.ini
    max_file_size
    upload_max_filesize
    post_max_size
    They are usually limited to 2Mb
    */
        return [
            'document' => 'required|file|max:10000', // this is in Kb
        ];
    }
}
