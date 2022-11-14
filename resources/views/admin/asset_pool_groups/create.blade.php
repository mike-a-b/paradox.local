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
                <h3 class="card-title">Create pool group</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('admin.asset-pool-groups.store') }}" method="POST">
                @csrf                
                <div class="card-body">                    
                    <div class="form-group">
                        <label>Group type</label>
                        <select name="g_type" class="form-control">
                            <option value="0">Select group type</option>
                            @foreach ($gTypeTitles as $id=>$name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach                                                                
                        </select>
                        @error('g_type')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror                        
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" name="name" value="{{ old('name') }}" autocomplete="off">
                        @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror                        
                    </div>
                    <div class="form-group">
                        <label>Description</label>                        
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="en-tab" data-toggle="tab" href="#description-en" role="tab" aria-controls="description-en" aria-selected="true">EN</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="ru-tab" data-toggle="tab" href="#description-ru" role="tab" aria-controls="description-ru" aria-selected="true">RU</a>
                            </li>                            
                        </ul>
                        <div class="tab-content mt-1">
                            <div class="tab-pane show active" id="description-en" role="tabpanel" aria-labelledby="en-tab">
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" autocomplete="off">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="tab-pane" id="description-ru" role="tabpanel" aria-labelledby="ru-tab">
                                <textarea class="form-control @error('description_ru') is-invalid @enderror" name="description_ru" autocomplete="off">{{ old('description_ru') }}</textarea>
                                @error('description_ru')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>                            
                        </div>                  
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