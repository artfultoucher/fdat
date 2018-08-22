@extends('frontend.layouts.app')
@push('after-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
@endpush
@push('after-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

    <script>
    $('.selectpicker').selectpicker();
    </script>

@endpush

@section('title', 'Assign Students')

@section('breadcrumbs', Breadcrumbs::render('assign_students', $project))

@section('content')
    <select class="selectpicker">
      <option>Mustard</option>
      <option>Ketchup</option>
      <option>Relish</option>
    </select>

@endsection
