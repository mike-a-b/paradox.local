<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssetPool;

class AssetPoolsController extends Controller
{
    /**
     * Show the index page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $search = '';
        $pools = AssetPool::with('items')->paginate(20);

        //dd($pools->toArray());

        return view('admin.asset_pools.index', compact('pools', 'search'));
    }
}