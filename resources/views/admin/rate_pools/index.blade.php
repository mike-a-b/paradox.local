@extends('adminlte::page')

@section('title', 'Paradoxx:: Rates pools page')

@section('content_header')
<h1 class="m-0 text-dark">Rate pools</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <rate-pools-index></rate-pools-index>
    </div> 
</div>
<!-- /.row -->
@stop