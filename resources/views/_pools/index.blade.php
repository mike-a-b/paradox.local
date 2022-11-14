@extends('layouts.app')

@section('content')
<h2>Asset pools</h2>

<table class="table d-inline-block">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>                            
            <th>Price</th>                                                                
            <th>created_at</th>            
        </tr>
    </thead>
    <tbody>
        @forelse ($pools as $data)
        <tr>
            <td>{{ $data['id'] }}</td>                            
            <td class="text-bold">
                <a href="{{ route('pools.show', $data['id']) }}">{{ $data['name'] }}</a>
            </td>
            <td>
                {{ $data['price_usd'] }}
                @if (isset($poolsHistory[$data['id']]) && $poolsHistory[$data['id']]['direction'] != 'same')
                <span>                    
                    @if ($poolsHistory[$data['id']]['direction'] == 'up')
                    <span style="color:green">&UpArrow; {{ $poolsHistory[$data['id']]['fraction'] }}%</span>
                    @else
                    <span style="color:red">&DownArrow; {{ $poolsHistory[$data['id']]['fraction'] }}%</span>
                    @endif
                </span>
                @endif                
            </td>
            <td>{{ $data['created_at']->format('y.m.d H:i') }}</td>                                                          
        </tr>                            
        @empty                                                    
        @endforelse                        
    </tbody>
</table>
@stop