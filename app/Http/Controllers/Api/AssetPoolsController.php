<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetPool;
use App\Models\UserAssetPool;
use App\Http\Resources\AssetPoolResource;
use App\Http\Resources\AssetPoolCollection;
use App\Services\DataRecalculator;
use App\Services\CoinmarketcapCategory;

class AssetPoolsController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, AssetPool $assetPool)
    {        
        $isWithItems = $request->get('with_items', false);
        $isActive = $request->get('is_active', -1);
        if ($isWithItems) {
            $list = $assetPool->getAll(0, $isActive);
        } else {
            $list = $assetPool::when($isActive!=-1, function($query) use ($isActive) {                                                       
                                    $query->where('is_active', $isActive);            
                                })
                                ->orderBy('asset_pool_group_id', 'DESC')
                                ->get();
        }

        //dd($list->toArray());

        return new AssetPoolCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AssetPool $assetPool)
    {
        //dd($request->toArray());
        $request->validate([
            'name' => 'required|string|min:2|max:100',
            'description' => 'required|string|min:2|max:500',
            'logo' => 'string',
            'asset_type_id' => 'required|integer',
            'is_active' => 'integer',
            'asset_pool_group_id' => 'integer',
            'is_topmarketcap_based' => 'integer',
            'asset_pool_template_id' => 'integer',
        ]);

        $startPriceUsd = 100;

        $data = $request->all() + ['price_usd' => $startPriceUsd, 'price_start_usd' => $startPriceUsd];

        $item = $assetPool->commit($data);

        // $dataRecalculator = new DataRecalculator();
        // $dataRecalculator->updatePoolsHistory($item->id);

        return new AssetPoolResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssetPool  $assetPool
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, AssetPool $assetPool)
    {              
        $isWithItems = $request->get('with_items', false);
        //dd($isWithItems);
        if ($isWithItems) {
            $data = $assetPool->getAll($assetPool->id);
            $data = $data[0] ?? [];
        } else {
            $data = $assetPool->find($assetPool->id);
        }

        return new AssetPoolResource($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssetPool  $assetPool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssetPool $assetPool)
    {
        $assetPoolStart = $assetPool->replicate();

        $request->validate([
            'name' => 'string|min:2|max:100',
            'name_short' => 'string|min:2|max:20',
            'description' => 'string|min:2|max:500',
            'logo' => 'string',
            'asset_type_id' => 'integer',
            'is_active' => 'integer',
            'is_topmarketcap_based' => 'integer',
            'asset_pool_group_id' => 'integer',
            'asset_pool_template_id' => 'integer',
            'is_new_asset_action' => 'integer',
            'price_usd' => 'numeric',
        ]);

        $isNewAssetAction = $request->boolean('is_new_asset_action');
        //$isTopmarketcapBased = $request->boolean('is_topmarketcap_based');
        
        $assetPool->fill($request->all());  
        $assetPool->commit($assetPool->toArray(), 'update');        

        $coinmarketcapCategory = new CoinmarketcapCategory();
        if ($assetPool->is_topmarketcap_based && (
                $assetPoolStart->is_topmarketcap_based != $assetPool->is_topmarketcap_based ||
                ($assetPool->asset_pool_template_id && $assetPoolStart->asset_pool_template_id != $assetPool->asset_pool_template_id)
            )
        ) {            
            $coinmarketcapCategory->rebuildAssetPool($assetPool, 'top_cmk_category');
            $coinmarketcapCategory->rebuildUserAssetPools($assetPool->id);
            $isNewAssetAction = true;
        } elseif ($assetPool->asset_pool_template_id && $assetPoolStart->asset_pool_template_id != $assetPool->asset_pool_template_id) { 
            //dd($assetPool->toArray());                       
            $coinmarketcapCategory->rebuildAssetPool($assetPool, 'cmk_category');
            $coinmarketcapCategory->rebuildUserAssetPools($assetPool->id);
            $isNewAssetAction = true;
        }

        if ($isNewAssetAction) {            
            $dataRecalculator = new DataRecalculator();
            //dd($assetPool->id);
            $dataRecalculator->updatePoolsHistory($assetPool->id);
            $dataRecalculator->rebuildUserPools($assetPool->id);
            // $dataRecalculator->updateUserPools();
            // $dataRecalculator->updateUserProfiles();
        }        

        return new AssetPoolResource($assetPool);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssetPool  $assetPool
     * @return \Illuminate\Http\Response
     */
    public function updateLogo(Request $request, AssetPool $assetPool)
    {
        $path = 'assets/imgs/pool';

        $request->validate([
            'logo' => 'required|mimes:png,jpg,jpeg|max:2048',
        ]);

        $logoFile = $request->file('logo');

        $fileName = $assetPool->id.'.'.$logoFile->extension();  

        $logoFile->move(public_path('assets/imgs/pool'), $fileName);        

        
        $assetPool->logo = $path.'/'.$fileName;
        $assetPool->save();

        return new AssetPoolResource($assetPool);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AssetPool  $assetPool
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssetPool $assetPool)
    {
        $ret = $assetPool;
        //$assetPoolId = $ret->id;
        $logo = $assetPool->logo;

        UserAssetPool::where('asset_pool_id', $assetPool->id)->delete();

        $assetPool->delete();

        if (!empty($logo)) {
            $logo = public_path($logo);
            if (file_exists($logo)) {
                unlink($logo);
            }            
        }

        $dataRecalculator = new DataRecalculator();
        // $dataRecalculator->updatePoolsHistory();
        // $dataRecalculator->rebuildUserPools($assetPoolId);
        $dataRecalculator->updateUserPools();
        $dataRecalculator->updateUserProfiles();

        return new AssetPoolResource($ret);
    }

    /**
     * Restart asset pool
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssetPool  $assetPool
     * @return \Illuminate\Http\Response
     */
    public function restart(Request $request, AssetPool $assetPool)
    {
        $poolStartPriceUsd = 100;

        $dataRecalculator = new DataRecalculator();
        $dataRecalculator->restartAssetPool($assetPool, $poolStartPriceUsd);            

        return new AssetPoolResource($assetPool);
    }
}