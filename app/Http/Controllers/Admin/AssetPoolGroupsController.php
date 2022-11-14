<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetPoolGroup;
use App\Services\DataRecalculator;

class AssetPoolGroupsController extends Controller
{
    
    protected $validationRools = [
        'name' => 'required|string|min:3|max:100|unique:asset_pool_groups,name',        
        'description' => 'required|string|min:3|max:300',        
        'description_ru' => 'required|string|min:3|max:300',        
        'g_type' => 'required|integer|gt:0',
    ];    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$dataList = AssetPoolGroup::all();
        $search = $request->get('search');
        //dd($search);
        $dataList = AssetPoolGroup::where(function($query) use ($search) {
                                        if (!empty($search)) {                
                                            $qs = "%$search%";
                                            $query->where('name', 'like', $qs)->orWhere('description', 'like', $qs);
                                        }            
                                    })
                                    ->orderByDesc('g_type')
                                    ->orderBy('pos')
                                    ->paginate(200);
        if (!empty($search)) {
            $dataList->appends(['search' => $search]);
        }        
        //dd($dataList->toArray());    
        
        $gTypeTitles = AssetPoolGroup::G_TYPE_TITLES;
        
        return view('admin.asset_pool_groups.index', compact('dataList', 'search', 'gTypeTitles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gTypeTitles = AssetPoolGroup::G_TYPE_TITLES;

        return view('admin.asset_pool_groups.create', compact('gTypeTitles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AssetPoolGroup $assetPoolGroup)
    {
        $request->validate($this->validationRools);
        //dd($request->toArray());        
        $assetPoolGroup->commit($request->all());

        return redirect()->route('admin.asset-pool-groups.index', $assetPoolGroup->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AssetPoolGroup $assetPoolGroup)
    {        
        //dd($pools->toArray(), $poolItems->toArray());
        $gTypeTitles = AssetPoolGroup::G_TYPE_TITLES;

        return view('admin.asset_pool_groups.edit', compact('assetPoolGroup', 'gTypeTitles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssetPoolGroup $assetPoolGroup)
    {        
        $this->validationRools['name'] = $this->validationRools['name'] . ",{$assetPoolGroup->id},id";
        $request->validate($this->validationRools);
        $fields = ['id' => $assetPoolGroup->id] + $request->all();
        //dd($fields);
        $assetPoolGroup->commit($fields, 'update');

        return redirect()->route('admin.asset-pool-groups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssetPoolGroup $assetPoolGroup)
    {
        $assetPoolGroup->delete();
        $assetPoolGroup->posRebase();

        $dataRecalculator = new DataRecalculator();        
        //$dataRecalculator->updatePoolsHistory();
        //$dataRecalculator->updateUserPools();
        $dataRecalculator->updateUserProfiles();

        return redirect()->route('admin.asset-pool-groups.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pos(AssetPoolGroup $assetPoolGroup)
    {        
        $direction = request('direction');

        $assetPoolGroup->posMove($direction);
        $assetPoolGroup->posRebase();

        return redirect()->route('admin.asset-pool-groups.index');
    }
}