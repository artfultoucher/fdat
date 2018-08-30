@extends('frontend.layouts.app')
@section('title', 'All Subscription Tags')
@section('breadcrumbs', Breadcrumbs::render('matters_all'))

@section('content')
<h3 class="mt-3">All Subscription Tags</h3>
@foreach ($keys as $key)

<div class="card mt-4">
 <div class="card-header"><span class="h5 fancy">{{$key}} - {{$json_obj->$key->title}}</span>
@auth ()
    @if ($logged_in_user->has_subscribed($key))
        <span class="float-right badge badge-info"><i class="fas fa-check"></i>Subscribed</span>
    @endif
@endauth
 </div>
    <div class="card-body">
        @foreach ($json_obj->$key->links as $link)
        <div class="row">
            <div class="col-2">{{$link->name}}</div>
            <div class="col"><a href="{{$link->url}}">{{$link->url}}</a></div>
        </div>
        @endforeach
    </div>
</div>

@endforeach

@endsection
