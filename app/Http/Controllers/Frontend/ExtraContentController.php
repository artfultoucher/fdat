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

    public function help($section = 'about') {
        $fs = New \Illuminate\Filesystem\Filesystem();
        switch ($section) {
            case 'quickstart':
                $file = 'quickstart.md';
                $title = 'Information for users of the previous project system';
                break;
	    case 'orphans':
		$file = 'orphan.md';
		$title = 'Projects Without Supervisor';
		break;
            default:
                $file = 'about.md';
                $title = 'About FDAT';
        }
        $md = $fs->get(resource_path('text_content/' . $file));
        return view('frontend.help', ['md' => $md, 'title' => $title, 'breadcrumb' => $section]); //
    }
}
