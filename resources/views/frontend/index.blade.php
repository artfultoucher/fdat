@extends('frontend.layouts.app')

@section('title', app_name() . ' | '.__('navs.general.home'))

@section('content')
    <h1>Frank's Departmental Administration Tools</h1>

<div class="card-deck">
    <div class="card text-white bg-info" style="box-shadow: 5px 10px 8px #777777;">
        <div class="card-header h4 fancy">
            <i class="fas fa-home"></i> Welcome
        </div>
        <div class="card-body">
            <p>Welcome to FDAT. Currently this site might be useful for the administration of <strong>student projects</strong>.</p>
             <p>I will add more functions in the future.</p>
        </div>
    </div><!--card-->

    <div class="card text-white bg-primary" style="box-shadow: 5px 10px 8px #777777;">
        <div class="card-header h4 fancy">
            <i class="fas fa-user"></i> Accounts
        </div>
        <div class="card-body">
            <p>Most content is hidden from anonymous users.</p><p>Project students, supervisors and probably other users too must <strong>create an account</strong>.</p>
        </div>
    </div><!--card-->

    <div class="card text-white bg-secondary" style="box-shadow: 5px 10px 8px #777777;">
        <div class="card-header h4 fancy">
            <i class="fas fa-question"></i> Feedback
        </div>
        <div class="card-body">
         <p>Bug reports and feature requests are welcome.</p><p>But please read the sections under the <strong>help</strong> menu before doing so.</p>
        </div>
    </div><!--card-->

</div>
@endsection
