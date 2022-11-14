@extends('adminlte::page')

@section('title', 'Paredox:: Home page')

@section('content_header')
<h1 class="m-0 text-dark">Users</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card d-inline-flex">
            <div class="card-header">
                <!-- <h3 class="card-title">Responsive Hover Table</h3> -->
                <div class="card-title">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-xs">+ add user</a>
                </div>
                <div class="card-tools">
                    <form action="{{ route('admin.users.index') }}" class="d-inline">
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
            <div class="card-body p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>                            
                            <th>Active</th>                            
                            <th>created_at</th>
                            <th>updated_at</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataList as $data)
                        <tr>
                            <td>                                
                                ${{ round($data->balance_usd) }} :
                                {{ round($data->balance_usdt, 2) }}
                                <span class="text-gray">({{ $data->id }})</span>
                            </td>
                            <td>{{ $data->name }}</td>                            
                            <td>{{ $data->email }}</td>                                                                                                     
                            <td class="text-center">
                                @if ($data->is_active)
                                <i class="fa fa-check text-secondary table-row-icon"></i>
                                @endif                                    
                            </td>                      
                            <td>{{ $data->created_at }}</td>                            
                            <td>{{ $data->updated_at }}</td>   
                            <td class="text-center">
                                <a href="{{ route('admin.users.edit', $data->id) }}">
                                    <i class="fa fa-edit table-row-icon"></i>
                                </a>
                            </td>                            
                            <td>
                                <form class="d-inline" action="{{ route('admin.users.destroy', $data->id) }}" method="POST" onsubmit="return deleteRow();">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="bg-transparent border-0">
                                        <i class="fa fa-trash text-danger table-row-icon"></i>
                                    </button>
                                </form>
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