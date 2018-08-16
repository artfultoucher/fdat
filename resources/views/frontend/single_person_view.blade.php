@extends('frontend.layouts.app')
@section('title', $person->full_name)
@section('breadcrumbs', Breadcrumbs::render('view_person', $person))
@section('content')

@if($person->hasRole('student'))
<div class="card text-white bg-secondary" style="box-shadow: 5px 10px 8px #777777;">
@elseif ($person->hasRole('lecturer'))
<div class="card text-white bg-info" style="box-shadow: 5px 10px 8px #777777;">
@else
<div class="card  bg-light" style="box-shadow: 5px 10px 8px #777777;">
@endif
<div class="card-header h3">{{$person->full_name}}</div><!-- card-header-->
<div class="card-body">
@if (strlen($person->interests) > 9)
  <div class="card bg-light text-dark mb-3">
    <div class="card-header">{{$person->first_name}}'s introduction</div>
    <div class="card-body">@markdown($person->interests)</div>
  </div>
@endif
<ul class="list-group text-dark">
    @if ($person->supervised_projects()->isNotEmpty())
        <li class="list-group-item d-flex justify-content-between align-items-center">
          Supervised projects: <span class="badge badge-primary badge-pill">{{$person->supervised_projects()->count()}}</span>
        </li>
    @endif
    @if ($person->co_supervised_projects()->isNotEmpty())
        <li class="list-group-item d-flex justify-content-between align-items-center">
          Co-supervised projects: <span class="badge badge-primary badge-pill">{{$person->co_supervised_projects()->count()}}</span>
        </li>
    @endif
    @if ($person->yielded_projects()->isNotEmpty())
        <li class="list-group-item d-flex justify-content-between align-items-center">
          Yielded projects: <span class="badge badge-primary badge-pill">{{$person->yielded_projects()->count()}}</span>
        </li>
    @endif
    @if ($person->sproject_id > 0)
        <li class="list-group-item d-flex justify-content-between align-items-center">
          Working on: <span>{!! $person->link_to_sproject() !!}</span>
        </li>
    @endif
</ul>

</div><!-- card-body-->
</div><!-- card -->
@endsection
