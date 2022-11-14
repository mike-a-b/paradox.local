@extends('layouts.auth')

@section('content')
    <div class="auth__container__wrapper">
        <div class="auth__container" id="login-form">
            <div class="auth__container-header">
                <img src="{{ asset('images/logo.png') }}" alt="" height="42">
            </div>
            <div class="auth__container-body">
                <form method="POST" action="{{route("auth.register")}}">
                    @csrf
                    @error('page_expired')
                    <div class="auth__row">
                        <span class="invalid-feedback" style="display: block;" role="alert">{{ 'Page Session Expired. Try again' }}</span>
                    </div>
                    @enderror
                    <div class="auth__row">
                        <input placeholder="{{ __('register__form_name') }}" id="name" type="text" class="auth__row-input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                        @error('name')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="auth__row">
                        <input placeholder="{{ __('register__form_email') }}" id="email" type="email" class="auth__row-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="auth__row">
                        <input placeholder="{{ __('register__form_password') }}" id="password" type="password" class="auth__row-input @error('password') is-invalid @enderror" name="password" value="" required autofocus>

                        @error('password')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="auth__row">
                        <input placeholder="{{ __('register__form_confirm_password') }}" id="password_confirmation" type="password" class="auth__row-input @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="" required autofocus>

                        @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="auth__row-control">
                        <button type="submit" class="auth_button">
                            {{ __('register__form_btn') }}
                        </button>
                    </div>
                </form>
            </div>
    </div>
</div>
@endsection
