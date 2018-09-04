<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;

trait DeliverableRequests {

// This is quick and dirty until we do this properly with permissions, a model and CRUD forms

    private $d_requests;

    public function __construct() {
        $fs = New \Illuminate\Filesystem\Filesystem();
        $json_file = $fs->get(app_path('deliverable_requests.json'));
        $this->d_requests = json_decode($json_file);
        foreach ($this->d_requests as $key => $obj) { // replace the string dates with Carbon instances
            $this->d_requests[$key]->due_date = Carbon::createFromFormat('d/m/Y', $obj->due_date);
            $this->d_requests[$key]->feedback_date = Carbon::createFromFormat('d/m/Y', $obj->feedback_date);
        }
    }

    private function upcoming_deliverable($code) { // The deliverable with the closest future deadline for a given project type
        //returns null if all in the past
        $result = null;
        $closest = Carbon::createFromFormat('d/m/Y', '30/12/2100'); // must be something in the distant future
        foreach ($this->d_requests as $obj) {
            $testing = $obj->due_date;
            if ($obj->project_type == $code && $testing->isFuture() && $testing->lt($closest)) {
                $closest = $testing;
                $result = $obj;
            }
        }
        return $result;
    }

    private function request_by_id($id) {
        foreach ($this->d_requests as $obj) {
            if ($obj->id == $id) {
                return $obj;
            }
        }
        abort(403, "No Deliverable request with such ID.");
    }

}
