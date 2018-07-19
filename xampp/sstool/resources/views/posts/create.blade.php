@extends('layouts.app')

@section('content')
    <br>
    <a href="/posts" class="btn btn-secondary">Go Back</a>
    <h1>Create Request</h1>
    {!! Form::open(['action'=>'PostsController@store', 'method'=>'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class ="form-group">
                {{Form::label('user','<Name>')}}
                {{Form::text('name',Auth::user()->name,['value'=> Auth::user()->name,'class'=>'form-control', 'placeholder'=>'Name', 'disabled'])}}
        </div>
        <div class ="form-group">
                {{Form::label('user','<Email>')}}
                {{Form::text('email',Auth::user()->email, ['value'=> Auth::user()->email,'class'=>'form-control', 'placeholder'=>'Email', 'disabled'])}}
        </div>
        <div class ="form-group">
                {{Form::label('title','<IP Ranges>')}}
                <div class="text-info">* Please put in the IP ranges in comma(,) seperated format. (e.g.123.123.123.123, 123.123.123.123/24)</a>
                {{Form::textarea('body','',['class'=>'form-control', 'placeholder'=>'IP Ranges'])}}
        </div>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#requestModal">
            Submit Request
        </button>

        <!-- Modal -->
        <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="requestModalLabel">Confirm Details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <a>Name: </a> {{ Auth::user()->name }}
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
                    </div>
                  </div>
                </div>
              </div>
    {!! Form::close() !!}   
@endsection