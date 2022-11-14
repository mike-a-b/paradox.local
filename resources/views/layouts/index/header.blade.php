<section>
    <header class='header'>
        <div class='header__conteiner _conteiner '>
            <div class="header__menu menu">
                <div class="header__logo">
                    <a href="{{route("index")}}">
                        <img src="/front/img/logo.png" alt="Paradoxx" class="menu__logo-img">
                    </a>
                </div>
                <div class="header__exchange__rates">
                    @foreach ($headerInfo->cryptocurrency_info as $cryptocurrency)
                        <div class="exchange__rate bts {{ $cryptocurrency['direction'] == 'down' ? 'down' : 'informer_currency-up' }}">
                            <p><span>{{ $cryptocurrency['symbol'] }}:</span>${{ $cryptocurrency['new_price'] }}</p>
                            <div class="rate__per__day">
                                <p>
                                    {!! $cryptocurrency['direction'] == 'down' ? '&ndash;' : '+' !!}{{ $cryptocurrency['fraction'] }}
                                    %
                                </p>
                            </div>
                        </div>
                    @endforeach
                    <div class="exchange__rate eth up">
                        <p><span>Market Cap:</span>${{ $headerInfo->marketcup_total }} b</p>
                    </div>
                </div>
                <div class="header__user">
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
                    @if(auth()->user())
                        <a href="{{route("home")}}" class="auth__link">
                            <div class="auth__user_top">
                                <img src="/front/img/icon/auth-icon.png" alt="Account">
                                {{__("account")}}
                            </div>
							<div class="auth__user mob">
                                <img src="/front/img/icon/auth-icon.png" alt="Account">
                               
                            </div>
                        </a>
                    @else
                        <a href="{{route("home")}}" class="auth__link">
                            <div class="auth__user_top">
                                <img src="/front/img/icon/auth-icon.png" alt="Account">
                                {{__("login")}}
                            </div>
							<div class="auth__user mob">
                                <img src="/front/img/icon/auth-icon.png" alt="Account">
                               
                            </div>
                        </a>
                    @endif
                </div>
            </div>
            <div class="header__content content">
                <div class="content__description">
                    <div class="description">
                        {!! __("content__description") !!}
                    </div>
                    <div class="btn__content">
                        <a href="{{route("auth.register")}}" class="auth__user">{{__("btn_auth__user")}}</a>
                        <a href="#" class="view__demo">{{__("btn_view_demo")}}</a>
                    </div>
                </div>
                <div class="content__img child-1">
                    <img src="/front/img/content-img/Phone-content.png" alt="">
                </div>
            </div>
        </div>
    </header>
</section>