@extends('adminlte::page')

@section('title', 'Paredoxx:: User pool edit')

@section('content_header')
<!-- <h1 class="m-0 text-dark">Profile</h1> -->
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Create user rate pool</h3>
                <div class="float-left ml-4">{{ round($userProfile->balance_usd, 2) }}<sup>$</sup> &nbsp;&nbsp; {{ round($userProfile->balance_usdt, 2) }}<sup>usdt</sup></div>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.users.rate_pool-store', $user->id) }}" method="POST">
                @csrf                
                <div class="card-body">                    
                    <div class="form-group"> 
                        <div>
                            <label>pool</label>
                            <user-rate_pool-autocomplete user_id="{{$user->id}}" />                        
                        </div>
                        @error('rate_pool_id')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>                                
                    <div class="form-group">
                        <label>price_start</label>
                        <input class="form-control @error('price_start') is-invalid @enderror" name="price_start" value="1000" autocomplete="off">                        
                        @error('price_start')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>date_start</label>
                        <input class="form-control" name="date_start" value="{{ date('Y-m-d') }}" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>date_end</label>
                        <input class="form-control" name="date_end" value="{{ date("Y-m-d", strtotime("+1 month")) }}" autocomplete="off">
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div>
</div>
@stop