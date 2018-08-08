<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */

Route::get('project/free', 'ProjectController@index_free')->name('project.index_free'); 
Route::resource('project', 'ProjectController');
Route::get('project/{id}/visibility/{vis}', 'ProjectController@set_visibility')->name('project.change_visibility'); // should be PUT but that's so awkward to do from buttons

Route::get('user/role/{string}', 'PersonController@show_with_role')->name('person.show_with_role');
Route::get('user/{id}', 'PersonController@show')->name('person.show');

Route::patch('project/supervise/{id}' , 'EngagementController@supervise')->name('project.supervise');
Route::patch('project/unsupervise/{id}' , 'EngagementController@unsupervise')->name('project.unsupervise');

Route::patch('project/second/{id}' , 'EngagementController@become_second_reader')->name('project.second');
Route::patch('project/unsecond/{id}' , 'EngagementController@dismiss_second_reader')->name('project.unsecond');
