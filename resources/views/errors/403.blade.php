@extends('errors.errors_layout')

@section('title')
    403 - Access Denied
@endsection

@section('error-content')
    <h2>403</h2>
    <p>Access to this resource on the server is denied</p>
    <hr>
    <p class="mt-2">
        {{ $exception->getMessage() }}
    </p>
    @if(Request::is('admin/*'))
        <a href="{{ route('admin.dashboard') }}">Back to Dashboard</a>
        <a href="{{ route('admin.login') }}">Login Again</a>
    @else 
        <a href="/dashboard">Back to Dashboard</a>
        <a href="/sign-in">Login Again</a>
    @endif
@endsection