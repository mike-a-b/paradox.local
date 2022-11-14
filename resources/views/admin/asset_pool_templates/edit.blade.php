@extends('adminlte::page')

@section('title', 'Paredoxx:: Home page')

@section('content_header')
<!-- <h1 class="m-0 text-dark">Profile</h1> -->
@stop

@section('content')
<div class="row">
    <div class="col-12 pl-5">
        <h4>Edit pool template</h4>
        <div>            
            <pools-templates-edit template-id="{{ $assetPoolTemplate->id }}"></pools-templates-edit>
        </div>       
    </div>
</div>
@stop