<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExchangeInfo;
use App\Http\Resources\ExchangeInfoResource;
// use App\Http\Resources\ExchangeInfoCollection;

class ExchangeInfoController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ExchangeInfo $exchangeInfo)
    {        
        
        $list = $exchangeInfo->findOrfail(1);
        //dd($list->toArray());

        return new ExchangeInfoResource($list);
        // return new NotificationsLogCollection($list);
    }
}