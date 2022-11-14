<div class="mb-2">
    <a href="{{ route('admin.users.pool-create', $user->id) }}" class="btn btn-success btn-xs">+ add user pool</a>
</div>

<table class="table text-nowrap table-sm d-inline-block">
<thead>
    <tr>
        <th>id</th>
        <th>name</th>
        <th>price_start_usd</th>
        <th>price_usd</th>                                
        <th>created_at</th>
        <th>updated_at</th>
        <th></th>
        <th></th>
    </tr>
</thead>
<tbody>
    @forelse ($pools as $pool)
    <tr style="background-color: #eaf1f4;">
        <td>{{ $pool['id'] }}</td>                            
        <td class="text-bold text-gray">{{ $pool['asset_pool']['name'] }}</td>
        <td>${{ number_format($pool['price_start_usd'], 2) }}</td>                                                                            
        <td class="text-bold">${{ number_format($pool['price_usd'], 2) }}</td>                                                                            
        <td>{{ $pool['created_at'] }}</td>                            
        <td>{{ $pool['updated_at'] }}</td>   
        <td class="text-center">
            <a href="{{ route('admin.users.pool-edit', $pool['id']) }}">
                <i style="font-size: 18px;" class="fa fa-edit"></i>
            </a>
        </td>                            
        <td>
            <form class="d-inline" action="{{ route('admin.users.pool-destroy', $pool['id']) }}" method="POST" onsubmit="return deleteRow();">
                @method('DELETE')
                @csrf
                <button type="submit" class="bg-transparent border-0">
                    <i style="font-size: 18px;" class="fa fa-trash text-danger"></i>
                </button>
            </form>
        </td>                            
    </tr> 
        @if (isset($poolItems[$pool['id']]))
    <tr>
        <td colspan="8">
            <table class="table table-sm table-hover text-nowrap">                            
            <tbody>
                @forelse ($poolItems[$pool['id']] as $poolItem)                               
                <tr>
                    <td>{{ $poolItem->id }}</td>                                                                
                    <td>{{ $poolItem->asset_name }}</td>                                                                            
                    <td>{{ $poolItem->asset_fraction }}%</td>                                                                            
                    <td>${{ number_format($poolItem->price_start_usd, 2) }}$</td>                                                                            
                    <td>${{ number_format($poolItem->price_usd, 2) }}</td>                                                                  
                    <td>{{ round($poolItem->asset_amount, 8) }} {{ $poolItem->symbol }}</td>
                </tr>
                @empty                                    
                @endforelse                        
            </tbody> 
            </table>
        </td>
    </tr>              
        @endif                        
    @empty 
    @endforelse                        
</tbody> 
</table>