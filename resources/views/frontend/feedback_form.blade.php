@extends('frontend.layouts.app')
@section('title', 'Feedback Form')
@section('breadcrumbs', Breadcrumbs::render('feedback_form'))

@section('content')
    <div class="row mb-4">
        <div class="col">
          {{ html()->modelForm($doc , 'PATCH', route('frontend.deliverable.feedback_process', $doc->id))->open() }}
          <div class="form-group">
            {{ html()->label('Feedback to project student.')->for('comment') }}
            @include('frontend.includes.markdown_label')
            {{ html()->textarea('comment')->class('form-control')->attribute('rows', 10) }}
          </div><!--form-group-->
          <div class="form-group">
            {{ html()->label('Private comments.')->for('private_comment') }}
            @include('frontend.includes.markdown_label')
            {{ html()->textarea('private_comment')->class('form-control')->attribute('rows', 10) }}
          </div><!--form-group-->
          <div class="form-group mb-0 clearfix">
           {{ form_submit('Save') }}
          </div><!--form-group-->
          {{ html()->closeModelForm() }}
        </div><!--col-->
    </div><!--row-->
@endsection
