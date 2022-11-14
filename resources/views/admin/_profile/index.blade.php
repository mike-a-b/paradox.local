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
                <h3 class="card-title">Profile</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.profile.dosave') }}" method="POST">
                @csrf
                <div class="card-body">
                    @foreach ($settingsList as $setting)
                    <div class="form-group">
                        <label>{{ $setting->name }}</label>
                        @if ($setting->param != 'debank_check_frequency')
                        <input class="form-control" name="{{ $setting->param }}" value="{{ $setting->value }}" autocomplete="off">
                        @else
                            <select name="debank_check_frequency" class="form-control">
                                @foreach ($debankCheckFrequencySet as $val)
                                <option {{ $setting->value == $val ? 'selected' : '' }}  value="{{ $val }}">{{ $val }} {{ $setting->sufix }}</option>
                                @endforeach                                                                
                            </select>
                        @endif                                    
                    </div>
                    @endforeach                    
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