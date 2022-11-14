@extends('adminlte::page')

@section('title', 'Paredoxx:: Edit commission')

@section('content_header')
<!-- <h1 class="m-0 text-dark">Profile</h1> -->
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit commission</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.commissions.update', $commission->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">                    
                    <div class="form-group">
                        <label>Name*</label>
                        <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $commission->name }}" autocomplete="off">
                        @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror                        
                    </div>                                
                    <div class="form-group">
                        <label>Value*</label>
                        <input class="form-control @error('value') is-invalid @enderror" name="value" value="{{ $commission->value }}" autocomplete="off">
                        @error('value')
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