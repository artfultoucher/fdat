<?php

require __DIR__.'/breadcrumbs/backend/backend.php';

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('frontend.user.dashboard'));
});

Breadcrumbs::for('projects_all', function ($trail) {
    $trail->parent('home');
    $trail->push('All Projects', route('frontend.project.index_all'));
});

Breadcrumbs::for('projects_relevant', function ($trail) {
    $trail->parent('projects_all');
    $trail->push('Relevant to you', route('frontend.project.index'));
});

Breadcrumbs::for('projects_available', function ($trail) {
    $trail->parent('projects_relevant');
    $trail->push('Available');
});

Breadcrumbs::for('projects_taken', function ($trail) {
    $trail->parent('projects_relevant');
    $trail->push('Taken');
});

Breadcrumbs::for('projects_orphan', function ($trail) {
    $trail->parent('projects_relevant');
    $trail->push('Orphan');
});

Breadcrumbs::for('search_relevant', function ($trail) {
    $trail->parent('projects_relevant');
    $trail->push('Search');
});

Breadcrumbs::for('search_all', function ($trail) {
    $trail->parent('projects_all');
    $trail->push('Search');
});

// Home > New Project
Breadcrumbs::for('new_project', function ($trail) {
    $trail->parent('projects_all');
    $trail->push('New', route('frontend.project.create'));
});

Breadcrumbs::for('view_project', function ($trail, $project) {
    $trail->parent('projects_all');
    $trail->push($project->title, route('frontend.project.show', $project->id));
});

Breadcrumbs::for('edit_project', function ($trail, $project) {
    $trail->parent('view_project', $project);
    $trail->push('Edit', route('frontend.project.edit', $project->id));
});

Breadcrumbs::for('assign_students', function ($trail, $project) {
    $trail->parent('view_project', $project);
    $trail->push('Assign Students', route('frontend.project.student_form', $project->id));
});

// Home -> My account
Breadcrumbs::for('profile', function ($trail) {
    $trail->parent('home');
    $trail->push('My account', route('frontend.user.profile.update'));
});

Breadcrumbs::for('view_person', function ($trail, $person) {
    $trail->parent('home'); // change that later
    $trail->push($person->full_name, route('frontend.person.show', $person->id));
});

Breadcrumbs::for('all_students', function ($trail) {
    $trail->parent('home');
    $trail->push('All Students', route('frontend.person.show_all_students'));
});

Breadcrumbs::for('students', function ($trail) {
    $trail->parent('all_students');
    $trail->push('Relevant to you', route('frontend.person.show_students'));
});

Breadcrumbs::for('busy_students', function ($trail) {
    $trail->parent('students');
    $trail->push('With Project', route('frontend.person.show_busy_students'));
});

Breadcrumbs::for('free_students', function ($trail) {
    $trail->parent('students');
    $trail->push('Without Project', route('frontend.person.show_free_students'));
});

Breadcrumbs::for('all_lecturers', function ($trail) {
    $trail->parent('home');
    $trail->push('All Lecturers', route('frontend.person.show_all_lecturers'));
});

Breadcrumbs::for('lecturers', function ($trail) {
    $trail->parent('all_lecturers');
    $trail->push('Relevant to you', route('frontend.person.show_lecturers'));
});

Breadcrumbs::for('supervised_students', function ($trail, $person) {
    $trail->parent('view_person', $person);
    $trail->push('Supervised Students', route('frontend.person.supervisees', $person->id));
});

Breadcrumbs::for('supervised_projects', function ($trail, $person) {
    $trail->parent('view_person', $person);
    $trail->push('Supervised Projects', route('frontend.person.supervised_projects', $person->id));
});

Breadcrumbs::for('sr_projects', function ($trail, $person) {
    $trail->parent('view_person', $person);
    $trail->push('Co-Supervised Projects', route('frontend.person.sr_projects', $person->id));
});

Breadcrumbs::for('yielded_projects', function ($trail, $person) {
    $trail->parent('view_person', $person);
    $trail->push('Yielded Projects', route('frontend.person.yielded_projects', $person->id));
});

Breadcrumbs::for('mail_user', function ($trail, $user) {
    $trail->parent('view_person', $user);
    $trail->push('Compose Mail', route('frontend.mail.user', $user->id));
});

Breadcrumbs::for('mail_project', function ($trail, $project) {
    $trail->parent('view_project', $project);
    $trail->push('Compose mail to all involved', route('frontend.mail.project', $project->id));
});

Breadcrumbs::for('mail_many', function ($trail) {
    $trail->parent('home');
    $trail->push('Send Mail to Selected Users');
});

Breadcrumbs::for('matters_all', function ($trail) {
    $trail->parent('home');
    $trail->push('Subscription Tags', route('frontend.matters'));
});

Breadcrumbs::for('matters_single', function ($trail, $tag) {
    $trail->parent('matters_all');
    $trail->push($tag, route('frontend.matters', $tag));
});

Breadcrumbs::for('deliverable_upload', function ($trail, $project) {
    $trail->parent('view_project', $project);
    $trail->push('Upload Deliverable'); // TODO check other breadcrumbs if route can be eleminated
});

Breadcrumbs::for('deliverable_requests', function ($trail) {
    $trail->parent('home');
    $trail->push('Deliverable Requests', route('frontend.deliverable.all_requests'));
});

Breadcrumbs::for('my_deliverables', function ($trail) {
    $trail->parent('deliverable_requests');
    $trail->push('My Deliverables', route('frontend.deliverable.my'));
});

Breadcrumbs::for('feedback_form', function ($trail) {
    $trail->parent('my_deliverables');
    $trail->push('Feedback Form');
});
