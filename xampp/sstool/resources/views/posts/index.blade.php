@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Posts</h1>
    @auth
    <div>
        <a href="/posts/create" class="btn btn-info">Create Request</a>
    </div>
    @endauth
    @if(count($posts) > 0)
        <table class="table table-striped table-hover">
                <tr>
                    <th>IP Ranges</th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach($posts as $post)
                    <tr>
                    <td>{{$post->body}}</td>
                    <td>Requested on {{$post->created_at}} by {{$post->user->name}} </td>
                    <td><a href="/posts/{{$post->id}}" class="btn btn-info">Show</a></td>
                    </tr>
                @endforeach
            </table>
    @else
        <p>No posts found</p>
    @endif
</div>
@endsection