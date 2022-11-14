@extends('layouts.auth')

@section('content')








<div class="login-box">


    <div class="login-logo">
        <img src="{{ asset('images/logo.png') }}" alt="" height="50">
    </div>

   


   <div class="card card-outline card-primary"><div class="card-header"><h3 class="card-title float-none text-center" style="padding: 38px 35px 45px 0px;"> 
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('email_resend_desc') }}
                        </div>
                    @endif
                    {{ __('email_verify_desc') }}  </h3></div>
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="auth_button">{{ __('email_verify_btn_resend') }}</button>
                    </form>
                </div>


   

</div>
@endsection



