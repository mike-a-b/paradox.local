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
                        <div class="invest_link_child_item_img_title_links_wrapper">
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
                                ${!! $bladeService->printPriceBig($pool->price_usd) !!}
                            </p>

                            <span id="pool_balance_stat" class="invest_link_child_item_info2"></span>

                            @if (false && isset($poolLastHistory['direction']) && $poolLastHistory['direction'] != 'same')
                                <!-- <span class="invest_link_child_item_info2">
                            @if ($poolLastHistory['direction'] == 'up')
                                    <span class="fraction_color_plus">&plus; {{ $poolLastHistory['fraction'] }}%</span>

                                @else
                                    <span class="fraction_color_minus">&ndash; {{ $poolLastHistory['fraction'] }}%</span>

                                @endif
                                </span> -->
                            @endif

                            <span class="price_start">
                            {{ __('started from') }} ${{ $startPointPriceUsd_print }}
                            <span class="created_at">{{ __('at') }} {{ $startPointCreatedAt_print }}</span>
                        </span>
                        </div>
                        <div class="invest_link_child_item_img2">
                            <div class="tradingview-chart" id="chartPool">
                                <div id="investChart"></div>
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
                            <asset-pool-sell-buy-box pool-id="{{ $pool->id }}"></asset-pool-sell-buy-box>
                        </div>
                    </div>
                    <div class="invest_link_child_item_second_info_wrapper" style="padding-bottom:1.5em;">
                        <p class="invest_link_child_item_second_info_title">{{ __('Stats') }}</p>
                        <div class="invest_link_child_item_second_info_item_wrapper">
                            @foreach ($poolStatistics as $poolPeriod)
                                <div class="invest_link_child_item_second_info_item">
                                    <p class="invest_link_child_item_second_info_item_title">{{ $poolPeriod['title'] }}</p>
                                    <p class="invest_link_child_item_second_info_item_text">
                                        @php
                                            $poolPeriodStat = $poolPeriod['statistics'];
                                        @endphp
                                        @if ($poolPeriodStat)
                                            @if ($poolPeriodStat['direction'] == 'same')
                                                <span class="empty-stats">0%</span>
                                            @elseif ($poolPeriodStat['direction'] == 'up')
                                                <span class="fraction_color_plus">&plus; {{ $poolPeriodStat['fraction'] }}%</span>
                                            @else
                                                <span class="fraction_color_minus">&ndash; {{ $poolPeriodStat['fraction'] }}%</span>
                                            @endif
                                        @else
                                            <span class="empty-stats">0%</span>
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="invest_link_child_item_third_info_wrapper" style="padding-bottom:1em;">
                        <p class="invest_link_child_item_four_info_title">{{ __('About') }} {{ $pool->name }}</p>
                        <p class="invest_link_child_item_four_info1">
                            {{ empty($pool->{'description'.$dbLocaleSufix}) ? $pool->description : $pool->{'description'.$dbLocaleSufix} }}
                        </p>
                    </div>

                    <div class="invest_link_child_item_four_info_wrapper" style=" padding-bottom:1em;">
                        <p class="invest_link_child_item_third_info_title">{{ __('Underlying Tokens') }}</p>
                        <div class="invest_link_child_item_third_info_links_wrapper">
                            <div class="invest_link_child_item_third_info_links_titles_wrapper">
                                <p class="invest_link_child_item_third_info_links_title">{{ __('Asset') }}</p>
                                <p class="invest_link_child_item_third_info_links_title">{{ __('Quantity') }}</p>
                                <p class="invest_link_child_item_third_info_links_title">{{ __('Price') }}</p>
                                <p class="invest_link_child_item_third_info_links_title">{{ __('Price change 24h') }}</p>
                                <p class="invest_link_child_item_third_info_links_title">{{ __('Allocation') }}</p>
                                <p class="invest_link_child_item_third_info_links_title">{{ __('Share') }}</p>
                            </div>
                            @forelse ($assets as $asset)
                                <div class="invest_link_child_item_third_info_link">
                                    <div class="invest_link_child_item_third_info_link_img_title">
                                        <div class="invest_link_child_item_third_info_link_img">
                                            <a href="https://coinmarketcap.com/currencies/{{ $asset->asset_slug }}/"
                                               target="_blank"><img src="/{{ $asset->logo }}" alt=""></a>
                                        </div>
                                        <p class="invest_link_child_item_third_info_link_title">
                                            <a href="https://coinmarketcap.com/currencies/{{ $asset->asset_slug }}/"
                                               target="_blank">{{ $asset->asset_name }}</a>
                                        </p>
                                    </div>
                                    <p class="invest_link_child_item_third_info_link_info1">{{ round($asset->asset_amount, 8) }} {{ $asset->symbol }}</p>
                                    <p class="invest_link_child_item_third_info_link_info2">
                                        ${!! $bladeService->printAssetPrice($asset->asset_price_usd) !!}
                                    </p>
                                    <p class="invest_link_child_item_third_info_link_info2">
                                        @php
                                            $assetId = $asset->asset_id;
                                        @endphp
                                        @if (isset($assetHistoryList[$assetId]) && $assetHistoryList[$assetId]['direction'] != 'same')
                                            @if ($assetHistoryList[$assetId]['direction'] == 'up')
                                                <span class="fraction_color_plus">
                                        &plus;{{ $assetHistoryList[$assetId]['fraction'] }}%
                                        (${!! round($asset->history_price_change_usd, 2) !!})
                                    </span>
                                            @else
                                                <span class="fraction_color_minus">
                                        &ndash;{{ $assetHistoryList[$assetId]['fraction'] }}%
                                        (${!! round($asset->history_price_change_usd, 2) !!})
                                    </span>
                                            @endif
                                        @endif
                                    </p>
                                    <p class="invest_link_child_item_third_info_link_info3">
                                        $ {{ round($asset->pool_item_price_usd, 2) }}</p>
                                    <p class="invest_link_child_item_third_info_link_info4">{{ round($asset->fraction, 2) }}
                                        %</p>
                                </div>
                            @empty
                            @endforelse
                        </div>
                        <div class="mobile_invest_link_child_item_third_info_links_wrapper">
                            @forelse ($assets as $asset)
                                <div class="invest_link_child_item_third_info_link">
                                    <div class="invest_link_child_item_third_info_link_img_title_icon">
                                        <div class="invest_link_child_item_third_info_link_img_title">
                                            <div class="invest_link_child_item_third_info_link_img">
                                                <img src="/{{ $asset->logo }}" alt="">
                                            </div>
                                            <p class="invest_link_child_item_third_info_link_title">
                                                <a href="https://coinmarketcap.com/currencies/{{ $asset->asset_slug }}/"
                                                   target="_blank">{{ $asset->asset_name }}</a>
                                            </p>
                                        </div>
                                        @if (false)
                                            <!-- <div class="invest_link_child_item_third_info_link_icon"><svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1l4 4-4 4" stroke="currentColor" stroke-linecap="round"></path></svg></div> -->
                                        @endif
                                    </div>
                                    <div class="invest_link_child_item_third_info_link_title_info_wrapper">
                                        <p class="invest_link_child_item_third_info_links_title">{{ __('Quantity') }}</p>
                                        <p class="invest_link_child_item_third_info_link_info1">{{ $asset->symbol }} {{ round($asset->asset_amount, 8) }}</p>
                                    </div>
                                    <div class="invest_link_child_item_third_info_link_title_info_wrapper">
                                        <p class="invest_link_child_item_third_info_links_title">{{ __('Price') }}</p>
                                        <p class="invest_link_child_item_third_info_link_info2">
                                            ${!! $bladeService->printAssetPrice($asset->asset_price_usd) !!}
                                        </p>
                                    </div>
                                    <div class="invest_link_child_item_third_info_link_title_info_wrapper">
                                        <p class="invest_link_child_item_third_info_links_title">{{ __('Price change 24h') }}</p>
                                        <p class="invest_link_child_item_third_info_link_info2">
                                            @php
                                                $assetId = $asset->asset_id;
                                            @endphp
                                            @if (isset($assetHistoryList[$assetId]) && $assetHistoryList[$assetId]['direction'] != 'same')
                                                @if ($assetHistoryList[$assetId]['direction'] == 'up')
                                                    <span class="fraction_color_plus">
                                            &plus;{{ $assetHistoryList[$assetId]['fraction'] }}%
                                            (${!! $bladeService->printAssetPrice($asset->history_price_change_usd) !!})
                                        </span>
                                                @else
                                                    <span class="fraction_color_minus">
                                            &ndash;{{ $assetHistoryList[$assetId]['fraction'] }}%
                                            (${!! $bladeService->printAssetPrice($asset->history_price_change_usd) !!})
                                        </span>
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                    <div class="invest_link_child_item_third_info_link_title_info_wrapper">
                                        <p class="invest_link_child_item_third_info_links_title">{{ __('Allocation') }}</p>
                                        <p class="invest_link_child_item_third_info_link_info3">
                                            $ {{ round($asset->pool_item_price_usd, 2) }}</p>
                                    </div>
                                    <div class="invest_link_child_item_third_info_link_title_info_wrapper">
                                        <p class="invest_link_child_item_third_info_links_title">{{ __('Share') }}</p>
                                        <p class="invest_link_child_item_third_info_link_info4">{{ round($asset->fraction, 2) }}
                                            %</p>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>

                    <div style="padding-bottom:1em;">
                      <!--   <p class="invest_link_child_item_four_info1">{{ __('Update every') }} {{ $pool->rebalance_frequency > 1 ? $pool->rebalance_frequency : '' }} {{ __('day') }}{{ $pool->rebalance_frequency > 1 ? 's' : '' }}</p>
                        <p class="invest_link_child_item_four_info1">{{ __('Update at') }} {{ $pool->rebalanced_at_print }}
                            , {{ __('next') }} {{ $pool->rebalanced_next_at_print }}</p> -->
							      <p class="invest_link_child_item_four_info1">{{ __('Update every') }} 10 min.</p>
                        <p class="invest_link_child_item_four_info1">Rebalance every 00:00</p> 
							
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
