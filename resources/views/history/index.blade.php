@extends('layouts.app')

@section('history-links')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
@endsection

@section('meta-title', __('History'))

@section('zerion-wrapper-id', 'settings')

@section('content')
<section class="history">
    {{-- <user-history-page :history="$historyList"></user-history-page> --}}
    <div class="history_wrapper">
        <h1 class="history_title">{{ __('History') }}</h1>
        <div class="history_main_items_wrapper">
            @forelse ($historyList as $history)
            <div class="history_main_item" {!! $loop->iteration > $historyRowsToShowCount ? 'style="display:none"' : '' !!}>
                <p class="history_main_item_date">{{ $history->date_print }}</p>
                <div class="history_main_item_parent">
                    <div class="history_main_item_child">
                        <div class="history_main_item_child_img_info">
                            <div class="history_main_item_child_img">
                                @if (isset($history->transaction_type))
                                <img src="/images/{{ $history->deposit_usd > 0 ? 'child_img1.svg' : 'child_img5.svg'}}" alt="">
                                @else
                                <img src="/images/{{ $history->changer_field == 'deposits_usdt' ? 'child_img1.svg' : 'child_img5.svg'}}" alt="">
                                @endif
                            </div>
                            <div class="history_main_item_child_infos">
                                <p class="history_main_item_child_info_title">
                                @if ($history->transaction_type == 'P')
                                {{ __('Percentage') }} #{{ $history->id }}
                                @elseif ($history->transaction_type == 'U')
                                    {{ __($history->transaction_type_print) }}-{{ $history->deposit_usd > 0 ? __('Buy') : __('Sell') }} #{{ $history->id }}
                                @else
                                    {{ __($history->transaction_type_print) }} #{{ $history->id }}
                                @endif
                                </p>
                                <p class="history_main_item_child_info">{{ $history->time_print }}</p>
                            </div>
                        </div>
                        <div class="history_main_item_child2">
                            <div class="history_main_item_child_img_info">
                                @if ($history->pool_id)
                                <div class="history_main_item_child_img2">
                                    <img src="/{{ $history->pool_logo }}" alt="">
                                </div>
                                <div class="history_main_item_child_infos">
                                    <a href="{{ route('invest.pool', $history->pool_id) }}" class="history_main_item_child_info_link">{{ $history->pool_name }}</a>
                                    <p class="history_main_item_child_info"></p>
                                </div>
                                @elseif ($history->r_pool_id)
                                <div class="history_main_item_child_img2">
                                    <img src="/{{ $history->r_pool_logo }}" alt="">
                                </div>
                                <div class="history_main_item_child_infos">
                                    <div class="history_main_item_child_info_link">{{ $history->r_pool_name }}</div>
                                    <p class="history_main_item_child_info"></p>
                                </div>
                                @endif
                            </div>
                            <div class="history_main_item_child_info_wrapper">
                                <div class="history_main_item_child_info_img_wrapper" style="flex-wrap: wrap; width: 90px;justify-content: center;">
                                    <div
                                        @if (isset($history->transaction_type))
                                        class="{{ $history->deposit_usd > 0 ? 'fraction_color_plus' : 'fraction_color_minus' }}"
                                        @else
                                        class="{{ $history->changer_field == 'deposits_usdt' ? 'fraction_color_plus' : 'fraction_color_minus' }}"
                                        @endif
                                        style="flex: 0 0 100%;text-align:center"
                                    >
                                        {{ $history->deposit_usd_print }}$
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            @endforelse
        </div>
        @if (count($historyList) > $historyRowsToShowCount)
        <div class="more_transactions_btn_wrapper">
            <button class="more_transactions_btn">{{ __('More transactions') }}</button>
        </div>
        @endif
    </div>
</section>
@endsection
