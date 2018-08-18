<?php

require __DIR__.'/breadcrumbs/backend/backend.php';

// added for my app

// logged in users
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('frontend.user.dashboard'));
});

// Home > Projects
Breadcrumbs::for('projects', function ($trail) {
    $trail->parent('home');
    $trail->push('Projects', route('frontend.project.index'));
});

// Home > Projects > free
Breadcrumbs::for('projects_free', function ($trail) {
    $trail->parent('projects');
    $trail->push('Available', route('frontend.project.index_free'));
});

// Home > New Project
Breadcrumbs::for('new_project', function ($trail) {
    $trail->parent('projects');
    $trail->push('New', route('frontend.project.create'));
});

Breadcrumbs::for('view_project', function ($trail, $project) {
    $trail->parent('projects');
    $trail->push($project->title, route('frontend.project.show', $project->id));
});

Breadcrumbs::for('edit_project', function ($trail, $project) {
    $trail->parent('view_project', $project);
    $trail->push('Edit', route('frontend.project.edit', $project->id));
});

Breadcrumbs::for('assign_students', function ($trail, $project) {
    $trail->parent('view_project', $project);
    $trail->push('Assign students', route('frontend.project.student_form', $project->id));
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
    $trail->push('All students', route('frontend.person.show_all_students'));
});

Breadcrumbs::for('students', function ($trail) {
    $trail->parent('all_students');
    $trail->push('Relevant students', route('frontend.person.show_students'));
});

Breadcrumbs::for('all_lecturers', function ($trail) {
    $trail->parent('home');
    $trail->push('All lecturers', route('frontend.person.show_all_lecturers'));
});

Breadcrumbs::for('lecturers', function ($trail) {
    $trail->parent('all_lecturers');
    $trail->push('Relevant lecturers', route('frontend.person.show_lecturers'));
});

Breadcrumbs::for('supervised_students', function ($trail, $person) {
    $trail->parent('view_person', $person);
    $trail->push('Supervised students', route('frontend.person.supervisees', $person->id));
});

Breadcrumbs::for('supervised_projects', function ($trail, $person) {
    $trail->parent('view_person', $person);
    $trail->push('Supervised projects', route('frontend.person.supervised_projects', $person->id));
});

Breadcrumbs::for('sr_projects', function ($trail, $person) {
    $trail->parent('view_person', $person);
    $trail->push('Co-Supervised projects', route('frontend.person.sr_projects', $person->id));
});

Breadcrumbs::for('yielded_projects', function ($trail, $person) {
    $trail->parent('view_person', $person);
    $trail->push('Yielded projects', route('frontend.person.yielded_projects', $person->id));
});
