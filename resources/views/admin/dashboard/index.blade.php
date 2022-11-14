@extends('adminlte::page')

@section('title', 'Paradoxx:: Dashboard')

@section('content_header')
<h1 class="m-0 ml-1 text-dark">Dashboard</h1>
@stop

@section('content')
<div class="row ml-2">
    <div class="col-12">
        <div class="row">
            <div>
                <dashboard-exchange-info mode="dashboard"></dashboard-exchange-info>            
            </div>
            
            <div class="ml-4">
                <dashboard-notifications_log mode="dashboard"></dashboard-notifications_log>                
            </div>
        </div>        

        <div class="row mt-3">
            <dashboard-transactions mode="dashboard"></dashboard-transactions>                    
        </div>

        <div class="row mt-3">
            <a href="{{ route('admin.dashboard.exchange_bot_log') }}">Bot log</a>
        </div>
    </div>
</div>
<!-- /.row -->
@stop