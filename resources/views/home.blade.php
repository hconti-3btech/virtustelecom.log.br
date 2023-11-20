@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

    @if(session('msg'))
        <div class="alert alert-{{session('type')}}">
            <p>{{session('msg')}}</p>
        </div>
    @endif

    <p><b>Bem vindo ao sistema Estoque Virtus</b></p>
    <p>Estamos em construção, favor reportar quaisquer erro para o email <i><b>contato@3btech.com.br</b></i></p>
@stop