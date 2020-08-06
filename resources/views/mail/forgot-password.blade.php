@extends('mail.layout')

@section('content')
Hi {{ $object->user->name }},<br>
This is your reset password code: <strong>{{ $object->code }}</strong>, this code will expire in {{ $object->expires_at }}.
<br>
@endsection