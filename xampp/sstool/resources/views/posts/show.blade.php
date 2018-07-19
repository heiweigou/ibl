@extends('layouts.app')

@section('content')
    <br>
    <div class="row">
        <a href="/posts" class="btn btn-secondary">Go Back</a>
    </div>
    <h1>{{$post->title}} #{{$post->post_id}}</h1>
    <div>
        {!!$post->body!!}
    </div>
    <hr>
    <small>Written on {{$post->created_at}} by {{$post->user->name}}</small>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $post->user_id || Auth::user()->type == 'admin')
        <div class="row">
            <!--<a href="/posts/{{$post->id}}/edit" class="btn btn-info">Edit</a>-->
            <a>
                {!!Form::open(['action' => ['PostsController@destroy', $post->id], 'method'=>'POST','class'=>'pull-right'])!!}
                {{Form::hidden('_method','DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
                {!!Form::close()!!}
            </a>
        </div>
        @endif
    @endif
@endsection