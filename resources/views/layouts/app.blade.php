@inject('bladeService', 'App\Services\BladeService')

    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('history-links')
    <title>@yield('meta-title')</title>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <!-- <script src="{{asset('assets/js/public/jquery3.5.1.min.js')}}"></script> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <script src="{{asset('js/main.js')}}"></script> -->
    <script src="{{asset('assets/js/public/app.js')}}" defer></script>
</head>
<body id="body">
<div class="zerion_wrapper" id="@yield('zerion-wrapper-id')">
    <div class="general_menu_main_sections_wrapper">
        <div class="general_menu">
            <div class="general_menu_wrapper">
                <div class="logo_link_wrapper">
                    <a href="{{route('home')}}" class="logo_link">
                        <img src="{{asset('images/logo.svg')}}">
                    </a>
                </div>

                <a href="{{route('home')}}" class="general_menu_icon_info_link">
                    <div class="general_menu_link_icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 6.133c3.79 0 7.17 2.272 8.82 5.867-1.65 3.595-5.03 5.867-8.82 5.867S4.83 15.595 3.18 12C4.83 8.405 8.21 6.133 12 6.133zM12 4C7 4 2.73 7.317 1 12c1.73 4.683 6 8 11 8s9.27-3.317 11-8c-1.73-4.683-6-8-11-8zm0 5.333c1.38 0 2.5 1.195 2.5 2.667 0 1.472-1.12 2.667-2.5 2.667S9.5 13.472 9.5 12c0-1.472 1.12-2.667 2.5-2.667zM12 7.2c-2.48 0-4.5 2.155-4.5 4.8s2.02 4.8 4.5 4.8 4.5-2.155 4.5-4.8-2.02-4.8-4.5-4.8z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                    <p class="general_menu_link_info">{{ __('Overview') }}</p>
                </a>
                <a href="{{route('history')}}" class="general_menu_icon_info_link">
                    <div class="general_menu_link_icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.5 15l-3.355-.959A.2.2 0 0112 13.85V9" stroke="currentColor" stroke-width="1.6"
                                  stroke-linecap="round"></path>
                            <path
                                d="M4 12.5C4 7.808 7.808 4 12.5 4S21 7.808 21 12.5 17.192 21 12.5 21a8.465 8.465 0 01-5.487-2.01"
                                stroke="currentColor" stroke-width="1.6" stroke-linecap="round"></path>
                            <path d="M3.896 16.25l3.745-4.082A.1.1 0 007.567 12H.227a.1.1 0 00-.073.168l3.742 4.082z"
                                  fill="currentColor"></path>
                        </svg>
                    </div>
                    <p class="general_menu_link_info">{{ __('History') }}</p>
                </a>
                <a href="{{route('invest')}}" class="general_menu_icon_info_link">
                    <div class="general_menu_link_icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.833 5.667V17.25a2 2 0 002 2h15.334" stroke="currentColor" stroke-width="1.7"
                                  stroke-linecap="round"></path>
                            <path d="M3.833 18.417L9.5 11.999l3.208 2.292L17.5 8.708" stroke="currentColor"
                                  stroke-width="1.7" stroke-linejoin="round"></path>
                            <path
                                d="M18.272 11.765L14.61 8.779a.2.2 0 01.081-.35l4.04-.928a.2.2 0 01.244.214l-.377 3.914a.2.2 0 01-.326.136z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                    <p class="general_menu_link_info">{{ __('Invest') }}</p>
                </a>
                <a href="{{route('settings')}}" class="general_menu_icon_info_link">
                    <div class="general_menu_link_icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18.573 13.128a1.093 1.093 0 01-.405-.917 5.424 5.424 0 000-.392c-.012-.37.127-.74.422-.963l1.258-.956a.405.405 0 00.103-.51l-1.646-2.77c-.103-.18-.319-.24-.504-.18l-1.532.599c-.32.125-.68.071-.972-.11a6.005 6.005 0 00-.405-.23c-.318-.164-.558-.457-.61-.811l-.225-1.548a.407.407 0 00-.411-.34h-3.292c-.205 0-.37.14-.4.34l-.226 1.548c-.052.354-.292.648-.61.812-.138.073-.274.15-.406.232-.292.18-.65.232-.969.108l-1.534-.6a.412.412 0 00-.504.18L4.059 9.39a.38.38 0 00.103.51l1.259.956c.295.224.43.592.412.962a3.642 3.642 0 00-.001.363c.013.37-.127.74-.422.963l-1.258.956a.405.405 0 00-.103.51l1.646 2.77c.103.18.319.24.504.18l1.532-.599c.32-.125.68-.071.972.11.132.081.267.158.405.23.318.164.558.457.61.811l.225 1.548c.041.2.206.34.411.34h3.292c.205 0 .38-.14.4-.34l.226-1.548c.052-.354.292-.648.61-.812.138-.073.274-.15.406-.232.292-.18.65-.232.969-.108l1.534.6c.185.07.401 0 .504-.18l1.646-2.77a.38.38 0 00-.103-.51l-1.265-.972zM12 15c-1.697 0-3.086-1.35-3.086-3S10.303 9 12 9c1.697 0 3.086 1.35 3.086 3S13.697 15 12 15z"
                                stroke="currentColor" stroke-width="1.7"></path>
                        </svg>
                    </div>
                    <p class="general_menu_link_info">{{ __('Settings') }}</p>
                </a>
            </div>
        </div>
        <div class="main_sections_wrapper_parent">
            <div class="main_sections_wrapper">
                <header class="header">
                    <div class="header_wrapper">
                        <div class="header_form_wrapper">
                            <!-- <form action="" class="header_form">
                                <div class="header_form_input">
                                    <input type="text" class="header_form_input_field" placeholder="Search for tokens, pools, and vaults">
                                </div>
                                <button class="header_form_input_btn">F</button>
                            </form> -->
                            <div class="header_informer">
                                @foreach ($headerInfo->cryptocurrency_info as $cryptocurrency)
                                    <div class="informer_currency">
                                        <span class="informer_currency-symbol">{{ $cryptocurrency['symbol'] }}:</span>
                                        <span class="informer_currency-price">${{ $cryptocurrency['new_price'] }}</span>
                                        <span
                                            class="{{ $cryptocurrency['direction'] == 'down' ? 'informer_currency-minus' : 'informer_currency-plus' }}">
                                            {!! $cryptocurrency['direction'] == 'down' ? '&ndash;' : '+' !!}{{ $cryptocurrency['fraction'] }}%
                                        </span>
                                    </div>
                                @endforeach
                                <div class="informer_marketcup">
                                    <span class="informer_currency-symbol">Market Cap:</span>
                                    <span class="informer_currency-price">${{ $headerInfo->marketcup_total }} </span>
                                </div>
                              <!--   <div class="informer_currency">
                                    <span class="informer_currency-symbol">{{__('Balance')}}</span>
                                    <span id="informerCurrency" class="informer_currency-price"> ${!! $userBalance_usd !!} : ${!! $userBalance_usdt !!} </span>
                                </div> -->
								<div class="informer_currency balance">
                                    <!-- <span class="informer_currency-symbol">{{__('Balance')}}</span> -->
									 <span class="informer_currency-symbol"><img src="/images/briefcase.svg" alt="Текущий Инвест портфель" title="Текущий Инвест портфель"></span>
                                    <span id="informerCurrencyUsd" class="informer_currency-price balance"> ${!! $userBalance_usd !!} </span>
                                        <span class="informer_currency-symbol"><img src="/images/dollar.svg" alt="Ваш доступный баланс" title="Ваш доступный баланс"></span>
										<span id="informerCurrencyUsdt" class="informer_currency-price balance"> {!! $userBalance_usdt !!} </span> USDT
                                </div>
                            </div>
                        </div>
                        <div class="header_nested_menus_wrapper">
                         <!--    <div style="padding-right: 20px;">
                                <select class="header_lang_select">
                                    <option {{ $currentLocale === 'en' ? 'selected' : '' }} value="en">EN</option>
                                    <option {{ $currentLocale === 'ru' ? 'selected' : '' }} value="ru">RU</option>
                                    <option {{ $currentLocale === 'es' ? 'selected' : '' }} value="es">ES</option>
                                </select>
                            </div> -->
							       <div class="selected__languages">
                                            @foreach(__("langs") as $lang)
                            @if($currentLocale == $lang["symbol"])
                        <div class="selected__language flag__language" data-languages="{{$lang["symbol"]}}">
                            <img src="{{$lang["flag"]}}" alt="{{$lang["name"]}}" class="flag__language">
                            <p>{{$lang["name"]}}</p>
                            <img src="/front/img/arrows.png" alt="" class="arrows">
                        </div>
                            @endif
                        @endforeach

                        <ul class="all__languages">
                            @foreach(__("langs") as $lang)
                            <li data-languages="{{$lang["symbol"]}}">
                                <img src="{{$lang["flag"]}}" alt="{{$lang["name"]}}" class="flag__language">
                                <p>{{$lang["name"]}}</p>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                            <div class="general_menu_icon_info_main">
                                <div class="general_menu_icon_info_main_wrapper">
                                    <!-- Authentication Links -->
                                    @guest
                                        <div class="general_menu_img_info">
                                            <div class="general_menu_info_wrapper">
                                                @if (Route::has('login'))
                                                    <p class="general_menu_title"><a
                                                            href="{{ route('login') }}">{{ __('Login') }}</a></p>
                                                @endif
                                                @if (Route::has('register'))
                                                    <p class="general_menu_title"><a
                                                            href="{{ route('register') }}">{{ __('Register') }}</a></p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="general_menu_img_info">
                                            <div class="general_menu_img">
                                                <img
                                                    src="{{ isset($authUser->profile) && !empty($authUser->profile->ava) ? '/'.$authUser->profile->ava : '/assets/imgs/cabinet/ava-default.png' }}"
                                                    alt="">
                                            </div>
                                            <div class="general_menu_info_wrapper">
                                                <p class="general_menu_title">{{ $authUser->name ?? 'UserName' }}</p>
{{--                                                <p class="general_menu_info1">--}}
{{--                                                    ${!! isset($authUser->profile) ? $bladeService->printPriceBig($dengi ?? $authUser->profile->balance_sum_usd) : 0 !!}--}}
{{--                                                </p>--}}
                                            </div>
                                        </div>
                                        <div class="general_menu_icon_main">
                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                 direction="down" class="WalletMenu__Angle-sc-17xicac-0 iwIpxX"
                                                 style="width: 24px; height: 24px;">
                                                <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2"
                                                      stroke-linecap="round"></path>
                                            </svg>
                                        </div>
                                    @endguest

                                </div>
                                <div class="general_menu_nested_list">
                                    @if (false)
                                        <!-- <p class="general_menu_nested_list_title">Watchlist</p>
                                        <div class="general_menu_nested_list_img_info_icon_wrapper">
                                            <div class="general_menu_nested_list_img_info_wrapper">
                                                <div class="general_menu_nested_list_img">
                                                    <img src="{{asset('images/menu_img1.png')}}" alt="">
                                                </div>
                                                <div class="general_menu_nested_list_info">
                                                    <p class="general_menu_nested_list_info1">demo.zerion.eth</p>
                                                    <p class="general_menu_nested_list_info2">Watch address</p>
                                                </div>
                                            </div>
                                            <div class=general_menu_nested_list_icon>
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="WalletListItem__Check-sc-3sg5u1-0 cWizIW"><path d="M9.714 18L4.7 12.946a1 1 0 010-1.409l.204-.205a1 1 0 011.418 0l3.393 3.409 7.962-8.023a1 1 0 011.422.002l.208.211a1 1 0 01-.003 1.406L9.714 18z" fill="currentColor"></path></svg>
                                            </div>
                                        </div> -->
                                        <!-- <div class="general_menu_nested_list_links_wrapper">
                                            <a href="" class="general_menu_nested_list_link">
                                                <div class="general_menu_nested_list_link_icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3.85A1.15 1.15 0 0010.85 5v5.85H5a1.15 1.15 0 000 2.3h5.85V19a1.15 1.15 0 002.3 0v-5.85H19a1.15 1.15 0 000-2.3h-5.85V5A1.15 1.15 0 0012 3.85z" fill="currentColor"></path></svg>
                                                </div>
                                                <p class="general_menu_nested_list_link_info">Connect another wallet</p>
                                            </a>
                                            <a href="" class="general_menu_nested_list_link">
                                                <div class="general_menu_nested_list_link_icon">
                                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.105 13.79v.888c0 .978-.805 1.778-1.79 1.778H1.79c-.993 0-1.789-.8-1.789-1.778V2.234C0 1.256.796.456 1.79.456h12.526c.984 0 1.79.8 1.79 1.778v.889H8.052c-.994 0-1.79.8-1.79 1.778v7.11c0 .979.796 1.779 1.79 1.779h8.052zm-8.052-1.778H17V4.9H8.053v7.11zm3.579-2.222a1.336 1.336 0 01-1.342-1.334c0-.738.599-1.333 1.342-1.333.742 0 1.342.595 1.342 1.333s-.6 1.334-1.342 1.334z" fill="currentColor"></path></svg>
                                                </div>
                                                <p class="general_menu_nested_list_link_info">Manage Wallets</p>
                                            </a>
                                        </div> -->
                                    @endif
                                    <div class="general_menu_nested_list_links_wrapper">
                                        <a href="{{ route('settings') }}" class="general_menu_nested_list_link">
                                            <div class="general_menu_nested_list_link_icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12 3.85A1.15 1.15 0 0010.85 5v5.85H5a1.15 1.15 0 000 2.3h5.85V19a1.15 1.15 0 002.3 0v-5.85H19a1.15 1.15 0 000-2.3h-5.85V5A1.15 1.15 0 0012 3.85z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </div>
                                            <p class="general_menu_nested_list_link_info">{{ __('Settings') }}</p>
                                        </a>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                           class="general_menu_nested_list_link">
                                            <div class="general_menu_nested_list_link_icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12 3.85A1.15 1.15 0 0010.85 5v5.85H5a1.15 1.15 0 000 2.3h5.85V19a1.15 1.15 0 002.3 0v-5.85H19a1.15 1.15 0 000-2.3h-5.85V5A1.15 1.15 0 0012 3.85z"
                                                        fill="currentColor"></path>
                                                </svg>
                                            </div>
                                            <p class="general_menu_nested_list_link_info">{{ __('Logout') }}</p>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @if (false)
                                <!--<div class="connect_to_zerion_nested_menu">-->
                                <!--    <button class="connect_to_zerion_nested_menu_btn">-->
                                <!--      <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.334 3.333h13.333c.92 0 1.667.746 1.667 1.667v10c0 .92-.747 1.667-1.667 1.667H3.334c-.92 0-1.667-.747-1.667-1.667V5c0-.92.746-1.667 1.667-1.667zM16.667 7.5V5H3.334v2.5h13.333zM3.334 15v-5h13.333v5H3.334zm2.083-3.333h2.5c.23 0 .417.186.417.416v.834c0 .23-.187.416-.417.416h-2.5A.417.417 0 015 12.917v-.834c0-.23.187-.416.417-.416z" fill="currentColor"></path></svg>-->
                                <!--    </button>-->
                                <!--</div>-->
                                <!--<div class="view_hide_btn_wrapper">-->
                                <!--   <button class="view_hide_btn">-->
                                <!--       <div class="view_icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 7.5A2.497 2.497 0 007.5 10c0 1.383 1.117 2.5 2.5 2.5s2.5-1.117 2.5-2.5-1.117-2.5-2.5-2.5z" fill="currentColor"></path><path d="M10 15.45c-3.713 0-6.904-2.242-8.3-5.45C3.095 6.792 6.286 4.55 10 4.55c3.714 0 6.904 2.242 8.301 5.45-1.396 3.208-4.587 5.45-8.3 5.45z" stroke="currentColor" stroke-width="1.6"></path></svg></div>-->
                                <!--       <div class="hide_icon"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.986 7.672a2.497 2.497 0 00-1.51 2.297c0 1.383 1.117 2.5 2.502 2.5a2.5 2.5 0 002.3-1.51L8.986 7.672z" fill="currentColor"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M2.761 1.453a.5.5 0 00-.706 0l-.426.425a.5.5 0 000 .707L4.457 5.41A9.91 9.91 0 00.8 9.97c1.443 3.658 5.006 6.25 9.178 6.25a9.813 9.813 0 004.31-.99l3.626 3.62a.5.5 0 00.706 0l.426-.425a.5.5 0 000-.707L2.76 1.453zm10.315 12.564L5.615 6.565c-1.315.82-2.382 2-3.067 3.404a8.262 8.262 0 0010.528 4.048z" fill="currentColor"></path><path d="M17.407 9.969a8.298 8.298 0 01-1.606 2.245l1.132 1.131a9.89 9.89 0 002.223-3.376c-1.443-3.659-5.006-6.25-9.178-6.25-.825 0-1.626.1-2.391.292l1.372 1.37a8.262 8.262 0 018.448 4.588z" fill="currentColor"></path></svg></div>-->
                                <!--   </button>-->

                                <!--</div>-->
                            @endif
                        </div>
                    </div>
                    <div class="header_mobile">
                        <div class="header_mobile_wrapper">

                            <div class="hamburger-menu">
                                <button class="hamburger-menu-btn">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 24h24v-2.667H4V24zm0-6.667h24v-2.666H4v2.666zM4 8v2.667h24V8H4z"
                                              fill="currentColor"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="mobile_logo_link_wrapper">
                                <a href="{{ route('home') }}" class="logo_link">
                                    <img src="{{asset('images/logo.svg')}}">
                                </a>
                            </div>
                            <div class="mobile_search_form_btn_wallet_hidden_box_wrapper">
                                @if (false)
                                    <!-- <button class="mobile_search_form_btn"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><circle cx="10" cy="10" r="6" stroke="currentColor" stroke-width="2"></circle><path d="M14.5 14.5l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button> -->
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mobile_version1">
                        <div class="mobile_version1_wrapper">
                            <div class="mobile_version1_close">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                     style="width: 24px;">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M19.66 4.34a1.16 1.16 0 00-1.642 0l-6.02 6.02-6.016-6.017A1.16 1.16 0 104.34 5.984l6.017 6.018-6.017 6.017a1.16 1.16 0 001.641 1.642L12 13.643l6.02 6.02a1.16 1.16 0 001.641-1.642l-6.02-6.02 6.02-6.019a1.16 1.16 0 000-1.641z"
                                          fill="currentColor"></path>
                                </svg>
                            </div>
                            <div class="general_menu_wrapper">
                                <div class="general_menu_icon_info_main">
                                    <div class="general_menu_icon_info_main_wrapper">
                                        <div class="general_menu_img_info">
                                            <div class="general_menu_img">
                                                <img
                                                    src="{{ isset($authUser->profile) && !empty($authUser->profile->ava) ? '/'.$authUser->profile->ava : '/assets/imgs/cabinet/ava-default.png' }}"
                                                    alt="">
                                            </div>
                                            <div class="general_menu_info_wrapper">
                                                <p class="general_menu_title">{{ $authUser->name ?? 'UserName' }}</p>
{{--                                                <p class="general_menu_info1">--}}
{{--                                                    ${!! isset($authUser->profile) ? $bladeService->printPriceBig($dengi ?? $authUser->profile->balance_sum_usd) : 0 !!}</p>--}}
                                            </div>
                                        </div>
                                        <div class="general_menu_langs_mob">
                                            <a href="#" data-lng="en"
                                               class="{{ $currentLocale == 'en' ? 'active' : '' }}">EN</a>
                                            <a href="#" data-lng="ru"
                                               class="{{ $currentLocale == 'ru' ? 'active' : '' }}">RU</a>
                                            <a href="#" data-lng="es"
                                               class="{{ $currentLocale == 'es' ? 'active' : '' }}">ES</a>
                                        </div>
                                        <!-- <div class="general_menu_icon_main">
                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" direction="down" class="WalletMenu__Angle-sc-17xicac-0 iwIpxX" style="width: 24px; height: 24px;"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path></svg>
                                        </div> -->
                                    </div>
                                    @if (false)
                                        <!-- <div class="general_menu_nested_list">
                                            <p class="general_menu_nested_list_title">Watchlist</p>
                                            <div class="general_menu_nested_list_img_info_icon_wrapper">
                                                <div class="general_menu_nested_list_img_info_wrapper">
                                                    <div class="general_menu_nested_list_img">
                                                        <img src="{{asset('images/menu_img1.png')}}" alt="">
                                                    </div>
                                                    <div class="general_menu_nested_list_info">
                                                        <p class="general_menu_nested_list_info1">{{ $authUser->name }}</p>
                                                        <p class="general_menu_nested_list_info2">${!! isset($authUser->profile) ? $bladeService->printPriceBig($authUser->profile->balance_usd) : 0 !!}</p>
                                                    </div>
                                                </div>
                                                <div class=general_menu_nested_list_icon>
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="WalletListItem__Check-sc-3sg5u1-0 cWizIW"><path d="M9.714 18L4.7 12.946a1 1 0 010-1.409l.204-.205a1 1 0 011.418 0l3.393 3.409 7.962-8.023a1 1 0 011.422.002l.208.211a1 1 0 01-.003 1.406L9.714 18z" fill="currentColor"></path></svg>
                                                </div>
                                            </div>
                                            <div class="general_menu_nested_list_links_wrapper">
                                                <a href="" class="general_menu_nested_list_link">
                                                    <div class="general_menu_nested_list_link_icon">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3.85A1.15 1.15 0 0010.85 5v5.85H5a1.15 1.15 0 000 2.3h5.85V19a1.15 1.15 0 002.3 0v-5.85H19a1.15 1.15 0 000-2.3h-5.85V5A1.15 1.15 0 0012 3.85z" fill="currentColor"></path></svg>
                                                    </div>
                                                    <p class="general_menu_nested_list_link_info">Connect another wallet</p>
                                                </a>
                                                <a href="" class="general_menu_nested_list_link">
                                                    <div class="general_menu_nested_list_link_icon">
                                                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.105 13.79v.888c0 .978-.805 1.778-1.79 1.778H1.79c-.993 0-1.789-.8-1.789-1.778V2.234C0 1.256.796.456 1.79.456h12.526c.984 0 1.79.8 1.79 1.778v.889H8.052c-.994 0-1.79.8-1.79 1.778v7.11c0 .979.796 1.779 1.79 1.779h8.052zm-8.052-1.778H17V4.9H8.053v7.11zm3.579-2.222a1.336 1.336 0 01-1.342-1.334c0-.738.599-1.333 1.342-1.333.742 0 1.342.595 1.342 1.333s-.6 1.334-1.342 1.334z" fill="currentColor"></path></svg>
                                                    </div>
                                                    <p class="general_menu_nested_list_link_info">Manage Wallets</p>
                                                </a>
                                            </div>
                                        </div> -->
                                    @endif
                                </div>
                                <a href="{{route('home')}}" class="general_menu_icon_info_link">
                                    <div class="general_menu_link_icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 6.133c3.79 0 7.17 2.272 8.82 5.867-1.65 3.595-5.03 5.867-8.82 5.867S4.83 15.595 3.18 12C4.83 8.405 8.21 6.133 12 6.133zM12 4C7 4 2.73 7.317 1 12c1.73 4.683 6 8 11 8s9.27-3.317 11-8c-1.73-4.683-6-8-11-8zm0 5.333c1.38 0 2.5 1.195 2.5 2.667 0 1.472-1.12 2.667-2.5 2.667S9.5 13.472 9.5 12c0-1.472 1.12-2.667 2.5-2.667zM12 7.2c-2.48 0-4.5 2.155-4.5 4.8s2.02 4.8 4.5 4.8 4.5-2.155 4.5-4.8-2.02-4.8-4.5-4.8z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </div>
                                    <p class="general_menu_link_info">{{ __('Overview') }}</p>
                                </a>
                                <a href="{{route('history')}}" class="general_menu_icon_info_link">
                                    <div class="general_menu_link_icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.5 15l-3.355-.959A.2.2 0 0112 13.85V9" stroke="currentColor"
                                                  stroke-width="1.6" stroke-linecap="round"></path>
                                            <path
                                                d="M4 12.5C4 7.808 7.808 4 12.5 4S21 7.808 21 12.5 17.192 21 12.5 21a8.465 8.465 0 01-5.487-2.01"
                                                stroke="currentColor" stroke-width="1.6" stroke-linecap="round"></path>
                                            <path
                                                d="M3.896 16.25l3.745-4.082A.1.1 0 007.567 12H.227a.1.1 0 00-.073.168l3.742 4.082z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </div>
                                    <p class="general_menu_link_info">{{ __('History') }}</p>
                                </a>
                                <a href="{{route('invest')}}" class="general_menu_icon_info_link">
                                    <div class="general_menu_link_icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.833 5.667V17.25a2 2 0 002 2h15.334" stroke="currentColor"
                                                  stroke-width="1.7" stroke-linecap="round"></path>
                                            <path d="M3.833 18.417L9.5 11.999l3.208 2.292L17.5 8.708"
                                                  stroke="currentColor" stroke-width="1.7"
                                                  stroke-linejoin="round"></path>
                                            <path
                                                d="M18.272 11.765L14.61 8.779a.2.2 0 01.081-.35l4.04-.928a.2.2 0 01.244.214l-.377 3.914a.2.2 0 01-.326.136z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </div>
                                    <p class="general_menu_link_info">{{ __('Invest') }}</p>
                                </a>
                                <a href="{{route('settings')}}" class="general_menu_icon_info_link">
                                    <div class="general_menu_link_icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M18.573 13.128a1.093 1.093 0 01-.405-.917 5.424 5.424 0 000-.392c-.012-.37.127-.74.422-.963l1.258-.956a.405.405 0 00.103-.51l-1.646-2.77c-.103-.18-.319-.24-.504-.18l-1.532.599c-.32.125-.68.071-.972-.11a6.005 6.005 0 00-.405-.23c-.318-.164-.558-.457-.61-.811l-.225-1.548a.407.407 0 00-.411-.34h-3.292c-.205 0-.37.14-.4.34l-.226 1.548c-.052.354-.292.648-.61.812-.138.073-.274.15-.406.232-.292.18-.65.232-.969.108l-1.534-.6a.412.412 0 00-.504.18L4.059 9.39a.38.38 0 00.103.51l1.259.956c.295.224.43.592.412.962a3.642 3.642 0 00-.001.363c.013.37-.127.74-.422.963l-1.258.956a.405.405 0 00-.103.51l1.646 2.77c.103.18.319.24.504.18l1.532-.599c.32-.125.68-.071.972.11.132.081.267.158.405.23.318.164.558.457.61.811l.225 1.548c.041.2.206.34.411.34h3.292c.205 0 .38-.14.4-.34l.226-1.548c.052-.354.292-.648.61-.812.138-.073.274-.15.406-.232.292-.18.65-.232.969-.108l1.534.6c.185.07.401 0 .504-.18l1.646-2.77a.38.38 0 00-.103-.51l-1.265-.972zM12 15c-1.697 0-3.086-1.35-3.086-3S10.303 9 12 9c1.697 0 3.086 1.35 3.086 3S13.697 15 12 15z"
                                                stroke="currentColor" stroke-width="1.7"></path>
                                        </svg>
                                    </div>
                                    <p class="general_menu_link_info">{{ __('Settings') }}</p>
                                </a>
                                <a href="{{route('logout')}}"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                   class="general_menu_icon_info_link">
                                    <div class="general_menu_link_icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M18.573 13.128a1.093 1.093 0 01-.405-.917 5.424 5.424 0 000-.392c-.012-.37.127-.74.422-.963l1.258-.956a.405.405 0 00.103-.51l-1.646-2.77c-.103-.18-.319-.24-.504-.18l-1.532.599c-.32.125-.68.071-.972-.11a6.005 6.005 0 00-.405-.23c-.318-.164-.558-.457-.61-.811l-.225-1.548a.407.407 0 00-.411-.34h-3.292c-.205 0-.37.14-.4.34l-.226 1.548c-.052.354-.292.648-.61.812-.138.073-.274.15-.406.232-.292.18-.65.232-.969.108l-1.534-.6a.412.412 0 00-.504.18L4.059 9.39a.38.38 0 00.103.51l1.259.956c.295.224.43.592.412.962a3.642 3.642 0 00-.001.363c.013.37-.127.74-.422.963l-1.258.956a.405.405 0 00-.103.51l1.646 2.77c.103.18.319.24.504.18l1.532-.599c.32-.125.68-.071.972.11.132.081.267.158.405.23.318.164.558.457.61.811l.225 1.548c.041.2.206.34.411.34h3.292c.205 0 .38-.14.4-.34l.226-1.548c.052-.354.292-.648.61-.812.138-.073.274-.15.406-.232.292-.18.65-.232.969-.108l1.534.6c.185.07.401 0 .504-.18l1.646-2.77a.38.38 0 00-.103-.51l-1.265-.972zM12 15c-1.697 0-3.086-1.35-3.086-3S10.303 9 12 9c1.697 0 3.086 1.35 3.086 3S13.697 15 12 15z"
                                                stroke="currentColor" stroke-width="1.7"></path>
                                        </svg>
                                    </div>
                                    <p class="general_menu_link_info">{{ __('Logout') }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    @if (false)
                        <!-- <div class="mobile_version2">
                            <div class="mobile_version2_wrapper">
                                <form action="" class="mobile_search_header_form">
                                    <div class="mobile_search_header_form_input_icon_wrapper">
                                        <div class="mobile_search_header_form_input_icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;"><circle cx="10" cy="10" r="6" stroke="currentColor" stroke-width="2"></circle><path d="M14.5 14.5l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></div>
                                        <div class="mobile_search_header_form_input">
                                            <input type="text" class="mobile_search_header_form_input_field" placeholder="Search for tokens, pools, and vaults">
                                        </div>
                                    </div>
                                    <button class="mobile_search_header_form_btn" type="button">Cancel</button>

                                </form>
                                <div class="mobile_search_header_form_items_wrapper">
                                    <p class="mobile_search_header_form_item"></p>
                                </div>
                            </div>
                        </div> -->
                    @endif
                    @if (false)
                        <!-- <div class="mobile_version3">
                            <div class="mobile_version3_wrapper">
                                <div class="mobile_version3_close"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px;"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.66 4.34a1.16 1.16 0 00-1.642 0l-6.02 6.02-6.016-6.017A1.16 1.16 0 104.34 5.984l6.017 6.018-6.017 6.017a1.16 1.16 0 001.641 1.642L12 13.643l6.02 6.02a1.16 1.16 0 001.641-1.642l-6.02-6.02 6.02-6.019a1.16 1.16 0 000-1.641z" fill="currentColor"></path></svg></div>
                                <div class="mobile_general_menu_nested_list">
                                    <p class="general_menu_nested_list_title">Watchlist</p>
                                    <div class="general_menu_nested_list_img_info_icon_wrapper">
                                        <div class="general_menu_nested_list_img_info_wrapper">
                                            <div class="general_menu_nested_list_img">
                                                <img src="{{asset('images/menu_img1.png')}}" alt="">
                                            </div>
                                            <div class="general_menu_nested_list_info">
                                                <p class="general_menu_nested_list_info1">demo.zerion.eth</p>
                                                <p class="general_menu_nested_list_info2">Watch address</p>
                                            </div>
                                        </div>
                                        <div class=general_menu_nested_list_icon>
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="WalletListItem__Check-sc-3sg5u1-0 cWizIW"><path d="M9.714 18L4.7 12.946a1 1 0 010-1.409l.204-.205a1 1 0 011.418 0l3.393 3.409 7.962-8.023a1 1 0 011.422.002l.208.211a1 1 0 01-.003 1.406L9.714 18z" fill="currentColor"></path></svg>
                                        </div>
                                    </div>
                                    <div class="general_menu_nested_list_links_wrapper">
                                        <a href="" class="general_menu_nested_list_link">
                                            <div class="general_menu_nested_list_link_icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3.85A1.15 1.15 0 0010.85 5v5.85H5a1.15 1.15 0 000 2.3h5.85V19a1.15 1.15 0 002.3 0v-5.85H19a1.15 1.15 0 000-2.3h-5.85V5A1.15 1.15 0 0012 3.85z" fill="currentColor"></path></svg>
                                            </div>
                                            <p class="general_menu_nested_list_link_info">Connect another wallet</p>
                                        </a>
                                        <a href="" class="general_menu_nested_list_link">
                                            <div class="general_menu_nested_list_link_icon">
                                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16.105 13.79v.888c0 .978-.805 1.778-1.79 1.778H1.79c-.993 0-1.789-.8-1.789-1.778V2.234C0 1.256.796.456 1.79.456h12.526c.984 0 1.79.8 1.79 1.778v.889H8.052c-.994 0-1.79.8-1.79 1.778v7.11c0 .979.796 1.779 1.79 1.779h8.052zm-8.052-1.778H17V4.9H8.053v7.11zm3.579-2.222a1.336 1.336 0 01-1.342-1.334c0-.738.599-1.333 1.342-1.333.742 0 1.342.595 1.342 1.333s-.6 1.334-1.342 1.334z" fill="currentColor"></path></svg>
                                            </div>
                                            <p class="general_menu_nested_list_link_info">Manage Wallets</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    @endif
                </header>
                @yield('content')

                <script>
                    setInterval(function() {
                        var xmlHttp = new XMLHttpRequest();
                        xmlHttp.open( "GET", "/getBalance", false );
                        xmlHttp.send( null );
                        let data = JSON.parse(xmlHttp.responseText)
                        document.getElementById("informerCurrencyUsd").textContent = data.usd
                        document.getElementById("informerCurrencyUsdt").textContent = data.usdt
                    }, 5000)
                </script>
            </div>
        </div>
    </div>

</div>
</body>
</html>
