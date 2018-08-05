@extends('frontend.layouts.app')

@section('content')

    Link to <a href="{{ route('frontend.project.index')}}">project list</a><p>
    Link to <a href="{{ route('frontend.project.create')}}">project creation form</a><p>
    Link to <a href="{{ route('frontend.project.show', 1)}}"> project 1.</a>
@endsection
