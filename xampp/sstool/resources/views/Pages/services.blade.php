@extends('layouts.app')

@section('content')
    <h1>{{$title}}</h1>
    <p>This is the services page self-service portal for vun testing.</p>
    @if(count($services) >0)
        <ul class = "list-group">
        @foreach($services as $service)
            <ul>
                <li class="list-group-item">{{$service}}</li>
            </ul>
        @endforeach
        </ul>
    @endif
@endsection
