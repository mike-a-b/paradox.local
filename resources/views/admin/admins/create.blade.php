@extends('adminlte::page')

@section('title', 'Paredox:: Home page')

@section('content_header')
<!-- <h1 class="m-0 text-dark">Profile</h1> -->
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Create user profile</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.admins.store') }}" method="POST">
                @csrf                
                <div class="card-body">                    
                    <div class="form-group">
                        <label>Name*</label>
                        <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="off">
                        @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror                        
                    </div>                                
                    <div class="form-group">
                        <label>Email*</label>
                        <input class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="off">
                        @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>                                
                    <div class="form-group">
                        <label>Password*</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="" autocomplete="off">
                        @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-check">                        
                        <input type="checkbox" class="form-check-input" id="isActive" {{ !old() || old('is_active') ? 'checked' : '' }} name="is_active" value="1">
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