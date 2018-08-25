@extends('frontend.layouts.app')
@section('breadcrumbs', Breadcrumbs::render($bc_name, $bc_object))
@section('title', app_name() . ' | Send Message')

@section('content')
    <div class="row justify-content-center">
        <div class="col col-sm-8 align-self-center">
            <div class="card mt-4">
                <div class="card-header">
                    <strong>
                        Send mail
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    {{ html()->form('POST', route('frontend.mail.post'))->open() }}
                        <div class="row">
                            <div class="col">
                                To:
                                @foreach ($recipients as $key => $to)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="to_ids[]" value="{{$to->id}}" checked id="box-id-{{$key}}">
                                        <label class="form-check-label" for="box-id-{{$key}}">
                                            {{$to->full_name}}
                                        </label>
                                    </div>
                                @endforeach
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="cc" value="yes" checked id="cc-box">
                                    <label class="form-check-label" for="cc-box">
                                        CC to yourself
                                    </label>
                                </div><p>
                                <div class="form-group">
                                    {{ html()->label('Subject:')->for('subject') }}
                                    {{ html()->text('subject')
                                        ->class('form-control')->required() }}
                                </div><!--form-group-->
                                <div class="form-group">
                                    {{ html()->label('Your message:')->for('message') }}
                                    {{ html()->textarea('message')
                                        ->class('form-control')
                                        ->value($intro)
                                        ->attribute('rows', 8)->required() }}
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
