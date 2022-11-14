@extends('adminlte::page')

@section('title', 'Paradoxx:: Notifications')

@section('content_header')
<h1 class="m-0 text-dark">Notifications</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <dashboard-notifications_log></dashboard-notifications_log>
    </div>
</div>
<!-- /.row -->
@stop