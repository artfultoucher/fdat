<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */

Route::get('project/all', 'ProjectController@index_all')->name('project.index_all');
Route::get('project/free', 'ProjectController@index_free')->name('project.index_free');
Route::get('project/taken', 'ProjectController@index_taken')->name('project.index_taken');

Route::resource('project', 'ProjectController');
Route::get('project/{id}/visibility/{vis}', 'ProjectController@set_visibility')->name('project.change_visibility'); // should be PUT but that's so awkward to do from buttons

Route::get('user/all_lecturers', 'PersonController@show_all_lecturers')->name('person.show_all_lecturers');
Route::get('user/lecturers', 'PersonController@show_lecturers')->name('person.show_lecturers');

Route::get('user/show_all_students', 'PersonController@show_all_students')->name('person.show_all_students');
Route::get('user/students', 'PersonController@show_students')->name('person.show_students');
Route::get('user/assigned_students', 'PersonController@show_busy_students')->name('person.show_busy_students');
Route::get('user/free_students', 'PersonController@show_free_students')->name('person.show_free_students');

Route::get('user/supervisees/{id}', 'PersonController@show_students_of_supervisor')->name('person.supervisees');
Route::get('user/{id}', 'PersonController@show')->name('person.show');

Route::get('user/supervised_projects/{id}', 'ProjectController@supervised_projects')->name('person.supervised_projects');
Route::get('user/sr_projects/{id}', 'ProjectController@sr_projects')->name('person.sr_projects');
Route::get('user/yielded_projects/{id}', 'ProjectController@yielded_projects')->name('person.yielded_projects');

Route::patch('project/supervise/{id}' , 'EngagementController@supervise')->name('project.supervise');
Route::patch('project/unsupervise/{id}' , 'EngagementController@unsupervise')->name('project.unsupervise');
Route::patch('project/second/{id}' , 'EngagementController@become_second_reader')->name('project.second');
Route::patch('project/unsecond/{id}' , 'EngagementController@dismiss_second_reader')->name('project.unsecond');

Route::get('project/students/{id}/{pool?}' , 'EngagementController@student_form')->name('project.student_form');
Route::patch('project/students/{id}' , 'EngagementController@reassign_students')->name('project.students');
Route::patch('project/dismiss_students/{id}' , 'EngagementController@dismiss_students')->name('project.dismiss_students');

Route::get('mail/user/{id}', 'MailController@mail_user')->name('mail.user');
Route::get('mail/project/{id}', 'MailController@mail_project')->name('mail.project');
Route::post('mail/post', 'MailController@mail_post')->name('mail.post');

Route::get('matters/{code?}', 'ExtraContentController@view_matters')->name('matters');

Route::patch('deliverable/feedback/{did}', 'DeliverableController@feedback_process')->name('deliverable.feedback_process');
Route::post('deliverable/download/', 'DeliverableController@download')->name('deliverable.download');
Route::get('deliverable/form/{pid}', 'DeliverableController@upload_form')->name('deliverable_form');
Route::delete('deliverable/delete', 'DeliverableController@delete')->name('deliverable.delete');
Route::post('deliverable/store/{pid}', 'DeliverableController@store')->name('deliverable.store');
Route::delete('deliverable/delete_many/{rid?}', 'DeliverableController@delete_many')->name('deliverable.delete_many');  // TODO guard this with proper middleware
Route::get('deliverable/index', 'DeliverableController@index')->name('deliverable.all_requests');
Route::get('deliverable/my', 'DeliverableController@my_deliverables')->name('deliverable.my');
Route::get('deliverable/feedback_form/{did}', 'DeliverableController@feedback_form')->name('deliverable.feedback_form');
