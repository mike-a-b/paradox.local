@extends('adminlte::page')

@section('title', 'Paredoxx:: User Edit')

@section('content_header')
<!-- <h1 class="m-0 text-dark">Profile</h1> -->
@stop

@section('content')
<div class="row">
    <div class="col-12">

    <ul class="nav nav-tabs">        
        <li class="nav-item">
            <a class="nav-link{{ !$selectedTab ? ' active' : ''}}" data-toggle="tab" href="#asset_pools">
                Asset pools
                @if (count($pools))
                <span class="badge badge-secondary">{{ count($pools) }}</span>
                @endif                
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $selectedTab == 'rate-pool' ? ' active' : ''}}" data-toggle="tab" href="#rate_pools">
                Rate pools
                @if (count($ratePools))
                <span class="badge badge-secondary">{{ count($ratePools) }}</span>
                @endif                
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $selectedTab == 'user-info' ? ' active' : ''}}" data-toggle="tab" href="#info">Info</a>
        </li>
        <li class="nav-item pl-3">            
            <h3 class="mb-0 mt-1 text-gray">{{ round($userProfile->balance_usd, 2) }}<sup>$</sup> &nbsp;&nbsp; {{ round($userProfile->balance_usdt, 2) }}<sup>usdt</sup></h3>
        </li>
        <li class="nav-item pl-4 pt-1">
            <a href="{{ route('admin.users.balance_usdt-change', $userProfile->user_id) }}" class="btn btn-secondary btn-xs">&plusmn;USDT</a>
        </li>
    </ul>
    <div class="tab-content">        
        <div class="tab-pane fade bg-white p-4 {{ !$selectedTab ? 'show active' : ''}}" id="asset_pools">
            @include('admin.users.edit_asset-pools')
        </div>
        <div class="tab-pane fade bg-white p-4 {{ $selectedTab == 'rate-pool' ? 'show active' : ''}}" id="rate_pools">
            @include('admin.users.edit_rate-pools')
        </div>
        <div class="tab-pane fade bg-white {{ $selectedTab == 'user-info' ? 'show active' : ''}}" id="info">
            @include('admin.users.edit_user-info')
        </div>
    </div>                
</div>
@stop