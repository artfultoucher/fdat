<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Deliverable extends Model
{
    /*
    public function delete() { // I don't like model events for this. THe OO way is to redefine delete()
        Storage::delete($this->path);
        parent::delete();
    }
*/
    public static function boot()
    { // i think this is the proper way of redefining delete()
        parent::boot();
        static::deleting(function($model) {
            Storage::delete($model->path);
            \Log::info('Deleted file: ' . $model->path);
            });
    }
}
