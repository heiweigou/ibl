@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>User List</h1>
        </div>

            <div class="panel-body">
                <a class="btn btn-success" href="/register" role="button">Register User</a>
                @if(count($users) >0)
                <table class="table table-striped">
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                    @foreach($users as $user)
                        <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->type}}
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}
                        </tr>
                    @endforeach
                </table>
                @else
                    <p>You have no User</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
