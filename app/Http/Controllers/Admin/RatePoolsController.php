<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;

class RatePoolsController extends Controller
{
    /**
     * Show the index page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {              
        return view('admin.rate_pools.index');
    }    
}