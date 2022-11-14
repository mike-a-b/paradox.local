@extends('adminlte::page')

@section('title', 'Paredoxx:: Home page')

@section('content_header')
<!-- <h1 class="m-0 text-dark">Profile</h1> -->
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit currency</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.currencies.update', $currency->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">                    
                    <div class="form-group">
                        <label>Name*</label>
                        <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $currency->name }}" autocomplete="off">
                        @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror                        
                    </div>                                
                    <div class="form-group">
                        <label>Symbol*</label>
                        <input class="form-control @error('symbol') is-invalid @enderror" name="symbol" value="{{ $currency->symbol }}" autocomplete="off">
                        @error('symbol')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror                        
                    </div>                                                    
                    <div class="form-group">
                        <label>Symbol short*</label>
                        <input class="form-control @error('symbol_short') is-invalid @enderror" name="symbol_short" value="{{ $currency->symbol_short }}" autocomplete="off">
                        @error('symbol_short')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror                        
                    </div>                                                    
                    <div class="form-group">
                        <label>PriceUsd*</label>
                        <input class="form-control @error('price_usd') is-invalid @enderror" name="price_usd" value="{{ $currency->price_usd }}" autocomplete="off">
                        @error('price_usd')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror                        
                    </div>                                                    
                    <div class="form-check">
                        <input type="checkbox" {{ $currency->is_active ? 'checked' : '' }} class="form-check-input" id="isActive" name="is_active" value="1">
                        <label class="form-check-label" for="isActive">Active</label>
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