<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetPoolTemplate;
use App\Http\Resources\AssetPoolTemplateResource;
use App\Http\Resources\AssetPoolTemplateCollection;

class AssetPoolTemplatesController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, AssetPoolTemplate $assetPoolTemplate)
    {        
        // $isWithItems = $request->get('with_items', false);
        // if ($isWithItems) {
        //     $list = $assetPoolTemplate->getAll();
        // } else {
        //     $list = $assetPoolTemplate::all();
        // }

        $list = $assetPoolTemplate::all();

        //dd($list->toArray());

        return new AssetPoolTemplateCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request, AssetPoolTemplate $assetPoolTemplate)
    // {
    //     //dd($request->toArray());
    //     $request->validate([
    //         'name' => 'required|string|min:2|max:50',
    //         'description' => 'required|string|min:2|max:500',
    //         'logo' => 'string',
    //         'currency_id' => 'required|integer',
    //         'rate' => 'required|numeric',
    //         // 'date_start' => 'required|date',
    //         // 'date_end' => 'required|date',
    //         'is_active' => 'integer',
    //         'asset_pool_group_id' => 'integer',
    //     ]);

    //     $item = $assetPoolTemplate->commit($request->all());

    //     // $dataRecalculator = new DataRecalculator();
    //     // $dataRecalculator->updatePoolsHistory($item->id);

    //     return new AssetPoolTemplateResource($item);
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AssetPoolTemplate  $assetPoolTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(AssetPoolTemplate $assetPoolTemplate)
    {              
        // $ssetPool = new AssetPoolTemplate();  
        // $isWithItems = request()->get('with_items', false);
        // //dd($isWithItems);
        // if ($isWithItems) {
        //     $data = $ssetPool->getAll($assetPoolTemplateId);
        //     $data = $data[0] ?? [];
        // } else {
        //     $data = $ssetPool->find($assetPoolTemplateId);
        // }

        //dd($assetPoolTemplate);

        return new AssetPoolTemplateResource($assetPoolTemplate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssetPoolTemplate  $assetPoolTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssetPoolTemplate $assetPoolTemplate)
    {
        $request->validate([
            'name' => "required|string|min:3|max:100|unique:asset_pool_templates,name,{$assetPoolTemplate->id},id", 
            'asset_count' => 'required|integer|gt:0',
            'is_active' => 'integer',
            'body' => 'required|string',
        ]);       
        
        $assetPoolTemplate->fill($request->all());  
        $assetPoolTemplate->commit($assetPoolTemplate->toArray(), 'update');

        return new AssetPoolTemplateResource($assetPoolTemplate);
    }    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AssetPoolTemplate  $assetPoolTemplate
     * @return \Illuminate\Http\Response
     */
    // public function destroy(AssetPoolTemplate $assetPoolTemplate)
    // {
    //     $ret = $assetPoolTemplate;

    //     UserAssetPool::where('rate_pool_id', $assetPoolTemplate->id)->delete();

    //     $assetPoolTemplate->delete();

    //     $dataRecalculator = new DataRecalculator();
    //     $dataRecalculator->updatePoolsHistory();
    //     $dataRecalculator->updateUserPools();
    //     $dataRecalculator->updateUserProfiles();

    //     return new AssetPoolTemplateResource($ret);
    // }
}