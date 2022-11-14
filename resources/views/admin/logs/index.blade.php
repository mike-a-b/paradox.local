@extends('adminlte::page')

@section('title', 'Paredox:: Home page')

@section('content_header')
<h1 class="m-0 text-dark">Logs</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card d-inline-flex">
            <div class="card-header">
                <div class="card-tools">
                    <form action="{{ route('admin.logs.index') }}" class="d-inline">
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
            <div class="card-body p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Table name</th>
                            <th>Table ID</th>
                            <th>Title</th>
                            <th>Data</th>
                            <th>Created at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataList as $data)
                        <tr>
                            <td>{{ $data->id }}</td>
                            <td>{{ $data->user_id }}</td>
                            <td>{{ $data->table_name }}</td>
                            <td>{{ $data->model_id }}</td>
                            <td>{{ $data->title }}</td>
                            <td>{{ $data->data }}</td>
                            <td>{{ $data->created_at }}</td>
                            <td>
                                <form class="d-inline" action="{{ route('admin.logs.destroy', $data->id) }}" method="POST" onsubmit="return deleteRow();">
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
            <div>
                {{ $dataList->links() }}
            </div>
        </div>
    </div>
</div>
@stop
