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

// Home -> My account
Breadcrumbs::for('profile', function ($trail) {
    $trail->parent('home');
    $trail->push('My account', route('frontend.user.profile.update'));
});
