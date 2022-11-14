<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAssetPool;
use App\Http\Resources\UserAssetPoolResource;
use App\Http\Resources\UserAssetPoolCollection;

class UserAssetPoolsController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        $request->validate([
                'user_id' => 'required|integer',                
            ]);
        
        $userId = $request->get('user_id');

        $list = UserAssetPool::where('user_id', $userId)->get();

        //dd($list->toArray());

        return new UserAssetPoolCollection($list);
    }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request, AssetPool $assetPool)
    // {
    //     //dd($request->toArray());
    //     $request->validate([
    //         'name' => 'required|string|min:2|max:100',
    //         'description' => 'required|string|min:2|max:500',
    //         'logo' => 'string',
    //         'asset_type_id' => 'required|integer',
    //         'is_active' => 'integer',
    //         'asset_pool_group_id' => 'integer',
    //     ]);

    //     $item = $assetPool->commit($request->all());

    //     return new AssetPoolResource($item);
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\AssetPool  $assetPool
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($assetPoolId)
    // {              
    //     $ssetPool = new AssetPool();  
    //     $isWithItems = request()->get('with_items', false);
    //     //dd($isWithItems);
    //     if ($isWithItems) {
    //         $data = $ssetPool->getAll($assetPoolId);
    //         $data = $data[0] ?? [];
    //     } else {
    //         $data = $ssetPool->find($assetPoolId);
    //     }

    //     return new AssetPoolResource($data);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\AssetPool  $assetPool
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, AssetPool $assetPool)
    // {
    //     $request->validate([
    //         'name' => 'required|string|min:2|max:50',
    //         'description' => 'required|string|min:2|max:500',
    //         'logo' => 'string',
    //         'asset_type_id' => 'required|integer',
    //         'is_active' => 'integer',
    //         'asset_pool_group_id' => 'integer',
    //     ]);
        
    //     $assetPool->fill($request->all());  
    //     $assetPool->commit($assetPool->toArray(), 'update');

    //     return new AssetPoolResource($assetPool);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\AssetPool  $assetPool
    //  * @return \Illuminate\Http\Response
    //  */
    // public function updateLogo(Request $request, AssetPool $assetPool)
    // {
    //     //dd($request->file('logo'));
    //     // $request->validate([
    //     //     'name' => 'required|string|min:2|max:50',
    //     //     'description' => 'required|string|min:2|max:500',
    //     //     'logo' => 'required|string|min:2|max:100',
    //     //     'asset_type_id' => 'required|integer',
    //     //     'is_active' => 'integer',
    //     //     'asset_pool_group_id' => 'integer',
    //     // ]);

    //     // $logoFile = 'assets/imgs/cryptocurrency/logo/'.$coinInfo->symbol.'.png';
    //     //                 file_put_contents(public_path($logoFile), file_get_contents($coinInfo->logo));

    //     $path = 'assets/imgs/pool';

    //     $request->validate([
    //         'logo' => 'required|mimes:png,jpg,jpeg|max:2048',
    //     ]);

    //     $logoFile = $request->file('logo');

    //     $fileName = $assetPool->id.'.'.$logoFile->extension();  

    //     $logoFile->move(public_path('assets/imgs/pool'), $fileName);        

        
    //     $assetPool->logo = $path.'/'.$fileName;
    //     $assetPool->save();

    //     return new AssetPoolResource($assetPool);
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\AssetPool  $assetPool
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(AssetPool $assetPool)
    // {
    //     $ret = $assetPool;

    //     $assetPool->delete();

    //     return new AssetPoolResource($ret);
    // }
}