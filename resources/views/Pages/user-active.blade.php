@extends('layouts.dashboard')

@section('content')
<div class="home-content">
    <span class="page_title">User active</span>
   
    <div class="">
        User name {{ $user['name']}}
    </div>
    <div class="">
        <a href="/user/{{$user['id']}}/active" class="btn btn-primary btn-rounded">Activate</a>
        <a href="/user/{{$user['id']}}/cancel" class="btn btn-warning btn-rounded">Cancel</a>
    </div>
</div>
@endsection