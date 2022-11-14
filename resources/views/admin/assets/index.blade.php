@extends('adminlte::page')

@section('title', 'Paradoxx:: Home page')

@section('content_header')
<h1 class="m-0 text-dark">Assets</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card d-inline-flex">                    
            <div class="card-header">                
                <div class="card-title">
                    <a href="?stoplisted=1" class="btn bg-gray px-2 btn-xs">stoplisted</a>
                </div>
                <div class="card-tools">
                    <form action="{{ route('admin.assets.index') }}" class="mt-1">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="search" value="{{ $search }}" class="form-control float-right" placeholder="Search">

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>            
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>                                                        
                            <th>Logo</th>                                                        
                            <th class="text-center">
                                @if ($sortField == 'total_balance')
                                <i class="fa {{ $sortOrder ? 'fa-caret-down' : 'fa-caret-up' }} text-secondary"></i>
                                @endif                                
                                <a class="text-dark" href="/admin/assets?sfield=name&sorder={{ ($sortOrder && $sortField == 'name') ? 0 : 1 }}&search={{ $search }}">
                                    Name
                                </a>
                            </th>                            
                            <th>Symbol</th>
                            <th>Price $</th>
                            <th>IsStoplisted</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataList as $data)                        
                        <tr>
                            <td>{{ $data->id }}</td>                                                                                                                                          
                            <td>
                                <img style="max-width: 32px;" src="/{{ $data->logo }}" alt="{{ $data->symbol }}">
                            </td>
                            <td class="text-center align-middle">{{ $data->name }}</td>                            
                            <td class="align-middle">{{ $data->symbol }}</td>                            
                            <td class="align-middle">{{ round($data->price_usd, 3) }}</td>
                            <td class="text-center align-middle">
                                @if ($data->is_stoplisted)
                                <i class="fa fa-check text-secondary table-row-icon"></i>
                                @endif                                    
                            </td>
                            <td class="align-middle text-center">
                                <a href="{{ route('admin.assets.edit', $data->id) }}">
                                    <i class="fa fa-edit table-row-icon text-gray"></i>
                                </a>
                            </td>   
                        </tr>
                        @empty
                            
                        @endforelse                        
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix d-flex justify-content-center pt-4">                
                {{ $dataList->links() }}
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
<!-- /.row -->
@stop