@extends('layouts.app')

@section('content')
<h2>Asset pool - {{ $pool->name }}</h2>

<h3>{{ $pool->price_usd }}

@if (isset($poolLastHistory['direction']) && $poolLastHistory['direction'] != 'same')
<span>                    
    @if ($poolLastHistory['direction'] == 'up')
    <span style="color:green">&UpArrow; {{ $poolLastHistory['fraction'] }}%</span>
    @else
    <span style="color:red">&DownArrow; {{ $poolLastHistory['fraction'] }}%</span>
    @endif
</span>
@endif                
</h3>

<table class="table d-inline-block">
    <thead>
        <tr>
            <th>ID</th>                                 
            <th>Price</th>                                                                
            <th>created_at</th>            
        </tr>
    </thead>
    <tbody>        
        @forelse ($poolHistory as $data)
        <tr>
            <td>{{ $data['id'] }}</td>                                        
            <td>
                {{ $data['new_price'] }}
                @if ($data['direction'] != 'same')
                <span>                    
                    @if ($data['direction'] == 'up')
                    <span style="color:green">&UpArrow; {{ $data['fraction'] }}%</span>
                    @else
                    <span style="color:red">&DownArrow; {{ $data['fraction'] }}%</span>
                    @endif
                </span>
                @endif                
            </td>
            <td>{{ $data['created_at'] }}</td>                                                          
        </tr>                            
        @empty                                                    
        @endforelse                        
    </tbody>
</table>

<div>Created: {{ $pool->created_at->format('y.m.d H:i') }}</div>

@stop