<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
use App\Http\Resources\CurrencyResource;
use App\Http\Resources\CurrencyCollection;

class CurrenciesController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Currency $currency)
    {        
        $list = $currency::all();        
        //dd($list->toArray());

        return new CurrencyCollection($list);
    }
}