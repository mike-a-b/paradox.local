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

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="auth__container-body">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf                                               

            <div class="auth__row">
                <input placeholder="{{ __('Email address') }}" id="email" type="email" class="auth__row-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="auth__row-control">
			<a href="/login/"><div class="auth_button back">
{{ __('login') }}
</div></a>
                <button type="submit" class="auth_button">
                    {{ __('Send password reset link') }}
                </button> 
				
            </div>
        </form>
    </div>
</div>
</div>

@endsection                
