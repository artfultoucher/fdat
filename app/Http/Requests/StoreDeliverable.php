<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Project;

class StoreDeliverable extends FormRequest
{

    public function authorize()
    {
        return true; // cehcks done in controller
    }

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
