@extends('adminlte::page')

@section('title', 'Paredoxx:: User balance usdt add')

@section('content_header')
<!-- <h1 class="m-0 text-dark">Profile</h1> -->
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">User balance usdt add</h3>
                <div class="float-left ml-4">{{ round($userProfile->balance_usd, 2) }}<sup>$</sup> &nbsp;&nbsp; {{ round($userProfile->balance_usdt, 2) }}<sup>usdt</sup></div>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.users.balance_usdt-store', $user->id) }}" method="POST">
                @csrf                
                <div class="card-body"> 
                    <div class="form-group">                        
                        <select name="operation_type" class="form-control">
                            <option {{ old('operation_type') == 'deposit' ? 'selected' : '' }} value="deposit">Deposit</option>
                            <option {{ old('operation_type') == 'withdraw' ? 'selected' : '' }} value="withdraw">Withdraw</option>
                        </select>                        
                    </div>                                                              
                    <div class="form-group">
                        <label>USDT</label>
                        <input class="form-control @error('balance_usdt') is-invalid @enderror" name="balance_usdt" value="{{ old('balance_usdt', 0) }}" autocomplete="off">
                        @error('balance_usdt')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
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