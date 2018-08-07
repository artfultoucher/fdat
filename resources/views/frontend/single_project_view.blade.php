@extends('frontend.layouts.app')
@section('title', 'View | ' . $project->title)
@section('breadcrumbs', Breadcrumbs::render('view_project', $project))
@section('content')
               <div class="card {!! $project->colors()['bg-col'] !!} {!! $project->colors()['text-col'] !!}">
                 <div class="card-header">
                     <span class="h5">
                         {!! $project->icons() !!} {{ $project->title }}
                     </span><br><i class="fas fa-chalkboard"></i> {{$project->type}}
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
                         <div class="card text-white bg-dark">
                           <div class="card-header"><strong>This project</strong></div>
                           <div class="card-body">
                             <div class="btn-group">
                               <div class="dropdown show">
                                 <button  class="btn btn-secondary dropdown-toggle mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                 <button type="button" class="btn btn-secondary dropdown-toggle mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Visibility
                                 </button>
                                 <div class="dropdown-menu">

                                   @switch($project->visibility)
                                   @case(0)
                                   <a class="dropdown-item active" href="#">Only you</a>
                                   <a class="dropdown-item" href="{{route('frontend.change_visibility', ['id' => $project->id, 'vis' => 1]) }}">Permitted users</a>
                                   <a class="dropdown-item" href="{{route('frontend.change_visibility', ['id' => $project->id, 'vis' => 2]) }}">Public</a>
                                   @break
                                   @case(1)
                                   <a class="dropdown-item" href="{{route('frontend.change_visibility', ['id' => $project->id, 'vis' => 0]) }}">Only you</a>
                                   <a class="dropdown-item active" href="#">Permitted users</a>
                                   <a class="dropdown-item" href="{{route('frontend.change_visibility', ['id' => $project->id, 'vis' => 2]) }}">Public</a>
                                   @break
                                   @default
                                   <a class="dropdown-item" href="{{route('frontend.change_visibility', ['id' => $project->id, 'vis' => 0]) }}">Only you</a>
                                   <a class="dropdown-item" href="{{route('frontend.change_visibility', ['id' => $project->id, 'vis' => 1]) }}">Permitted users</a>
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
                        <div class="card text-white bg-dark">
                          <div class="card-header"><strong>Engage</strong></div>
                            <div class="card-body">
                              <div class="btn-group">
                               <div class="dropdown show">
                                 <button type="button" style="width: 6.5em;" class="btn btn-secondary dropdown-toggle mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   You
                                 </button>
                                 <div class="dropdown-menu">
                                   <a class="dropdown-item" href="#">Supervise</a>
                                   <a class="dropdown-item" href="#">UnSupervise</a>
                                   <a class="dropdown-item" href="#">Become Second Reader</a>
                                   <a class="dropdown-item" href="#">Dismiss Second Reader</a>
                                 </div>
                               </div>

                               <div class="dropdown show">
                                 <button type="button" style="width: 6.5em;" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Students
                                 </button>
                                 <div class="dropdown-menu">
                                   <a class="dropdown-item" href="#">ReAssign students</a>
                                   <a class="dropdown-item" href="#">Dismiss all students</a>
                                 </div>
                               </div>
                             </div><!-- btn-group -->
                           </div><!-- card-body -->
                        </div><!-- card -->
                       </div><!-- col -->

                     @endcan

                    </div><!-- row -->
                    <div class="row">
                      <div class="col">Supervisor: <strong>{{$project->supervisor_name()}}</strong></div><div class="col">Second Reader: <strong>{{$project->second_reader_name()}}</strong></div>
                      <div class="col">Author: <strong>{{$project->author_name()}}</strong></div><div class="col">Assigned to: </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="card bg-light text-dark">
                          <div class="card-header"><strong>Full Project Description</strong>
                          </div>
                          <div class="card-body">
                            {{$project->description}}
                          </div>
                       </div><!-- card -->
                      </div>
                    </div>

                </div> <!-- card-body -->
                <div class="card-footer">
                    <small><i class="fas fa-industry"></i>{{$project->created_at->diffForHumans()}} <i class="fas fa-edit"></i>{{$project->updated_at->diffForHumans()}}</small>
                </div>
            </div><!-- card -->
@endsection
