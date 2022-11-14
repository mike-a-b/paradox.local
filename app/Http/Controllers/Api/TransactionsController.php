<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransactionCollection;

class TransactionsController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Transaction $transaction)
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
            $list = $transaction->getAll([], [$offset, $count]);
        } else {
            $list = $transaction::all();   
        }
             
        // dd($list->toArray());

        return new TransactionCollection($list);
    }

    public function count(Request $request, Transaction $transaction)
    {        
        $count = $transaction::count();                

        return [
            'data' => [
                'count' => $count
            ]
        ];
    }
}