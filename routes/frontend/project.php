<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::resource('project', 'ProjectController');
Route::get('project/{id}/visibility/{vis}', 'ProjectController@set_visibility')->name('change_visibility'); // should be PUT but that's so awkward to do from buttons
