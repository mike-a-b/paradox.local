<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetPoolItem;
use App\Http\Resources\AssetPoolItemResource;
use App\Http\Resources\AssetPoolItemCollection;

class AssetPoolItemsController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, AssetPoolItem $assetPoolItem)
    {        
        $request->validate([            
            'pool_id' => 'required|integer',
            'with_assets' => 'integer',
        ]);        

        $poolId = $request->get('pool_id');
        $isWithItems = $request->get('with_assets', false);

        if ($isWithItems) {
            $list = $assetPoolItem->getAll($poolId);
        } else {            
            $list = $assetPoolItem::where('asset_pool_id', $poolId)->get();
        }

        //dd($list->toArray());

        return new AssetPoolItemCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AssetPoolItem $assetPoolItem)
    {                
        //dd($request->toArray());
        $request->validate([
            'asset_pool_id' => 'integer',
            'asset_id' => 'integer',            
            'fraction' => 'numeric',            
        ]);

        //dd($request->all());

        $item = $assetPoolItem->commit($request->all());

        //dd($item->toArray());

        return new AssetPoolItemResource($item);
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\AssetPool  $assetPool
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(AssetPoolItem $assetPoolItem)
    // {        
    //     $isWithItems = request()->get('with_items', false);
    //     //dd($isWithItems);
    //     if ($isWithItems) {
    //         $data = $assetPool->getAll($assetPool->id);
    //         $data = $data[0] ?? [];
    //     } else {
    //         $data = $assetPool;
    //     }

    //     return new AssetPoolResource($data);
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssetPool  $assetPool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssetPoolItem $assetPoolItem)
    {
        $request->validate([
            'asset_pool_id' => 'integer',
            'asset_id' => 'integer',                      
            'fraction' => 'numeric',
        ]);
        
        $assetPoolItem->fill($request->all());  
        $assetPoolItem->commit($assetPoolItem->toArray(), 'update');          

        return new AssetPoolItemResource($assetPoolItem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AssetPool  $assetPool
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssetPoolItem $assetPoolItem)
    {
        $ret = $assetPoolItem;

        $assetPoolItem->delete();

        return new AssetPoolItemResource($ret);
    }
}