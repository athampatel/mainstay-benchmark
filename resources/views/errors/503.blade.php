@extends('errors.errors_layout')

@section('title')
    Oops! Service Down
@endsection

@section('error-content')
    <h2>Oops!</h2>
    <p>Service Down</p>
    <p>Try checking back after an hour</p>
    {{-- <a href="{{ route('admin.dashboard') }}">Back to Dashboard</a>
    <a href="{{ route('admin.login') }}">Login Again !</a> --}}
@endsection