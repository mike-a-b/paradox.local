@extends('layouts.index.app')
@section("content")
    <section>
        <main class="main">
            <div class="main__conteiner _conteiner">
                <div class="main__content content">
                    <div class="content__description">
                        <div class="description">
                            <h2>
                                {!! __("main__content_desc_h2") !!}
                            </h2>
                            <h3>
                                {!! __("main__content_desc_h3") !!}
                            </h3>
                        </div>
                    </div>
                    <div class="content__img child-2">
                        <img src="/front/img/content-img/traide-content.png" alt="">

                    </div>
                </div>
                <div class="main__content content grid-even">
                    <div class="content__description">
                        <div class="description">
                            <h2>
                                {!! __("main__content_1_desc_h2") !!}
                            </h2>
                            <h3>
                                {!! __("main__content_1_desc_h3") !!}
                            </h3>
                        </div>
                    </div>
                    <div class="content__img child-3">
                        <img src="/front/img/content-img/coins-content.png" alt="">

                    </div>
                </div>
                <div class="main__content content">
                    <div class="content__description">
                        <div class="description">
                            <h2>
                                {!! __("main__content_2_desc_h2") !!}
                            </h2>
                            <h3>
                                {!! __("main__content_2_desc_h3") !!}
                            </h3>
                        </div>
                    </div>
                    <div class="content__img child-4">
                        <img src="/front/img/content-img/traide-history.png" alt="">
                    </div>
                </div>
                <div class="main__content content grid-even">
                    <div class="content__description">
                        <div class="description__form">
                            <h2>
                                {!! __("main__content_description__form") !!}
                            </h2>
                            <form method="POST" action="{{route("auth.register")}}" class="form" id="register_form">
                                @csrf
                                @error('page_expired')
                                <div class="auth__row">
                                    <span class="invalid-feedback" style="display: block;"
                                          role="alert">{{ 'Page Session Expired. Try again' }}</span>
                                </div>
                                @enderror
                                <div class="group">
                                    <input type="text" name="name" id="register_name" value="{{ old('name') }}"
                                           required>
                                    <span class="bar"></span>
                                    <label> {!! __("main__content_name__form") !!}:</label>
                                    <img src="/front/img/icon/auth-icon-black.png" alt="Name">
                                </div>

                                <span class="invalid-feedback" role="alert" id="error_register_name"
                                     > @error('name'){{ $message }} @enderror</span>


                                <div class="group">
                                    <input type="email" name="email" id="register_email" value="{{ old('email') }}"
                                           required>
                                    <span class="bar"></span>
                                    <label> {!! __("main__content_email__form") !!}:</label>
                                    <img src="/front/img/icon/mail.png" alt="Mail">
                                </div>
                                <span class="invalid-feedback" role="alert"
                                      id="error_register_email">@error('email'){{ $message }} @enderror</span>

                                <div class="group">
                                    <input type="password" name="password" id="register_password" value="" required>
                                    <span class="bar"></span>
                                    <label> {!! __("main__content_password__form") !!}</label>
                                    <img src="/front/img/icon/password.png" alt="Password">
                                </div>
                                <span class="invalid-feedback" role="alert"
                                      id="error_register_password">@error('password'){{ $message }} @enderror</span>

                                <div class="group">
                                    <input type="password" name="password_confirmation"
                                           id="register_password_confirmation" value="" required>
                                    <span class="bar"></span>
                                    <label> {!! __("main__content_confirm_password__form") !!}</label>
                                    <img src="/front/img/icon/password.png" alt="Confirm password">
                                </div>
                                @error('password_confirmation')
                                <span class="invalid-feedback" role="alert"
                                      id="error_register_password_confirmation">{{ $message }}</span>
                                @enderror

                                <button type="button" class="auth__user"
                                        onClick="submitRegister()">{!! __("main__content_btn__form") !!}</button>
                            </form>
                        </div>
                    </div>
                    <div class="content__img child-5">
                        <img src="/front/img/content-img/Phone-auth.png" alt="">
                    </div>
                </div>
            </div>
        </main>
    </section>
@endsection

@section('script')
    <script>
        var register_form = document.getElementById("register_form");
        var register_name = document.getElementById("register_name");
        var register_email = document.getElementById("register_email");
        var register_password = document.getElementById("register_password");
        var register_password_confirmation = document.getElementById("register_password_confirmation");
        var error_register_name = document.getElementById("error_register_name");
        var error_register_email = document.getElementById("error_register_email");
        var error_register_password = document.getElementById("error_register_password");
        function getBaseHeaders() {
            return {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'credentials': 'same-origin',
                'mode': 'no-cors'
            };
        }
        function submitRegister() {
            let url = "/register/check";
            const formData = {
                name:register_name.value,
                email:register_email.value,
                password:register_password.value,
                password_confirmation:register_password_confirmation.value
            };
            fetch(url, {
                method: 'POST',
                headers: getBaseHeaders(),
                body: JSON.stringify(formData)
            }).then(res => res.json())
        .then(res => {
                error_register_name.innerHTML = "";
                error_register_email.innerHTML = "" ;
                error_register_password.innerHTML = "";
                if(res.validation){
                    register_form.submit();
                }else{
                for (const [key, value] of Object.entries(res.errors))
                    if(key=='name'){
                        error_register_name.innerHTML = value;
                    }else if(key=='email'){
                        error_register_email.innerHTML = value ;
                    }else if(key=='password'){
                        error_register_password.innerHTML = value;
                    }
                }
        });
        }
    </script>
@endsection

