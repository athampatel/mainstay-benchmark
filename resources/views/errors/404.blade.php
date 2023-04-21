@extends('errors.errors_layout')

@section('title')
404 - Page Not Found
@endsection

@section('error-content')
    <h2>404</h2>
    <p>{{config('constants.404_page_message')}}</p>
    @if(Request::is('admin/*'))
        <a href="{{ route('admin.dashboard') }}">Back to Dashboard</a>
        <a href="{{ route('admin.login') }}">Login Again</a>
    @else 
        <a href="/dashboard">Back to Dashboard</a>
        <a href="/sign-in">Login Again</a>
    @endif
@endsection