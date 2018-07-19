
@extends('layouts.app')
@section('content')
    <div class="jumbotron text-center">
        <h1>Welcome to the Self-Service Portal</h1>
        @guest
        <p>Please login to submit your request for AdHoc Scan.</p>
        <p>
            <a class="btn btn-primary btn-lg" href="/login" role="button">Login</a> 
            <!--<a class="btn btn-success btn-lg" href="/register" role="button">Register</a>-->
        </p>
        @else
        <a>You are logged-in as -- </a>{{ Auth::user()->name }}
        @endguest
    </div>
@endsection
