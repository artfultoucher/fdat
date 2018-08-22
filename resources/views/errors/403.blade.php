@extends('frontend.layouts.app')

@section('title', '403')

@section('content')
    <h1>HTTP 403</h1>
    <div class="alert alert-danger" role="alert">
      {{ $exception->getMessage() }}
    </div>

Now what?
@endsection
