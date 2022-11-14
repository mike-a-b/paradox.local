<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Http\Resources\AssetResource;
use App\Http\Resources\AssetCollection;

class AssetsController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Asset $asset)
    {       
        $request->validate([
            'query' => 'string|max:20',
            'offset' => 'integer|gt:-1',
            'count' => 'integer|gt:0',
        ]); 

        $query = $request->get('query');
        $offset = $request->get('offset', 0);
        $count = $request->get('count', 100);
        $isStoplisted = $request->get('is_stoplisted', false);        

        $list = $asset->getAll(['query' => $query, 'is_stoplisted' => $isStoplisted], [$offset, $count]);
        //dd($list->toArray());

        return new AssetCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Asset $asset)
    {
        //dd($request->toArray());
        $request->validate([
            'name' => 'required|string|min:2|max:50',
            'asset_type_id' => 'required|integer',
            'is_active' => 'integer',
        ]);

        $item = $asset->insert($request->all());

        return new AssetResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset)
    {        
        $isWithItems = request()->get('with_items', false);
        //dd($isWithItems);
        if ($isWithItems) {
            $data = $asset->getAll($asset->id);
            $data = $data[0] ?? [];
        } else {
            $data = $asset;
        }

        return new AssetResource($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'name' => 'string|min:2|max:50',
            'asset_type_id' => 'integer',
            'is_active' => 'integer',
        ]);
        
        $asset->update($request->all());        

        return new AssetResource($asset);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asset $asset)
    {
        $ret = $asset;

        $asset->delete();

        return new AssetResource($ret);
    }
}