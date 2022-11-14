@extends('layouts.auth')

@section('content')
<div class="auth__container__wrapper">
<div class="auth__container" id="login-form">
    
    <div class="auth__container-header">
        <img src="/images/logo.svg" alt="" height="42">
    </div>
    <div class="auth__container-title">
        {{ __('Reset password') }}
    </div>

    <div class="auth__container-body">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="auth__row">                                
                <input placeholder="{{ __('Email address') }}" id="email" type="email" class="auth__row-input @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

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

            <div class="auth__row">                
                <input placeholder="{{ __('Confirm password') }}" id="password-confirm" type="password" class="auth__row-input" name="password_confirmation" required autocomplete="new-password">                
            </div>

            <div class="auth__row-control">
                <button type="submit" class="auth_button">
                    {{ __('Reset password') }}
                </button>
            </div>
        </form>
    </div>
</div>


</div>

@endsection

                    