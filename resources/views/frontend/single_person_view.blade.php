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
    <div class="card-header">
       This is the card header
    </div><!-- card-header-->
    <div class="card-body">
      <h2 class="card-title">{{$person->full_name}}</h2>
      More infor
    </div><!-- card-body-->
</div><!-- card -->
@endsection
