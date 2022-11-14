@inject('bladeService', 'App\Services\BladeService')

@extends('layouts.app')

@section('meta-title', 'Invest')

@section('zerion-wrapper-id', 'invest')

@section('content')
<section class="invest">
    <div class="invest_wrapper">
        <h1 class="invest_title">Invest</h1>
        <div class="invest-period-menu">
            <a href="?period=24hours" class="item {{$period=='24hours'?'active':''}}">24h</a>
            <a href="?period=7days" class="item {{$period=='7days'?'active':''}}">7d</a>
            <a href="?period=30days" class="item {{$period=='30days'?'active':''}}">M</a>
            <a href="?period=90days" class="item {{$period=='90days'?'active':''}}">3M</a>
            <a href="?period=ALL" class="item {{$period=='ALL'?'active':''}}">ALL</a>
        </div>
        @forelse ($ratePoolsGrouped as $pools) 
        @php
            $poolGroup = $pools[0]['group'];
            $poolsCount = count($pools);
        @endphp       
        <div class="invest_links_items_wrapper">
            <h2 class="invest_links_item_title">{{ $poolGroup['name'] }}</h2>
            <div class="invest_links_item_info_second_link">
                <p class="invest_links_item_info">{{ empty($poolGroup['description'.$dbLocaleSufix]) ? $poolGroup['description'] : $poolGroup['description'.$dbLocaleSufix] }}</p>
                @if ($poolsCount > 7)
                <a href="" class="invest_second_link">
                    <span class="invest_second_link_info">{{ __('See all') }}</span>                  
                </a>
                @endif                
            </div>

            <div class="invest_links_wrapper">
            <!-- {{ count($pools) < 7 ? ' invest_links_wrapper-short' : '' }} -->
                @forelse ($pools as $pool)
                <a href="{{ route('invest.rate-pool', $pool['id']) }}" class="invest_link{{ $loop->iteration > 6 ? ' invest_link-hide' : '' }}">
                    <div class="invest_link_img_info_btn_wrpper">
                        <div class="invest_link_img">
                            <img src="/{{ $pool['logo'] }}" alt="">
                        </div>
                        <div class="invest_link_info_btn_box">
                            <p class="invest_link_info1">{{ $pool['name'] }}</p>                            
                        </div>
                    </div>
                    <div class="invest_link_second_title_info">
                        <p class="invest_link_second_title">Rate</p>
                        <div class="invest_link_second_infos">                            
                            <p class="invest_link_info2">{!! $bladeService->printPriceBig($pool['rate']) !!}<span>%</span></p>
                            <p class="invest_link_info3">
                                @if (FALSE && isset($poolsHistory[$pool['id']]) && $poolsHistory[$pool['id']]['direction'] != 'same')                                
                                    @if ($poolsHistory[$pool['id']]['direction'] == 'up')
                                    <span class="fraction_color_plus">&plus; {{ $poolsHistory[$pool['id']]['fraction'] }}%</span>
                                    @else
                                    <span class="fraction_color_minus">&ndash; {{ $poolsHistory[$pool['id']]['fraction'] }}%</span>
                                    @endif                                
                                @endif
                            </p>
                        </div>
                    </div>
                </a>   
                @empty                                                                    
                @endforelse                
            </div>
        </div>
        @empty                                                    
        @endforelse
        
        @forelse ($poolsGrouped as $pools) 
        @php
            $poolGroup = $pools[0]['group'];
            $poolsCount = count($pools);
        @endphp       
        <div class="invest_links_items_wrapper">
            <h2 class="invest_links_item_title">{{ $poolGroup['name'] }}</h2>
            <div class="invest_links_item_info_second_link">
                <p class="invest_links_item_info">{{ empty($poolGroup['description'.$dbLocaleSufix]) ? $poolGroup['description'] : $poolGroup['description'.$dbLocaleSufix] }}</p>
                @if ($poolsCount > 7)
                <a href="" class="invest_second_link">
                    <span class="invest_second_link_info">{{ __('See all') }}</span>                  
                </a>
                @endif                
            </div>

            <div class="invest_links_wrapper">
            <!-- {{ count($pools) < 7 ? ' invest_links_wrapper-short' : '' }} -->
                @forelse ($pools as $pool)
                <a href="{{ route('invest.pool', $pool['id']) }}" class="invest_link{{ $loop->iteration > 6 ? ' invest_link-hide' : '' }}">
                    <div class="invest_link_img_info_btn_wrpper">
                        <div class="invest_link_img">
                            <img src="/{{ $pool['logo'] }}" alt="">
                        </div>
                        <div class="invest_link_info_btn_box">
                            <p class="invest_link_info1">{{ $pool['name'] }}</p>                            
                        </div>
                    </div>
                    <div class="invest_link_second_title_info">
                        <p class="invest_link_second_title">Price</p>
                        <div class="invest_link_second_infos">                            
                            <p class="invest_link_info2">${!! $bladeService->printPriceBig($pool['price_usd']) !!}</p>
                            <p class="invest_link_info3">
                                @if (isset($poolsHistory[$pool['id']]) && $poolsHistory[$pool['id']]['direction'] != 'same')                                
                                    @if ($poolsHistory[$pool['id']]['direction'] == 'up')
                                    <span class="fraction_color_plus">&plus; {{ $poolsHistory[$pool['id']]['fraction'] }}%</span>
                                    @else
                                    <span class="fraction_color_minus">&ndash; {{ $poolsHistory[$pool['id']]['fraction'] }}%</span>
                                    @endif                                
                                @endif
                            </p>
                        </div>
                    </div>
                </a>   
                @empty                                                                    
                @endforelse                
            </div>
        </div>
        @empty                                                    
        @endforelse
    </div>
</section>
@endsection