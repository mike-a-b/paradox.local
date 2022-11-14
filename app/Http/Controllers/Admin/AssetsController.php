<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;

class AssetsController extends Controller
{
    /**
     * Show the index page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, Asset $assets)
    {        
        $sortFieldStart = 'tg_notice';
        $search = $request->get('search');
        $sortField = $request->get('sfield');
        $sortOrder = $request->get('sorder');
        $isStoplisted = $request->boolean('stoplisted');

        $onPageCount = $isStoplisted ? 1000 : 20;

        // $sortField = in_array($sortField, ['total_balance', 'nft_net_worth']) ? $sortField : $sortFieldStart;
        // $sortOrder = empty($sortOrder) ? 0 : 1;
        // if ($sortField == $sortFieldStart) {
        //     $sortOrder = 1;
        // }

        $dataList = Asset::when(!empty($search), function($query) use ($search) {
                                $query->where('name', 'like', "$search%")
                                    ->orWhere('symbol', 'like', "$search%");
                            })                        
                            ->when($isStoplisted, function($query) {
                                $query->where('is_stoplisted', '1');
                            })                        
                            ->orderBy('id', 'ASC')
                            ->paginate($onPageCount);
        
        //dd($dataList->toArray());

        if (!empty($search)) {
            $dataList->appends(['search' => $search]);
        }        
        if ($sortField != $sortFieldStart) {
            $dataList->appends(['sfield' => $sortField]);
            $dataList->appends(['sorder' => $sortOrder]);
        }          
        //dd($sortOrder);          

        return view('admin.assets.index', compact('dataList', 'search', 'sortField', 'sortOrder'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Asset $asset)
    {                
        //dd($asset->toArray());

        return view('admin.assets.edit', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asset $asset)
    {                    
        $validationRools = [
            'name' => 'required|string|min:2|max:50', //|unique:assets,name'.",{$asset->id},id",
            'symbol' => 'required|string|min:2|max:20',
            'slug' => 'required|string|min:1|max:20',            
            'is_stoplisted' => 'integer',
        ];
        
        $request->validate($validationRools);
        
        $asset->name = $request->post('name');
        $asset->symbol = $request->post('symbol');
        $asset->slug = $request->post('slug');
        $asset->is_stoplisted = $request->boolean('is_stoplisted') ? 1 : 0;
        $asset->save();

        return redirect()->route('admin.assets.index');
    }
}