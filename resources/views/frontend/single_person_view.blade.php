@extends('frontend.layouts.app')
@section('title', $person->full_name)
@section('breadcrumbs', Breadcrumbs::render('view_person', $person))
@section('content')
@include('frontend.includes.public_user_info')
@endsection
