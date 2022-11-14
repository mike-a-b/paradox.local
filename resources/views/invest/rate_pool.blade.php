@inject('bladeService', 'App\Services\BladeService')

@extends('layouts.app')

@section('history-links')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
@endsection

@section('meta-title', $metaTitle)

@section('zerion-wrapper-id', 'invest')

@section('content')
<section class="invest_link_child">
    <div class="invest_link_child_wrapper">
        <div class="invest_link_child_backlink">
            <a href="{{ url()->previous() ?? route('invest') }}">🠠 {{ __('Back') }}</a>
        </div>

        <div class="invest_link_child_items_wrapper">
            <div class="invest_link_child_item first_invest_link_child_item">
                <div class="invest_link_child_item_first_info_wrapper">
                    <div  class="invest_link_child_item_img_title_links_wrapper">
                        <div class="invest_link_child_item_img_title">
                            <div class="invest_link_child_item_img1">
                                <img src="/{{ $pool->logo }}" alt="">                                                                
                            </div>
                            <p class="invest_link_child_item_title1">{{ $pool->name_short }}</p>
                        </div>                        
                    </div>
                    <h1 class="invest_link_child_item_main_title">
                        {{ $pool->name }}                        
                    </h1>
                    <div class="invest_link_child_item_infos_wrapper">
                        <p class="invest_link_child_item_info1">
                            ${!! $bladeService->printPriceBig($userRatePool->price_usd) !!}                            
                        </p>         
                        <span class="price_start">
                            {{ __('started from') }} ${{ $userRatePool->price_start_usd_print}} {{ $userRatePool->date_start_print }}
                            <span class="created_at">{{ __('to') }} {{ $userRatePool->date_end_print }}</span>
                        </span>                        
                    </div>
                    <div class="invest_link_child_item_img2">                        
                        <div class="tradingview-chart">                            
                            <p class="invest_link_child_item_third_info_title">{{ __('History') }}</p>
                            <div class="invest_link_child_item_third_info_links_wrapper">
                                <div class="invest_link_child_item_third_info_links_titles_wrapper">
                                    <p class="invest_link_child_item_third_info_links_title">{{ __('Date') }}</p>
                                    <p class="invest_link_child_item_third_info_links_title">{{ __('Balance') }}</p>
                                    <p class="invest_link_child_item_third_info_links_title">{{ __('Percentage') }}</p>                                    
                                </div>
                                @forelse ($userPoolHistory as $item)
                                <div class="invest_link_child_item_third_info_link">                                    
                                    <p class="invest_link_child_item_third_info_link_info1">{{ $item->date_print }}</p>
                                    <p class="invest_link_child_item_third_info_link_info2">                                
                                        ${{ $item->pool_balance_usd_print }}
                                    </p>
                                    <p class="invest_link_child_item_third_info_link_info2">                                
                                        ${!! $bladeService->printAssetPrice($item->deposit_usd) !!}                                        
                                    </p>                                    
                                </div>
                                @empty                            
                                @endforelse                                                
                            </div>
                            <div class="mobile_invest_link_child_item_third_info_links_wrapper">
                                @forelse ($userPoolHistory as $item)
                                <div class="invest_link_child_item_third_info_link">                                    
                                    <div class="invest_link_child_item_third_info_link_title_info_wrapper">
                                        <p class="invest_link_child_item_third_info_links_title">{{ __('Date') }}</p>
                                        <p class="invest_link_child_item_third_info_link_info1">{{ $item->date_print }}</p>
                                    </div>
                                    <div class="invest_link_child_item_third_info_link_title_info_wrapper">
                                        <p class="invest_link_child_item_third_info_links_title">{{ __('Balance') }}</p>
                                        <p class="invest_link_child_item_third_info_link_info2">
                                            ${{ $item->pool_balance_usd_print }}                               
                                        </p>
                                    </div>
                                    <div class="invest_link_child_item_third_info_link_title_info_wrapper">
                                        <p class="invest_link_child_item_third_info_links_title">{{ __('Percentage') }}</p>
                                        <p class="invest_link_child_item_third_info_link_info2">
                                            ${!! $bladeService->printAssetPrice($item->deposit_usd) !!}                                 
                                        </p>
                                    </div>                                   
                                </div>
                                @empty                            
                                @endforelse
                            </div>  
                        </div>                                                
                    </div>
                </div>                                

                <div class="invest_link_child_item_third_info_wrapper" style="border-bottom:none;">
                    <p class="invest_link_child_item_four_info_title">{{ __('About') }} {{ $pool->name }}</p>
                    <p class="invest_link_child_item_four_info1">                        
                        {{ empty($pool->{'description'.$dbLocaleSufix}) ? $pool->description : $pool->{'description'.$dbLocaleSufix} }}
                    </p>                                      
                </div>
                                
            </div>            

        </div>
    </div>
</section>
@endsection