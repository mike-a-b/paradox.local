@extends('layout.app')

@section('history-links')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
@endsection

@section('meta-title', 'History')

@section('zerion-wrapper-id', 'settings')

@section('content')
<section class="history">
    <div class="history_wrapper">
        <h1 class="history_title">History</h1>
        @if (false)
        <!-- <div class="history_main_info_icon_wrapper">
            <div class="history_main_icon"><svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="9.5" stroke="#9C9FA8"></circle><path d="M9.999 3.757v4.73l3.998 1.787L10 3.757z" fill="currentColor"></path><path d="M10 3.757l-4 6.517 4-1.786V3.757zM9.999 13.34v3.214l4-5.535-4 2.32zM9.999 16.554V13.34l-4-2.32 4 5.535zM9.999 12.596l3.998-2.322L10 8.49v4.107z" fill="currentColor"></path><path d="M6 10.274l3.999 2.322V8.489l-4 1.785z" fill="currentColor"></path></svg></div>
            <p class="history_main_info">Only available for Ethereum. More networks coming soon.</p>
        </div>
        <div class="history_search_form_download_btns_wrapper">
            <form action="" class="history_search_form">
                <button class="history_search_form_btn"><span class="SearchInput__LeadingIcon-sc-15wgvpl-1 gZTczu"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px; color: var(--neutral-700);"><circle cx="10" cy="10" r="6" stroke="currentColor" stroke-width="2"></circle><path d="M14.5 14.5l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></span></button>
                <div class="history_search_form_input">
                    <input type="text" class="history_search_form_input_field" placeholder="Filter by Address, Protocol, Asset, Type">
                </div>
            </form>
            <div class="history_download_btn_calculate_btn_wrapper">
                <button class="history_download_btn">Download CSV</button>
                <button class="history_calculate_btn">Calculate Taxes</button>
            </div>
        </div> -->  
        @endif        
        <div class="history_main_items_wrapper">
            @forelse ($historyList as $history)
            <div class="history_main_item">
                <p class="history_main_item_date">{{ $history->date_print }}</p>
                <div class="history_main_item_parent">
                    <div class="history_main_item_child">
                        <div class="history_main_item_child_img_info">
                            <div class="history_main_item_child_img">
                                <img src="/images/{{ $history->balance_change > 0 ? 'child_img1.svg' : 'child_img5.svg'}}" alt="">
                            </div>
                            <div class="history_main_item_child_infos">
                                <p class="history_main_item_child_info_title">{{ $history->balance_change > 0 ? 'Receive' : 'Send' }}</p>
                                <p class="history_main_item_child_info">{{ $history->time_print }}</p>
                            </div>
                        </div>
                        <div class="history_main_item_child2">
                            <div class="history_main_item_child_img_info">
                                <div class="history_main_item_child_img2">
                                    <img src="/{{ $history->pool_logo }}" alt="">
                                </div>
                                <div class="history_main_item_child_infos">
                                    <a href="{{ route('invest.pool', $history->pool_id) }}" class="history_main_item_child_info_link">{{ $history->pool_name }}</a>
                                    <p class="history_main_item_child_info"></p>
                                </div>
                            </div>
                            <div class="history_main_item_child_info_wrapper">                            
                                <div class="history_main_item_child_info_img_wrapper" style="flex-wrap: wrap; width: 90px;justify-content: center;">
                                    <div class="{{ $history->balance_change > 0 ? 'fraction_color_plus' : 'fraction_color_minus' }}" style="flex: 0 0 100%;text-align:center">{{ $history->balance_change_print }}$</div>                                    
                                    <small>{{ $history->balance_usd_print }}$</small>
                                </div>
                            </div>
                        </div>

                    </div>
                    @if (false)
                        <!-- <div class="history_main_item_child_hidden_parent">
                            <div class="history_main_item_child_hidden">
                                <div class="history_main_item_child_hidden_info_wrapper">
                                    <p class="history_main_item_child_hidden_info_title">Collection</p>
                                    <p class="history_main_item_child_hidden_info">HappyLand Gummy Bears Official</p>
                                </div>
                                <div class="history_main_item_child_hidden_info_wrapper">
                                    <p class="history_main_item_child_hidden_info_title">Fee</p>
                                    <p class="history_main_item_child_hidden_info">0.039 ETH (₿0.002703)</p>
                                </div>
                                <div class="history_main_item_child_hidden_info_wrapper">
                                    <p class="history_main_item_child_hidden_info_title">Transaction hash</p>
                                    <div class="history_main_item_child_hidden_info_copy_btn_link_wrapper">
                                        <p class="history_main_item_child_hidden_info">0x4a1e...5faf</p>
                                        <div class="history_main_item_child_hidden_copy_btn_link_wrapper">
                                            <button class="history_main_item_child_hidden_copy_btn"><svg width="60%" height="60%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary-500)"><path d="M16 2a1 1 0 00-1-1H4c-1.1 0-2 .9-2 2v13a1 1 0 102 0V3h11a1 1 0 001-1zm3 3H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z" fill="currentColor"></path></svg></button>
                                            <a href="" class="history_main_item_child_hidden_info_link"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary-500)"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.667 15.889h6.666c.306 0 .556-.25.556-.556v-2.777c0-.306.25-.556.555-.556.306 0 .556.25.556.556v3.333C17 16.5 16.5 17 15.889 17H8.11C7.5 17 7 16.5 7 15.889V8.11A1.11 1.11 0 018.111 7h3.333c.306 0 .556.25.556.556 0 .305-.25.555-.556.555H8.667a.557.557 0 00-.556.556v6.666c0 .306.25.556.556.556zm4.998-7.778a.557.557 0 01-.556-.555c0-.306.25-.556.556-.556h2.777c.306 0 .556.25.556.556v2.777c0 .306-.25.556-.556.556a.557.557 0 01-.555-.556V8.894l-5.072 5.073a.553.553 0 11-.784-.784l5.073-5.072h-1.44z" fill="currentColor"></path></svg></a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div> -->
                    @endif                    
                </div>
            </div>
            @empty                
            @endforelse

            <!-- <div class="history_main_item">
                <p class="history_main_item_date">July 27, 2021</p>
                <div class="history_main_item_parent">
                    <div class="history_main_item_child">
                        <div class="history_main_item_child_img_info">
                            <div class="history_main_item_child_img">
                                <img src="../images/child_img3.svg" alt="">
                            </div>
                            <div class="history_main_item_child_infos">
                                <p class="history_main_item_child_info_title">Contract Execution</p>
                                <p class="history_main_item_child_info">10:05 PM</p>
                            </div>
                        </div>
                        <div class="history_main_item_child3">
                            <div class="history_main_item_child_info_wrapper">
                                <p class="history_main_item_child_info_title2">Application</p>
                                <div class="history_main_item_child_info_img_wrapper">
                                    <div class="history_main_item_child_info_img2">
                                        <img src="../images/child_img4.png" alt="">
                                    </div>
                                    <p class="history_main_item_child_info3">0x74ee...7d22</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="history_main_item_child_hidden_parent">
                        <div class="history_main_item_child_hidden">
                            <div class="history_main_item_child_hidden_info_wrapper">
                                <p class="history_main_item_child_hidden_info_title">Fee</p>
                                <p class="history_main_item_child_hidden_info">0.039 ETH (₿0.002703)</p>
                            </div>
                            <div class="history_main_item_child_hidden_info_wrapper">
                                <p class="history_main_item_child_hidden_info_title">Transaction hash</p>
                                <div class="history_main_item_child_hidden_info_copy_btn_link_wrapper">
                                    <p class="history_main_item_child_hidden_info">0x4a1e...5faf</p>
                                    <div class="history_main_item_child_hidden_copy_btn_link_wrapper">
                                        <button class="history_main_item_child_hidden_copy_btn"><svg width="60%" height="60%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary-500)"><path d="M16 2a1 1 0 00-1-1H4c-1.1 0-2 .9-2 2v13a1 1 0 102 0V3h11a1 1 0 001-1zm3 3H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z" fill="currentColor"></path></svg></button>
                                        <a href="" class="history_main_item_child_hidden_info_link"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary-500)"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.667 15.889h6.666c.306 0 .556-.25.556-.556v-2.777c0-.306.25-.556.555-.556.306 0 .556.25.556.556v3.333C17 16.5 16.5 17 15.889 17H8.11C7.5 17 7 16.5 7 15.889V8.11A1.11 1.11 0 018.111 7h3.333c.306 0 .556.25.556.556 0 .305-.25.555-.556.555H8.667a.557.557 0 00-.556.556v6.666c0 .306.25.556.556.556zm4.998-7.778a.557.557 0 01-.556-.555c0-.306.25-.556.556-.556h2.777c.306 0 .556.25.556.556v2.777c0 .306-.25.556-.556.556a.557.557 0 01-.555-.556V8.894l-5.072 5.073a.553.553 0 11-.784-.784l5.073-5.072h-1.44z" fill="currentColor"></path></svg></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="history_main_item">
                <p class="history_main_item_date">July 3, 2021</p>
                <div class="history_main_item_parent">
                    <div class="history_main_item_child">
                        <div class="history_main_item_child_img_info">
                            <div class="history_main_item_child_img">
                                <img src="../images/child_img5.svg" alt="">
                            </div>
                            <div class="history_main_item_child_infos">
                                <p class="history_main_item_child_info_title">Send</p>
                                <p class="history_main_item_child_info">12:37 AM</p>
                            </div>
                        </div>
                        <div class="history_main_item_child2">
                            <div class="history_main_item_child_img_info">
                                <div class="history_main_item_child_img2">
                                    <img src="../images/child_img6.png" alt="">
                                </div>
                                <div class="history_main_item_child_infos">
                                    <a href="" class="history_main_item_child_info_link">0.0064 ETH</a>
                                    <p class="history_main_item_child_info">₿0.000408</p>
                                </div>
                            </div>
                            <div class="history_main_item_child_info_wrapper">
                                <p class="history_main_item_child_info_title2">To</p>
                                <div class="history_main_item_child_info_img_wrapper">
                                    <div class="history_main_item_child_info_img2">
                                        <img src="../images/child_img4.png" alt="">
                                    </div>
                                    <p class="history_main_item_child_info3">0xa277...cea6</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="history_main_item_child_hidden_parent">
                        <div class="history_main_item_child_hidden">
                            <div class="history_main_item_child_hidden_info_wrapper">
                                <p class="history_main_item_child_hidden_info_title">Fee</p>
                                <p class="history_main_item_child_hidden_info">0.039 ETH (₿0.002703)</p>
                            </div>
                            <div class="history_main_item_child_hidden_info_wrapper">
                                <p class="history_main_item_child_hidden_info_title">Transaction hash</p>
                                <div class="history_main_item_child_hidden_info_copy_btn_link_wrapper">
                                    <p class="history_main_item_child_hidden_info">0x4a1e...5faf</p>
                                    <div class="history_main_item_child_hidden_copy_btn_link_wrapper">
                                        <button class="history_main_item_child_hidden_copy_btn"><svg width="60%" height="60%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary-500)"><path d="M16 2a1 1 0 00-1-1H4c-1.1 0-2 .9-2 2v13a1 1 0 102 0V3h11a1 1 0 001-1zm3 3H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z" fill="currentColor"></path></svg></button>
                                        <a href="" class="history_main_item_child_hidden_info_link"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary-500)"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.667 15.889h6.666c.306 0 .556-.25.556-.556v-2.777c0-.306.25-.556.555-.556.306 0 .556.25.556.556v3.333C17 16.5 16.5 17 15.889 17H8.11C7.5 17 7 16.5 7 15.889V8.11A1.11 1.11 0 018.111 7h3.333c.306 0 .556.25.556.556 0 .305-.25.555-.556.555H8.667a.557.557 0 00-.556.556v6.666c0 .306.25.556.556.556zm4.998-7.778a.557.557 0 01-.556-.555c0-.306.25-.556.556-.556h2.777c.306 0 .556.25.556.556v2.777c0 .306-.25.556-.556.556a.557.557 0 01-.555-.556V8.894l-5.072 5.073a.553.553 0 11-.784-.784l5.073-5.072h-1.44z" fill="currentColor"></path></svg></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="history_main_item">
                <p class="history_main_item_date">June 21, 2021</p>
                <div class="history_main_item_parent">
                    <div class="history_main_item_child">
                        <div class="history_main_item_child_img_info">
                            <div class="history_main_item_child_img">
                                <img src="../images/child_img7.svg" alt="">
                            </div>
                            <div class="history_main_item_child_infos">
                                <p class="history_main_item_child_info_title">Trade</p>
                                <p class="history_main_item_child_info">01:33 PM</p>
                            </div>
                        </div>

                        <div class="history_main_item_child3">
                            <div class="history_main_item_child_img_info_second_wrapper">
                                <div class="history_main_item_child_img_info">
                                    <div class="history_main_item_child_img2">
                                        <img src="../images/child_img8.png" alt="">
                                    </div>
                                    <div class="history_main_item_child_infos">
                                        <a href="" class="history_main_item_child_info_link">-309.333 UNI</a>
                                        <p class="history_main_item_child_info">₿0.17292</p>
                                    </div>
                                </div>
                                <div class="history_main_item_child_img_info_second_icon">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="tjw99t1"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path></svg>
                                </div>
                                <div class="history_main_item_child_img_info">
                                    <div class="history_main_item_child_img2">
                                        <img src="../images/child_img6.png" alt="">
                                    </div>
                                    <div class="history_main_item_child_infos">
                                        <a href="" class="history_main_item_child_info_link">+2.796 ETH</a>
                                        <p class="history_main_item_child_info">₿0.171019</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="history_main_item_child_hidden_parent">
                        <div class="history_main_item_child_hidden">
                            <div class="history_main_item_child_hidden_info_wrapper">
                                <p class="history_main_item_child_hidden_info_title">Fee</p>
                                <p class="history_main_item_child_hidden_info">0.039 ETH (₿0.002703)</p>
                            </div>
                            <div class="history_main_item_child_hidden_info_wrapper">
                                <p class="history_main_item_child_hidden_info_title">Rate</p>
                                <p class="history_main_item_child_hidden_info">1 UNI = 0.0091 ETH</p>
                            </div>
                            <div class="history_main_item_child_hidden_info_wrapper">
                                <p class="history_main_item_child_hidden_info_title">Transaction hash</p>
                                <div class="history_main_item_child_hidden_info_copy_btn_link_wrapper">
                                    <p class="history_main_item_child_hidden_info">0x4a1e...5faf</p>
                                    <div class="history_main_item_child_hidden_copy_btn_link_wrapper">
                                        <button class="history_main_item_child_hidden_copy_btn"><svg width="60%" height="60%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary-500)"><path d="M16 2a1 1 0 00-1-1H4c-1.1 0-2 .9-2 2v13a1 1 0 102 0V3h11a1 1 0 001-1zm3 3H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z" fill="currentColor"></path></svg></button>
                                        <a href="" class="history_main_item_child_hidden_info_link"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary-500)"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.667 15.889h6.666c.306 0 .556-.25.556-.556v-2.777c0-.306.25-.556.555-.556.306 0 .556.25.556.556v3.333C17 16.5 16.5 17 15.889 17H8.11C7.5 17 7 16.5 7 15.889V8.11A1.11 1.11 0 018.111 7h3.333c.306 0 .556.25.556.556 0 .305-.25.555-.556.555H8.667a.557.557 0 00-.556.556v6.666c0 .306.25.556.556.556zm4.998-7.778a.557.557 0 01-.556-.555c0-.306.25-.556.556-.556h2.777c.306 0 .556.25.556.556v2.777c0 .306-.25.556-.556.556a.557.557 0 01-.555-.556V8.894l-5.072 5.073a.553.553 0 11-.784-.784l5.073-5.072h-1.44z" fill="currentColor"></path></svg></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- <div class="history_main_item">
                <p class="history_main_item_date">June 21, 2021</p>
                <div class="history_main_item_parent">
                    <div class="history_main_item_child">
                        <div class="history_main_item_child_img_info">
                            <div class="history_main_item_child_img">
                                <img src="../images/child_img9.svg" alt="">
                            </div>
                            <div class="history_main_item_child_infos">
                                <p class="history_main_item_child_info_title">Approval</p>
                                <p class="history_main_item_child_info">01:18 PM</p>
                            </div>
                        </div>
                        <div class="history_main_item_child2">
                            <div class="history_main_item_child_img_info">
                                <div class="history_main_item_child_img2">
                                    <img src="../images/child_img8.png" alt="">
                                </div>
                                <div class="history_main_item_child_infos">
                                    <a href="" class="history_main_item_child_info_link">Uniswap</a>
                                </div>
                            </div>
                            <div class="history_main_item_child_info_wrapper">
                                <p class="history_main_item_child_info_title2">Application</p>
                                <div class="history_main_item_child_info_img_wrapper">
                                    <div class="history_main_item_child_info_img2">
                                        <img src="../images/child_img4.png" alt="">
                                    </div>
                                    <p class="history_main_item_child_info3">0xdef1...5eff</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="history_main_item_child_hidden_parent">
                        <div class="history_main_item_child_hidden">
                            <div class="history_main_item_child_hidden_info_wrapper">
                                <p class="history_main_item_child_hidden_info_title">Fee</p>
                                <p class="history_main_item_child_hidden_info">0.039 ETH (₿0.002703)</p>
                            </div>
                            <div class="history_main_item_child_hidden_info_wrapper">
                                <p class="history_main_item_child_hidden_info_title">Transaction hash</p>
                                <div class="history_main_item_child_hidden_info_copy_btn_link_wrapper">
                                    <p class="history_main_item_child_hidden_info">0x4a1e...5faf</p>
                                    <div class="history_main_item_child_hidden_copy_btn_link_wrapper">
                                        <button class="history_main_item_child_hidden_copy_btn"><svg width="60%" height="60%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary-500)"><path d="M16 2a1 1 0 00-1-1H4c-1.1 0-2 .9-2 2v13a1 1 0 102 0V3h11a1 1 0 001-1zm3 3H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z" fill="currentColor"></path></svg></button>
                                        <a href="" class="history_main_item_child_hidden_info_link"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary-500)"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.667 15.889h6.666c.306 0 .556-.25.556-.556v-2.777c0-.306.25-.556.555-.556.306 0 .556.25.556.556v3.333C17 16.5 16.5 17 15.889 17H8.11C7.5 17 7 16.5 7 15.889V8.11A1.11 1.11 0 018.111 7h3.333c.306 0 .556.25.556.556 0 .305-.25.555-.556.555H8.667a.557.557 0 00-.556.556v6.666c0 .306.25.556.556.556zm4.998-7.778a.557.557 0 01-.556-.555c0-.306.25-.556.556-.556h2.777c.306 0 .556.25.556.556v2.777c0 .306-.25.556-.556.556a.557.557 0 01-.555-.556V8.894l-5.072 5.073a.553.553 0 11-.784-.784l5.073-5.072h-1.44z" fill="currentColor"></path></svg></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>
        <div class="more_transactions_btn_wrapper">
            <button class="more_transactions_btn">More transactions</button>
        </div>
    </div>
    <div class="calculate_taxes_popup">
        <div class="calculate_taxes_popup_wrapper">
            <div class="calculate_taxes_popup_close_icon">
                <svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M19.6599 4.33995C19.2066 3.88668 18.4718 3.88668 18.0185 4.33995L11.9988 10.3596L5.9815 4.34227C5.52823 3.889 4.79334 3.889 4.34008 4.34227C3.88681 4.79554 3.88681 5.53043 4.34008 5.98369L10.3574 12.001L4.33995 18.0184C3.88668 18.4717 3.88668 19.2066 4.33995 19.6599C4.79322 20.1131 5.52811 20.1131 5.98138 19.6599L11.9988 13.6424L18.0186 19.6622C18.4719 20.1155 19.2068 20.1155 19.66 19.6622C20.1133 19.2089 20.1133 18.474 19.66 18.0208L13.6403 12.001L19.6599 5.98137C20.1132 5.5281 20.1132 4.79321 19.6599 4.33995Z" fill="currentColor" fill-rule="evenodd"></path></svg>
            </div>
            <h1 class="calculate_taxes_popup_title">Calculate Taxes</h1>
            <p class="calculate_taxes_popup_info1">Some countries require you to pay tax on your crypto earnings which includes capital gains from DeFi investments.*</p>
            <p class="calculate_taxes_popup_info2">We've partnered with TokenTax to simplify your DeFi tax reporting.</p>
            <p class="calculate_taxes_popup_info2">Download your transaction history and open TokenTax to get started.</p>
            <div class="calculate_taxes_popup_copy_items_wrapper">
                <div class="calculate_taxes_popup_copy_item_title_wrapper">
                    <p class="calculate_taxes_popup_copy_item_title">Use the code to get <span>10 % discount</span></p>
                    <div class="calculate_taxes_popup_copy_item_info_icon">
                        <p class="calculate_taxes_popup_copy_item_info">ZERION</p>
                        <div class="calculate_taxes_popup_copy_item_icon"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="CopyButton__copy-icon"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.778 1.855H4.11C3.5 1.855 3 2.32 3 2.886v7.217h1.111V2.886h6.667v-1.03zm1.666 2.062h-6.11c-.612 0-1.112.464-1.112 1.031v7.216c0 .567.5 1.031 1.111 1.031h6.111c.612 0 1.112-.464 1.112-1.03V4.947c0-.567-.5-1.03-1.111-1.03zm0 8.247h-6.11V4.948h6.11v7.216z" fill="currentColor"></path></svg></div>
                    </div>
                </div>
            </div>
            <div class="calculate_taxes_popup_more_info_link_wrapper">
                <p class="calculate_taxes_popup_more_info_title">More information</p>
                <a href="" class="calculate_taxes_popup_more_info_link">DeFi Taxes Explained</a>
            </div>
            <div class="calculate_taxes_popup_copy_download_btn_token_link_wrapper">
                <button class="calculate_taxes_popup_copy_download_btn">Download CSV</button>
                <a href="" class="calculate_taxes_popup_copy_token_link">Go to TokenTax</a>
            </div>
        </div>
    </div>
</section>
@endsection