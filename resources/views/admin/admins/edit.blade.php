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
                <h3 class="card-title">Edit user profile</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.admins.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">                    
                    <div class="form-group">
                        <label>Name*</label>
                        <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" autocomplete="off">
                        @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror                        
                    </div>                                
                    <div class="form-group">
                        <label>Email*</label>
                        <input class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" autocomplete="off">
                        @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror                        
                    </div>                                
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" value="" autocomplete="off">                      
                    </div>
                    <div class="form-check">
                        <input type="checkbox" {{ $user->is_active ? 'checked' : '' }} class="form-check-input" id="isActive" name="is_active" value="1">
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