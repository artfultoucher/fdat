{{ html()->modelForm($logged_in_user, 'PATCH', route('frontend.user.profile.intro'))->class('form-horizontal')->open() }}
    <div class="row">
        <div class="col">
            <p>You are welcome to tell other users about your interests etc.<br>
            @include('frontend.includes.markdown_label')</p>
            <div class="form-group">
                {{ html()->textarea('interests')->class('form-control')->attribute('rows', 10)->required() }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->

    <div class="row">
        <div class="col">
            <div class="form-group mb-0 clearfix">
                {{ form_submit('Save') }}
            </div><!--form-group-->
        </div><!--col-->
    </div><!--row-->
{{ html()->closeModelForm() }}
