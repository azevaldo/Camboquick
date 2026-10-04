@extends('app.layouts.app')

@section('content')
<h1>Infelizmente ocorreu um erro, tente Novamente</h1>
@if(session('error'))
<div class="alert alert-danger">
    {{session('error')}}
</div>
@endif
@endsection