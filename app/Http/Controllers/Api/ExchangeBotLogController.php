<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExchangeBotLog;
use App\Http\Resources\ExchangeBotLogResource;
use App\Http\Resources\ExchangeBotLogCollection;

class ExchangeBotLogController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ExchangeBotLog $exchangeBotLog)
    {        
        $request->validate([
            'with_items' => 'integer',
            'offset' => 'integer|gt:-1',
            'count' => 'integer|gt:0',
        ]);        
        $offset = $request->get('offset', 0);
        $count = $request->get('count', 100);        
        
        $isWithItems = $request->get('with_items', false);        
        if ($isWithItems) {
            $list = $exchangeBotLog->getAll([], [$offset, $count]);
        } else {
            $list = $exchangeBotLog::all();   
        }
             
        // dd($list->toArray());

        return new ExchangeBotLogCollection($list);
    }

    public function count(Request $request, ExchangeBotLog $exchangeBotLog)
    {        
        $count = $exchangeBotLog::count();                
                
        //dd($count);

        return [
            'data' => [
                'count' => $count
            ]
        ];
    }
}