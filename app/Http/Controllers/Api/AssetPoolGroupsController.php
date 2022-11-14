<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetPoolGroup;
use App\Http\Resources\AssetPoolGroupResource;
use App\Http\Resources\AssetPoolGroupCollection;

class AssetPoolGroupsController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, AssetPoolGroup $assetPoolGroup)
    {        
        $request->validate([            
            'g_type' => 'integer',            
        ]);

        $gType = $request->get('g_type');

        $list = $assetPoolGroup::when($gType, function ($query) use ($gType) {
                                    $query->where('g_type', $gType);
                                })
                                ->orderBy('pos', 'ASC')->get();
        
        //dd($list->toArray());

        return new AssetPoolGroupCollection($list);
    }
}