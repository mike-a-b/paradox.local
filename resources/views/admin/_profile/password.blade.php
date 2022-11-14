@extends('adminlte::page')

@section('title', 'Mnemo:: Home page')

@section('content_header')
<!-- <h1 class="m-0 text-dark">Password change</h1> -->
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Password change</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.profile.savepassword') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>New password</label>
                        <input type="password" class="form-control" name="password" autocomplete="off" value="">
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