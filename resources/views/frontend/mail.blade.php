@extends('frontend.layouts.app')

@section('title', app_name() . ' | Send Message')

@section('content')
    <div class="row justify-content-center">
        <div class="col col-sm-8 align-self-center">
            <div class="card">
                <div class="card-header">
                    <strong>
                        Send message
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    {{ html()->form('POST', route('frontend.mail.post'))->open() }}

                    @foreach ($recipients as $to_someone)
                        {{$to_someone->full_name}}<br>
                    @endforeach

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label('Your message:')->for('message') }}

                                    {{ html()->textarea('message')
                                        ->class('form-control')
                                        ->placeholder('Hello')
                                        ->attribute('rows', 8) }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group mb-0 clearfix">
                                    {{ form_submit('Send') }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    {{ html()->form()->close() }}
                </div><!--card-body-->
            </div><!--card-->
        </div><!--col-->
    </div><!--row-->
@endsection
