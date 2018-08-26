@extends('frontend.layouts.app')
@section('title', 'View | ' . $project->title)
@section('breadcrumbs', Breadcrumbs::render('view_project', $project))
@push('after-scripts')
<script>
$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>
@endpush
@section('content')
<div class="card {!! $project->colors()['bg-col'] !!} {!! $project->colors()['text-col'] !!}" style="box-shadow: 5px 10px 8px #777777;">
<div class="card-header">
    <span class="h4 fancy">{{ $project->title }}</span><br>{{$project->type}}
</div><!--card-header-->
<div class="card-body">
    <div class="row mb-2">
        <div class="col">
            <div class="card text-white bg-secondary">
                <div class="card-header"><strong>Abstract</strong>
                </div>
                <div class="card-body">
                    {{$project->abstract}}
                </div>
            </div><!-- card -->
        </div>

        @if ($project->is_owner())

        <div class="col-3">
            <div class="card text-white bg-secondary">
                <div data-toggle="popover" data-trigger="hover" title="Modify this project"
                data-content="You can edit and delete projects and you can also change their visibility. Private projects are not shown to anyone except yourself.
                Permitted users means logged in users with the permission to view projects. Public means world wide."
                class="card-header"><strong>This project</strong></div>
                <div class="card-body">
                    <div class="btn-group">
                        <div class="dropdown show">
                            <button  class="btn btn-light dropdown-toggle mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 Modify
                            </button>
                            <div class="dropdown-menu">
                                   <a class="dropdown-item" href="{{route('frontend.project.edit', $project->id)}}">Edit this project</a>
                                  <form action="{{route('frontend.project.destroy', $project->id)}}" method="post">
                                     @csrf @method('delete')
                                     <button class="dropdown-item text-danger" type="submit">Delete this project</button>
                                 </form>
                            </div>
                        </div>

                        <div class="dropdown show">
                            <button type="button" class="btn btn-light dropdown-toggle mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Visibility
                            </button>
                            <div class="dropdown-menu">

                                   @switch($project->visibility)
                                   @case(0)
                                   <a class="dropdown-item active" href="#">Only you</a>
                                   <a class="dropdown-item" href="{{route('frontend.project.change_visibility', ['id' => $project->id, 'vis' => 1]) }}">Permitted users</a>
                                   <a class="dropdown-item" href="{{route('frontend.project.change_visibility', ['id' => $project->id, 'vis' => 2]) }}">Public</a>
                                   @break
                                   @case(1)
                                   <a class="dropdown-item" href="{{route('frontend.project.change_visibility', ['id' => $project->id, 'vis' => 0]) }}">Only you</a>
                                   <a class="dropdown-item active" href="#">Permitted users</a>
                                   <a class="dropdown-item" href="{{route('frontend.project.change_visibility', ['id' => $project->id, 'vis' => 2]) }}">Public</a>
                                   @break
                                   @default
                                   <a class="dropdown-item" href="{{route('frontend.project.change_visibility', ['id' => $project->id, 'vis' => 0]) }}">Only you</a>
                                   <a class="dropdown-item" href="{{route('frontend.project.change_visibility', ['id' => $project->id, 'vis' => 1]) }}">Permitted users</a>
                                   <a class="dropdown-item active" href="#">Public</a>
                                   @endswitch

                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- card -->
        </div><!-- col -->

         @endif

         @can('supervise projects')
             <div class="col-3">
                <div class="card text-white bg-secondary">
                     <div  data-toggle="popover" data-trigger="hover" title="Attach people to this project"
                     data-content="Specify which students undertake this project and which role you assume as a lecturer. A noticible constraint is that the
                     supervisor can only dismiss but not appoint the second reader."
                     class="card-header"><strong>Engage</strong></div>
                        <div class="card-body">
                            <div class="btn-group">
                               <div class="dropdown show">
                                 <button type="button" style="width: 6.5em;" class="btn btn-light dropdown-toggle mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Lecturers
                                 </button>
                                 <div class="dropdown-menu">
                                    <form action="{{route('frontend.project.supervise', $project->id)}}" method="post">
                                      @csrf @method('patch')
                                      <button class="dropdown-item" type="submit">Supervise</button>
                                    </form>
                                    <form action="{{route('frontend.project.unsupervise', $project->id)}}" method="post">
                                      @csrf @method('patch')
                                      <button class="dropdown-item" type="submit">UnSupervise</button>
                                    </form>
                                    <form action="{{route('frontend.project.second', $project->id)}}" method="post">
                                      @csrf @method('patch')
                                      <button class="dropdown-item" type="submit">Become Second Reader</button>
                                    </form>
                                    <form action="{{route('frontend.project.unsecond', $project->id)}}" method="post">
                                      @csrf @method('patch')
                                      <button class="dropdown-item" type="submit">Dismiss Second Reader</button>
                                    </form>
                                 </div>
                               </div>

                               <div class="dropdown show">
                                 <button type="button" style="width: 6.5em;" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Students
                                 </button>
                                 <div class="dropdown-menu">
                                   <a class="dropdown-item" href="{{route('frontend.project.student_form', $project->id)}}">ReAssign students</a>
                                   <form action="{{route('frontend.project.dismiss_students', $project->id)}}" method="post">
                                       @csrf @method('patch')
                                       <button class="dropdown-item" type="submit">Dismiss all students</button>
                                   </form>
                                 </div>
                             </div>
                         </div><!-- btn-group -->
                     </div><!-- card-body -->
                </div><!-- card -->
            </div><!-- col -->
        @endcan
    </div><!-- row -->
    <div class="card bg-light text-dark mt-2">
        <div class="card-header"><strong>Currently Engaged</strong>
            @auth ()
                <span  data-toggle="popover" data-trigger="hover" title="Mail to multiple recipients"
                data-content="Click to compose mail to some or all people who are currently involved in this project."
                class="float-right"><a class="btn btn-outline-dark" href="{{route('frontend.mail.project', $project->id)}}"><i class="fas fa-envelope"></i> Contact all</a></span>
            @endauth
        </div>
        <div class="card-body">
            <div class="card-block">
                <div class="row">
                    <div class="small col-md-3">
                        Supervisor
                    </div>
                    <div class="small col-md-3">
                        Second reader
                    </div>
                    <div class="small col-md-3">
                        Author
                    </div>
                    <div class="small col-md-3">
                        Assigned to
                    </div>
                </div>

                <div class="row">
                                   <div class="col-md-3">
                        @if($project->supervisor != 0)
                            <a href="{{route('frontend.person.show', $project->supervisor)}}">
                                <strong>{{$project->supervisor_name()}}</strong>
                            </a>
                        @else
                            <small>None</small>
                        @endif
                    </div>
                    <div class="col-md-3">
                        @if($project->secondreader != 0)
                            <a href="{{route('frontend.person.show', $project->secondreader)}}">
                                {{$project->secondreader_name()}}
                            </a>
                        @else
                            <small>None</small>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <a href="{{route('frontend.person.show', $project->author)}}">
                            {{$project->author_name()}}
                        </a>
                    </div>
                    <div class="col-md-3">
                        @forelse ($project->assigned_students() as $student)
                        <a href="{{route('frontend.person.show', $student->id)}}">
                            {{$student->full_name}}
                        </a><br>
                        @empty
                            <small>No students</small>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card bg-light text-dark mt-2">
                <div class="card-header"><strong>Full Project Description</strong>
                </div>
                <div class="card-body">
                @markdown($project->description)
                </div>
            </div><!-- card -->
        </div>
    </div>

    </div> <!-- card-body -->
    <div class="card-footer d-flex justify-content-between">
        {!! $project->icons() !!}
        <small><i class="fas fa-industry"></i>{{$project->created_at->diffForHumans()}} <i class="fas fa-edit"></i>{{$project->updated_at->diffForHumans()}}</small>
    </div>
</div><!-- card -->
@endsection
