@extends('layouts.auth')

@section('content')
<div class="auth__container__wrapper">
<div class="auth__container" id="login-form">

    <div class="auth__container-header">
        <img src="{{ asset('images/logo.png') }}" alt="" height="42">
    </div>

    <div class="auth__container-body">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @error('page_expired')
            <div class="auth__row">
                <span class="invalid-feedback" style="display: block;" role="alert">{{ 'Page Session Expired. Try again' }}</span>
            </div>
            @enderror

            <div class="auth__row">
                <input placeholder="{{ __('Email address') }}" id="email" type="email" class="auth__row-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="auth__row">
                <input placeholder="{{ __('Password') }}" id="password" type="password" class="auth__row-input @error('password') is-invalid @enderror" name="password" required>

                @error('password')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="auth__row-2col">
                <div class="rememberme__row">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="rememberme__row-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
                @if (Route::has('password.request'))
                <div class="forgotp__row">
                    <a class="forgotp__row-label" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </div>
                @endif
            </div>

            <div class="auth__row-control">
                <button type="submit" class="auth_button">
                    {{ __('Login') }}
                </button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
