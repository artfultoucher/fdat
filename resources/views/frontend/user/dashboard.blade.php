@extends('frontend.layouts.app')
@push('after-scripts')
<script>
$(function () {
  $('#pop').popover({title: "All your projects",
  content: "Here you can find all projects which you either own or undertake. This is the <strong>only place</strong> where your <strong>private projects</strong> (shown in red) appear.",
  placement: "left",
  trigger: "hover focus",
  html: true});
})
</script>
@endpush
@section('content')
<div class="card bg-light my-3">
 <div class="card-header h3 fancy"><i class="fas fa-tachometer-alt"></i> Dashboard</div><!--card-header-->
 <div class="card-body">
     @include('frontend.includes.public_user_info', ['person' => $logged_in_user])
     <div class="card bg-light">
         <div class="card-header"><span class="h5 fancy" id="pop" data-toggle="popover">My projects</span>
        @can('write projects')
        <a href="{{route('frontend.project.create')}}" class="btn btn-info float-right fancy"><i class="fas fa-plus-circle"></i> New Project</a>
        @endcan
         </div>
         <div class="card-body">
             <div class="card-columns">
                 @forelse ($my_projects as $project)
                     @include('frontend.includes.small_project_card')
                 @empty
                     None
                 @endforelse
             </div>
         </div> <!-- card-body -->
     </div>
</div>
</div><!-- card -->

@endsection
