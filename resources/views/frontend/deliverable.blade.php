@extends('frontend.layouts.app')
@section('title', app_name() . ' Hand in a Deliverable')
@section('breadcrumbs', Breadcrumbs::render('deliverable_upload', $project))
@section('content')
    <div class="row justify-content-center">
        <div class="col col-sm-8 align-self-center">
            <div class="card mt-4">
                <div class="card-header">
                    <strong>
                        Hand in a Deliverable
                    </strong>
                </div><!--card-header-->
                <div class="card-body">
                You have chosen to hand in the document called <p class="lead">{{$document_title}}</p> for your project <p class="lead">{{$project->title}}.</p>
                Please proceed only if the above is correct.
                <p>
                You can override a previously uploaded file as long as it has not been marked yet.
                </p>
                       <form method="post" action="{{route('frontend.deliverable.store', $project->id)}}" enctype="multipart/form-data">
                           @csrf
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label('Select file:')->for('document') }}
                                    {{ html()->file('document')->class('form-control')}}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group mb-0 clearfix">
                                    {{ form_submit('Upload') }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    {{ html()->form()->close() }}
                </div><!--card-body-->
            </div><!--card-->
        </div><!--col-->
    </div><!--row-->
@endsection
