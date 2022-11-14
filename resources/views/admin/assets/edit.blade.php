@extends('adminlte::page')

@section('title', 'Paredoxx:: Asset Edit')

@section('content_header')
<!-- <h1 class="m-0 text-dark">Profile</h1> -->
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit asset</h3>
            </div>
            <!-- /.card-header -->
            <form action="{{ route('admin.assets.update', $asset->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">                                                                                                      
                <div class="form-group">
                    <label>Name*</label>
                    <input class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" value="{{ $asset->name }}" autocomplete="off">
                    @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>           
                <div class="form-group">
                    <label>Symbol*</label>
                    <input class="form-control form-control-sm @error('symbol') is-invalid @enderror" name="symbol" value="{{ $asset->symbol }}" autocomplete="off">
                    @error('symbol')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>           
                <div class="form-group">
                    <label>Slug*</label>
                    <input class="form-control form-control-sm @error('slug') is-invalid @enderror" name="slug" value="{{ $asset->slug }}" autocomplete="off">
                    @error('slug')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>                     
                <div class="form-check">
                    <input type="checkbox" {{ $asset->is_stoplisted ? 'checked' : '' }} class="form-check-input" id="is_stoplisted" name="is_stoplisted" value="1">
                    <label class="form-check-label" for="is_stoplisted">isStoplisted</label>
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