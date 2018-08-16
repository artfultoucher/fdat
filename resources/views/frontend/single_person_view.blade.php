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
@if (strlen($person->interests) > 9)
<div class="card-body">
  <div class="card bg-light text-dark">
    <div class="card-header">{{$person->first_name}}'s introduction</div>
    <div class="card-body">@markdown($person->interests)</div>
  </div>
@endif

More info
</div><!-- card-body-->
</div><!-- card -->
@endsection
