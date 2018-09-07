@extends('frontend.layouts.app')

@section('title', app_name() . ' | '. $title)

@section('content')
    <div class="row mb-4">
        <div class="col">
            <div class="card bg-light">
                <div class="card-header fancy h3">
                    {{$title}}
                </div>
                <div class="card-body mytext">
                    @markdown($md)
                </div>
            </div><!--card-->
        </div><!--col-->
    </div><!--row-->

@endsection
