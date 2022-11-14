<div class="mb-2">
    <a href="{{ route('admin.users.rate_pool-create', $user->id) }}" class="btn btn-success btn-xs">+ add user pool</a>
</div>

<table class="table text-nowrap table-striped table-sm d-inline-block">
<thead>
    <tr>
        <th>id</th>
        <th>pool</th>        
        <th>rate</th>                                
        <th>date_start</th>
        <th>date_end</th>
        <th>price_start</th>
        <th>price_start_usd</th>
        <th>price</th>                                
        <th>price_usd</th>                                
        <th>created_at</th>
        <th>updated_at</th>
        <th></th>
        <th></th>
    </tr>
</thead>
<tbody>
    @forelse ($ratePools as $pool)
    <tr>
        <td>{{ $pool['id'] }}</td>                            
        <td class="text-bold text-gray">{{ $pool['rate_pool']['name'] }}</td>        
        <td class="text-bold">{{ round($pool['rate_pool']['rate'], 2) }}%</td>
        <td>{{ $pool['rate_date_start'] }}</td>
        <td>{{ $pool['rate_date_end'] }}</td>
        <td>{{ number_format($pool['price_start'], 2) }}</td>                                                                            
        <td>${{ number_format($pool['price_start_usd'], 2) }}</td>                                                                            
        <td class="text-bold">{{ number_format($pool['price'], 2) }}</td>
        <td class="text-bold">${{ number_format($pool['price_usd'], 2) }}</td>
        <td>{{ $pool['created_at'] }}</td>                            
        <td>{{ $pool['updated_at'] }}</td>   
        <td class="text-center">
            <a href="{{ route('admin.users.rate_pool-edit', $pool['id']) }}">
                <i style="font-size: 18px;" class="fa fa-edit"></i>
            </a>
        </td>                            
        <td>
            <form class="d-inline" action="{{ route('admin.users.rate_pool-destroy', $pool['id']) }}" method="POST" onsubmit="return deleteRow();">
                @method('DELETE')
                @csrf
                <button type="submit" class="bg-transparent border-0">
                    <i style="font-size: 18px;" class="fa fa-trash text-danger"></i>
                </button>
            </form>
        </td>                            
    </tr>                       
    @empty
    @endforelse                        
</tbody> 
</table>