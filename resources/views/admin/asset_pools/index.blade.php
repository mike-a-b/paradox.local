@extends('adminlte::page')

@section('title', 'Paradoxx:: Pools page')

@section('content_header')
<h1 class="m-0 text-dark">Asset pools</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <asset-pools-index></asset-pools-index>        
    </div>
</div>
<!-- /.row -->
@stop