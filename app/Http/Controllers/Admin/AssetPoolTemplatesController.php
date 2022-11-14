<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetPoolTemplate;
use App\Services\DataRecalculator;

class AssetPoolTemplatesController extends Controller
{
    
    protected $validationRools = [
        'name' => 'required|string|min:3|max:100|unique:asset_pool_templates,name',                
        'asset_count' => 'required|integer|gt:0',
    ];    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$dataList = AssetPoolTemplate::all();
        $search = $request->get('search');
        //dd($search);
        $dataList = AssetPoolTemplate::where(function($query) use ($search) {
            if (!empty($search)) {                
                $qs = "%$search%";
                $query->where('name', 'like', $qs);
            }            
        })->orderBy('pos')->paginate(200);
        if (!empty($search)) {
            $dataList->appends(['search' => $search]);
        }        
        //dd($dataList->toArray());    
        
        return view('admin.asset_pool_templates.index', compact('dataList', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        return view('admin.asset_pool_templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AssetPoolTemplate $assetPoolTemplate)
    {
        $request->validate($this->validationRools);
        //dd($request->toArray());        
        $ret = $assetPoolTemplate->commit($request->all());

        return redirect()->route('admin.asset-pool-templates.edit', $ret);
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
    public function edit(AssetPoolTemplate $assetPoolTemplate)
    {        
        //dd($pools->toArray(), $poolItems->toArray());

        return view('admin.asset_pool_templates.edit', compact('assetPoolTemplate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssetPoolTemplate $AssetPoolTemplate)
    {        
        $this->validationRools['name'] = $this->validationRools['name'] . ",{$AssetPoolTemplate->id},id";
        $request->validate($this->validationRools);
        $fields = ['id' => $AssetPoolTemplate->id] + $request->all();
        //dd($fields);
        $AssetPoolTemplate->commit($fields, 'update');

        return redirect()->route('admin.asset-pool-templates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssetPoolTemplate $assetPoolTemplate)
    {
        $assetPoolTemplate->delete();

        return redirect()->route('admin.asset-pool-templates.index');
    }
}