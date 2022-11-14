@inject('bladeService', 'App\Services\BladeService')

@extends('layouts.app')

@section('meta-title', 'Overview')

@section('zerion-wrapper-id', 'overview')

@section('content')
<section class="overview">
    <div class="overview_wrapper">
        <div class="overview_title_all_networks_wrapper">
            <h1 class="overview_title">Overview</h1>            
        </div>

        <div class="overview_items_wrapper">
            <div class="tradingview-chart" id="testChart"></div>
        </div>
        <div class="overview_items_wrapper">
            <pre id="testLog"></pre>
        </div>
    </div>
</section> 
@endsection 