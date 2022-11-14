<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RatePool;
use App\Models\UserAssetPool;
use App\Http\Resources\RatePoolResource;
use App\Http\Resources\RatePoolCollection;
use App\Services\DataRecalculator;

class RatePoolsController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, RatePool $ratePool)
    {        
        // $isWithItems = $request->get('with_items', false);
        // if ($isWithItems) {
        //     $list = $ratePool->getAll();
        // } else {
        //     $list = $ratePool::all();
        // }

        $list = $ratePool->getAll();

        //dd($list->toArray());

        return new RatePoolCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, RatePool $ratePool)
    {
        //dd($request->toArray());
        $request->validate([
            'name' => 'required|string|min:2|max:50',
            'description' => 'required|string|min:2|max:500',
            'logo' => 'string',
            'currency_id' => 'required|integer',
            'rate' => 'required|numeric',
            'is_active' => 'integer',
            'asset_pool_group_id' => 'integer',
        ]);

        $item = $ratePool->commit($request->all());

        // $dataRecalculator = new DataRecalculator();
        // $dataRecalculator->updatePoolsHistory($item->id);

        return new RatePoolResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RatePool  $ratePool
     * @return \Illuminate\Http\Response
     */
    public function show(RatePool $ratePool)
    {              
        // $ssetPool = new RatePool();  
        // $isWithItems = request()->get('with_items', false);
        // //dd($isWithItems);
        // if ($isWithItems) {
        //     $data = $ssetPool->getAll($ratePoolId);
        //     $data = $data[0] ?? [];
        // } else {
        //     $data = $ssetPool->find($ratePoolId);
        // }

        return new RatePoolResource($ratePool);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RatePool  $ratePool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RatePool $ratePool)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:50',
            'description' => 'required|string|min:2|max:500',
            'logo' => 'string',
            'currency_id' => 'required|integer',
            'rate' => 'required|numeric',            
            'is_active' => 'integer',
            'asset_pool_group_id' => 'integer',
        ]);        
        
        $ratePool->fill($request->all());  
        $ratePool->commit($ratePool->toArray(), 'update');

        $dataRecalculator = new DataRecalculator();
        $dataRecalculator->updatePoolsHistory($ratePool->id);
        $dataRecalculator->updateUserPools();
        $dataRecalculator->updateUserProfiles();

        return new RatePoolResource($ratePool);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RatePool  $ratePool
     * @return \Illuminate\Http\Response
     */
    public function updateLogo(Request $request, RatePool $ratePool)
    {
        $path = 'assets/imgs/r_pool';

        $request->validate([
            'logo' => 'required|mimes:png,jpg,jpeg|max:2048',
        ]);

        $logoFile = $request->file('logo');

        $fileName = $ratePool->id.'.'.$logoFile->extension();  

        $logoFile->move(public_path($path), $fileName);        

        
        $ratePool->logo = $path.'/'.$fileName;
        $ratePool->save();

        return new RatePoolResource($ratePool);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RatePool  $ratePool
     * @return \Illuminate\Http\Response
     */
    public function destroy(RatePool $ratePool)
    {
        $ret = $ratePool;
        $logo = $ratePool->logo;

        UserAssetPool::where('rate_pool_id', $ratePool->id)->delete();

        $ratePool->delete();

        if (!empty($logo)) {
            $logo = public_path($logo);
            if (file_exists($logo)) {
                unlink($logo);
            }            
        }

        $dataRecalculator = new DataRecalculator();
        $dataRecalculator->updatePoolsHistory();
        $dataRecalculator->updateUserPools();
        $dataRecalculator->updateUserProfiles();

        return new RatePoolResource($ret);
    }
}