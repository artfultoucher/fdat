<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */

Route::get('project/free', 'ProjectController@index_free')->name('project.index_free');
Route::resource('project', 'ProjectController');
Route::get('project/{id}/visibility/{vis}', 'ProjectController@set_visibility')->name('project.change_visibility'); // should be PUT but that's so awkward to do from buttons

Route::get('user/lecturers', 'PersonController@show_lecturers')->name('person.show_lecturers');
Route::get('user/all_lecturers', 'PersonController@show_all_lecturers')->name('person.show_all_lecturers');
Route::get('user/students', 'PersonController@show_students')->name('person.show_students');
Route::get('user/show_all_students', 'PersonController@show_all_students')->name('person.show_all_students');

Route::get('user/{id}', 'PersonController@show')->name('person.show');

Route::patch('project/supervise/{id}' , 'EngagementController@supervise')->name('project.supervise');
Route::patch('project/unsupervise/{id}' , 'EngagementController@unsupervise')->name('project.unsupervise');

Route::patch('project/second/{id}' , 'EngagementController@become_second_reader')->name('project.second');
Route::patch('project/unsecond/{id}' , 'EngagementController@dismiss_second_reader')->name('project.unsecond');

Route::get('project/students/{id}' , 'EngagementController@student_form')->name('project.student_form');
Route::patch('project/students/{id}' , 'EngagementController@reassign_students')->name('project.students');
