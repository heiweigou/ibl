@extends('layouts.app')

@section('content')
    <a href="/posts" class="btn btn-default">Go Back</a>
    <h1>Edit Request</h1>
    {!! Form::open(['action'=>['PostsController@update', $post->id],'method'=>'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class ="form-group">
                {{Form::label('user','<Name>')}}
                {{Form::text('user',Auth::user()->name,['class'=>'form-control', 'placeholder'=>'Name'])}}
        </div>
        <div class ="form-group">
                {{Form::label('user','<Email>')}}
                {{Form::text('user',Auth::user()->email, ['class'=>'form-control', 'placeholder'=>'Email'])}}
        </div>
        <div class ="form-group">
                {{Form::label('title','<IP Ranges>')}}
                <div class="text-info">* Please put in the IP ranges in comma(,) seperated format. (e.g.123.123.123.123, 123.123.123.123/24)</div>
                {{Form::textarea('body','',['class'=>'form-control', 'placeholder'=>'IP Ranges'])}}
        </div>
        <div class="form-group">
                {{Form::file('cover_image')}}
        </div>
        {{Form::hidden('_method','PUT')}}
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}   
@endsection