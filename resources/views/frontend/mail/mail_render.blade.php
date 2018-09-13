{!! $request->message !!}
--
{{$logged_in_user->full_name}} via FDAT.
@role('student')
Sender has StudentID {{$logged_in_user->studentid}}.
@endrole
