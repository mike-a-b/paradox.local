@extends('adminlte::page')

@section('title', 'Paradoxx:: Transactions')

@section('content_header')
<h1 class="m-0 text-dark">Transactions</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <dashboard-transactions></dashboard-transactions>        
    </div>
</div>
<!-- /.row -->
@stop