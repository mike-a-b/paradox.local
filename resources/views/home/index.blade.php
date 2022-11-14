@inject('bladeService', 'App\Services\BladeService')

@extends('layouts.app')

@section('meta-title', __('Overview'))

@section('zerion-wrapper-id', 'overview')

@section('content')
    <section class="overview">
        <div class="overview_wrapper">
            <div class="overview_title_all_networks_wrapper">
                <h1 class="overview_title">{{ __('Overview') }}</h1>
            </div>

            <div class="overview_items_wrapper">
                <div class="overview_item portfolio_item">
                    <div class="overview_item_title_btn">
                        <p class="overview_item_title">{{ __('Portfolio') }}</p>
                    </div>
                    <div class="portfolio_item_wrapper">
                        <div class="portfolio_item_info_wrapper" style="margin-bottom: 10px;">
                            <p class="portfolio_item_info1">
                                ${!! isset($authUser->profile) ? $bladeService->printPriceBig($userBalanceUsdt + $userAssetPoolBalance + $userRatePoolBalance) : 0 !!}</p>
                            <p class="portfolio_item_info2">
                                @if (false)
                                    <!-- <span class="{{ $userBalance24hDiffPers < 0 ? 'fraction_color_minus' : ($userBalance24hDiffPers > 0 ? 'fraction_color_plus' : '') }}">
                                {!! $userBalance24hDiffPers_print !!}% (${{ $userBalance24hDiffUsd }})
                                </span>                             -->
                                @endif
                                <span id="portfolio_user_balance_stat">

                                </span> <span class="portfolio_user_balance_stat24h">24h </span>
                            </p>
                        </div>
                        <div class="portfolio_item_img">
                         
                            <div class="tradingview-chart" id="chartUserBalance">
                                <div id="homeUserBalanceChart"></div>
                                <div class="chart-menu">
                                    <span class="date">1h</span>
                                    <span class="date selected">24h</span>
                                    <span class="date">7d</span>
                                    <span class="date">M</span>
                                    <span class="date">3M</span>
                                    <!-- <span class="date">1Y</span> -->
                                    <span class="date">ALL</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overview_item history_item_main">
                    <div class="overview_item_title_btn">
                        <p class="overview_item_title">{{ __('Yield dynamics') }}</p>
                    </div>
                    <div class="history_items_btn_wrapper">
                        <div class="history_items_wrapper" style="border:none;">
                            @foreach ($userBalanceStatisticsInfo as $balancePeriod)
                                <div class="history_item">
                                    <div class="history_item_img_title_info">
                                        <div class="history_item_title_info">
                                            <p class="history_item_title">{{ $balancePeriod['title'] }}</p>
                                        </div>
                                    </div>
                                    <p class="history_item_info">
                                        @php
                                            $balancePeriodStat = $balancePeriod['statistics'];
                                        @endphp
                                        @if ($balancePeriodStat)
                                            <span
                                                class="{{ $balancePeriodStat['direction'] === 'same' ? 'empty-stats' : ($balancePeriodStat['direction'] === 'up' ? 'fraction_color_plus' : 'fraction_color_minus') }}">
                                    @if ($balancePeriodStat['direction'] !== 'same')
                                                    {!! $balancePeriodStat['direction'] === 'up' ? '&plus;' : '&ndash;' !!}{{ $balancePeriodStat['fraction'] }}
                                                    % ({{$balancePeriodStat['fraction_usd']}})
                                                @else
                                                    0%
                                                @endif
                                </span>
                                        @else
                                            <span class="empty-stats">0%</span>
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="assets">
        <div class="assets_wrapper">
            @if ($userBalanceUsd)
                <div class="assets_title_items_titles_wrapper">
                    <h1 class="assets_title">
                        @if (!$userAssetPoolList->isEmpty())
                            {{ __('My crypto Pools') }}
                        @elseif(!$userRatePoolList->isEmpty() && $userAssetPoolList->isEmpty())
                            {{ __('Deposits') }}
                        @endif
                    </h1>
                    <div class="assets_items_titles_btn_wrapper">
                        <div class="assets_items_titles_wrapper">
                            @if (!$userAssetPoolList->isEmpty() || !$userRatePoolList->isEmpty())
                                <p class="assets_items_title active" data-id="open_div1">{{ __('My pools') }}</p>
                                <!-- <p class="assets_items_title_dop{{ $userRatePoolList->isEmpty() ? '' : ' active' }}" data-id="open_div3">My rate pools</p> -->
                            @endif
                            @if (!$userAssetPoolList->isEmpty())
                                <p class="assets_items_title" data-id="open_div2">{{ __('My assets') }}</p>
                            @endif
                        </div>
                        <!-- <button class="assets_items_btn"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.5 11a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 11a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM18 12.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" fill="currentColor"></path></svg></button> -->
                    </div>
                </div>
            @endif
            <div class="assets_item{{ !$userAssetPoolList->isEmpty() ? ' open' : '' }}" id="open_div1">
                <div class="assets_item_main_link">
                    <div class="assets_item_link_icon">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"
                             class="tm5rm07">
                            <rect width="32" height="32" rx="2" fill="#2558D9"></rect>
                            <path d="M7 11h15a3 3 0 013 3v8a3 3 0 01-3 3H8a1 1 0 01-1-1V11z" stroke="#fff"
                                  stroke-width="2"></path>
                            <path d="M6 9a2 2 0 012-2h12a2 2 0 012 2H6z" fill="#fff"></path>
                        </svg>
                    </div>
                    <p class="assets_item_link_info">{{ __('Balance') }}
                        ${!! $userAssetPoolBalance ? $bladeService->printPriceBig($userAssetPoolBalance) : 0 !!}</p>
                    <p class="assets_item_link_info_first">{{ $userAssetPoolBalancePercentage }}%<span class="portfolio_user_balance_statstart">from the start</span></p>
                </div>
                <div class="assets_item_link_wrapper">
                    <div class="assets_item_link_titles_wrapper">
                        <p class="assets_item_link_title_main">{{ __('POOL') }}</p>
                        <p class="assets_item_link_title_main">{{ __('PRICE') }}</p>
                        <p class="assets_item_link_title_main">{{ __('AMOUNT') }}</p>
                        <p class="assets_item_link_title_main">{{ __('BALANCE') }}</p>
                    </div>
                    @forelse ($userAssetPoolList as $pool)
                        <a href="{{ route('invest.pool', $pool->asset_pool->id) }}"
                           class="assets_item_link" {!! $loop->iteration > $poolsToShowCount ? 'style="display:none"' : '' !!}>
                            <div class="assets_item_link_img_title">
                                <div class="assets_item_link_img">
                                    <img src="/{{ $pool->asset_pool->logo }}" alt="">
                                </div>
                                <p class="assets_item_link_title">{{ $pool->asset_pool->name }}</p>
                            </div>
                            <div class="assets_item_link_infos_wrapper">
                                <p class="assets_item_link_info3">${{ $pool->asset_pool->price_usd_print }}</p>
                                <p class="assets_item_link_info4 red_info">
                                    @php
                                        $aPoolId = $pool->asset_pool->id;
                                    @endphp
                                    @if (isset($poolsHistory[$aPoolId]) && $poolsHistory[$aPoolId]['direction'] !== 'same')
                                        @if ($poolsHistory[$aPoolId]['direction'] == 'up')
                                            <span class="fraction_color_plus">
                                    &plus;{{ $poolsHistory[$aPoolId]['fraction'] }}%
                                    (${{ $pool->price_diff_usd_print }})
                                </span>
                                        @else
                                            <span class="fraction_color_minus">
                                    &ndash;{{ $poolsHistory[$aPoolId]['fraction'] }}%
                                    (${{ $pool->price_diff_usd_print }})
                                </span>
                                        @endif
                                    @endif
                                </p>
                            </div>
                            <p class="assets_item_link_info2">{{ $pool->pool_balance }}</p>
                            <p class="assets_item_link_info1">${{ $pool->price_usd_print }}</p>
                        </a>
                    @empty
                    @endforelse
                    @if (count($userAssetPoolList) > $poolsToShowCount)
                        <div class="assets_item_more_links_wrapper">
                            <a class="assets_item_more_links" href="">Show all pools</a>
                        </div>
                    @endif
                </div>
            </div>
            @if (!$userRatePoolList->isEmpty() && !$userAssetPoolList->isEmpty())
                <div class="assets_item_header-small">
                    {{ __('Deposits') }}
                </div>
            @endif
            @if ($userRatePoolList->isNotEmpty())
                <div class="assets_item user_rate_pools open" id="open_div3">
                    <div class="assets_item_main_link">
                        <div class="assets_item_link_icon">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                 xmlns="http://www.w3.org/2000/svg" class="tm5rm07">
                                <rect width="32" height="32" rx="2" fill="#2558D9"></rect>
                                <path d="M7 11h15a3 3 0 013 3v8a3 3 0 01-3 3H8a1 1 0 01-1-1V11z" stroke="#fff"
                                      stroke-width="2"></path>
                                <path d="M6 9a2 2 0 012-2h12a2 2 0 012 2H6z" fill="#fff"></path>
                            </svg>
                        </div>
                        <p class="assets_item_link_info">{{ __('Balance') }}
                            ${!! $userRatePoolBalance ? $bladeService->printPriceBig($userRatePoolBalance) : 0 !!}</p>
                        <p class="assets_item_link_info_first">{{ $userRatePoolBalancePercentage }}%<span class="portfolio_user_balance_statstart">from the start</span></p>
                    </div>
                    <div class="assets_item_link_wrapper">
                        <div class="assets_item_link_titles_wrapper">
                            <p class="assets_item_link_title_main">{{ __('POOL') }}</p>
                            <p class="assets_item_link_title_main">% {{ __('MONTH') }}</p>
                            <p class="assets_item_link_title_main">% {{ __('TOTAL') }}</p>
                            <p class="assets_item_link_title_main">{{ __('BALANCE') }}</p>
                            <p class="assets_item_link_title_main">$ {{ __('BALANCE') }}</p>
                        </div>
                        @forelse ($userRatePoolList as $pool)
                            <a href="{{ route('invest.rate-pool', $pool->rate_pool->id) }}"
                               class="assets_item_link" {!! $loop->iteration > $poolsToShowCount ? 'style="display:none"' : '' !!}>
                                <div class="assets_item_link_img_title">
                                    <div class="assets_item_link_img">
                                        <img src="/{{ $pool->rate_pool->logo }}" alt="">
                                    </div>
                                    <p class="assets_item_link_title">{{ $pool->rate_pool->name }}</p>
                                </div>
                                <div class="assets_item_link_infos_wrapper">
                                    <p class="assets_item_link_info3">{{ $pool->rate_pool->rate_print }}%</p>
                                </div>
                                <p class="assets_item_link_info2">{{ $pool->currency_symbol_short }}{{ $pool->rate_total_print }}</p>
                                <p class="assets_item_link_info2">{{ $pool->currency_symbol_short }}{{ $pool->price_print }}</p>
                                <p class="assets_item_link_info1">${{ $pool->price_usd_print }}</p>
                            </a>
                        @empty
                        @endforelse
                        @if (count($userRatePoolList) > $poolsToShowCount)
                            <div class="assets_item_more_links_wrapper">
                                <a class="assets_item_more_links" href="">{{ __('Show all pools') }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="assets_item user_rate_pools open" id="open_div4">
                <div class="assets_item_main_link" style="margin-bottom: 0;">
                    <div class="assets_item_link_icon">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"
                             class="tm5rm07">
                            <rect width="32" height="32" rx="2" fill="#2558D9"></rect>
                            <path d="M7 11h15a3 3 0 013 3v8a3 3 0 01-3 3H8a1 1 0 01-1-1V11z" stroke="#fff"
                                  stroke-width="2"></path>
                            <path d="M6 9a2 2 0 012-2h12a2 2 0 012 2H6z" fill="#fff"></path>
                        </svg>
                    </div>
                    <p class="assets_item_link_info">{{ __('Balance') }}
                        USDT {!! $userBalanceUsdt ? $bladeService->printPriceBig($userBalanceUsdt) : 0 !!}</p>
                    <p class="assets_item_link_info_first">
                        <span class="informer_currency-price">${{ $teatherInfo['new_price'] }}</span>
                        <span
                            class="{{ $teatherInfo['direction'] == 'down' ? 'informer_currency-minus' : 'informer_currency-plus' }}">
                        {!! $teatherInfo['direction'] == 'down' ? '&ndash;' : '+' !!}{{ $teatherInfo['fraction'] }}%
                    </span>
                    </p>
                </div>
            </div>

            <div class="assets_item" id="open_div2">
                <div class="assets_item_link_wrapper">
                    <div class="assets_item_link_titles_wrapper">
                        <p class="assets_item_link_title_main">{{ __('ASSET') }}</p>
                        <p class="assets_item_link_title_main">{{ __('PRICE') }}</p>
                        <p class="assets_item_link_title_main">{{ __('AMOUNT') }}</p>
                        <p class="assets_item_link_title_main">{{ __('BALANCE') }}</p>
                    </div>
                    @forelse ($assetItemList as $asset)
                        <div
                            class="assets_item_link" {!! $loop->iteration > $assetsToShowCount ? 'style="display:none"' : '' !!}>
                            <div class="assets_item_link_img_title">
                                <div class="assets_item_link_img">
                                    <a href="https://coinmarketcap.com/currencies/{{ $asset['asset_slug'] }}/"
                                       target="_blank">
                                        <img src="/{{ $asset['asset_logo'] }}" alt="">
                                    </a>
                                </div>
                                <div class="assets_item_link_title_infos_wravper">
                                    <div class="assets_item_link_title_info6">
                                        <p class="assets_item_link_title">
                                            <a href="https://coinmarketcap.com/currencies/{{ $asset['asset_slug'] }}/"
                                               target="_blank">{{ $asset['asset_name'] }}</a>
                                        </p>
                                        <p class="assets_item_link_info6">{{ $asset['fraction_print'] }}%</p>
                                    </div>
                                    <!-- <p class="assets_item_link_info5">Wallet</p> -->
                                </div>
                            </div>
                            <div class="assets_item_link_infos_wrapper">
                                <p class="assets_item_link_info3">
                                    ${!! $bladeService->printAssetPrice($asset['asset_price_usd']) !!}
                                </p>
                                <p class="assets_item_link_info4 red_info">
                                    @php
                                        $assetId = $asset['asset_id'];
                                    @endphp
                                    @if (isset($assetHistory[$assetId]) && $assetHistory[$assetId]['direction'] != 'same')
                                        @if ($assetHistory[$assetId]['direction'] == 'up')
                                            <span class="fraction_color_plus">
                                    &plus;{{ $assetHistory[$assetId]['fraction'] }}%
                                    (${!! $bladeService->printAssetPrice($asset['history_price_change_usd']) !!})
                                </span>
                                        @else
                                            <span class="fraction_color_minus">
                                    &ndash;{{ $assetHistory[$assetId]['fraction'] }}%
                                    (${!! $bladeService->printAssetPrice($asset['history_price_change_usd']) !!})
                                </span>
                                        @endif
                                    @endif
                                </p>
                            </div>
                            <p class="assets_item_link_info2">{{ $asset['asset_amount_print'] }} {{ $asset['asset_symbol'] }}</p>
                            <p class="assets_item_link_info1">${{ $asset['price_usd_print'] }}</p>
                        </div>
                    @empty
                    @endforelse
                    @if (count($assetItemList) > $assetsToShowCount)
                        <div class="assets_item_more_links_wrapper">
                            <a class="assets_item_more_links" href="">{{ __('Show all assets') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
