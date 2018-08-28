<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;


class ExtraContentController extends Controller
{
    public function view_matters($code = null){
        $fs = New \Illuminate\Filesystem\Filesystem(); // It seems there is no facade for direct file access. Storage is something else.
        $json_file = $fs->get(app_path('matters.json')); // dunno if file_get_contents() is safe
        $obj = json_decode($json_file);
        if (empty($code)) { // show all matters
             return view('frontend.matters_all', ['json_obj' => $obj, 'keys' => User::matters()]); // note the dollar sign!
        } else {
            // The matter codes are the keys in the JSON. Dirty but simple.
            return view('frontend.matters_single', ['obj' => $obj->$code, 'code' => $code]); // note the dollar sign!
        }
    }
}
