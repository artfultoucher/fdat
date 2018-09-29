@extends('frontend.layouts.app')
@push('after-styles')
    {{ style('css/bootstrap-select.min.css') }}
@endpush
@push('after-scripts')

    {{ script('js/bootstrap-select.min.js')}}
    <script>
    $('select').selectpicker();
    </script>

@endpush
@section('breadcrumbs', Breadcrumbs::render('mail_many'))
@section('title', 'FDAT | Send Message')

@section('content')
    <div class="row justify-content-center">
        <div class="col col-sm-8 align-self-center">
            <div class="card mt-4">
                <div class="card-header h4 fancy">
                        Send mail to many recipients
                </div><!--card-header-->

                <div class="card-body">
                    {{ html()->form('POST', route('frontend.mail.post'))->open() }}
                        <div class="row">
                            <div class="col">

                                To:
                                {{-- Plugin is from https://developer.snapappointments.com/bootstrap-select/ --}}
                                <select name="to_ids[]" multiple class="form-control mb-3" id="recipients" data-selected-text-format="count" data-actions-box="true"
                                data-style="btn-secondary" data-width="auto" data-live-search="true" data-header="Scroll or type to search. Click to toggle entry.">
                                    <optgroup label="Lecturers">
                                    @foreach ($lecturers as $lecturer)
                                        @if ( in_array($lecturer->id, $selected) )
                                            <option value="{{$lecturer->id}}" selected>{{$lecturer->full_name}}</option>
                                        @else
                                            <option value="{{$lecturer->id}}">{{$lecturer->full_name}}</option>
                                        @endif
                                    @endforeach
                                    <optgroup label="Students">
                                    @foreach ($students as $student)
                                        @if ( in_array($student->id, $selected) )
                                            <option value="{{$student->id}}" selected>
                                                StdID {{$student->studentid}} - {{$student->full_name}}
                                            </option>
                                        @else
                                            <option value="{{$student->id}}">
                                                StdID {{$student->studentid}} - {{$student->full_name}}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>

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
                                        ->value('Hello all,')
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
