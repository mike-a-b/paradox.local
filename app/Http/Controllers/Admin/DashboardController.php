<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
//use App\Models\Setting;

class DashboardController extends Controller
{    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // //$dataList = Currency::all();
        // $search = $request->get('search');
        // //dd($search);
        // $dataList = Currency::where(function($query) use ($search) {
        //     if (!empty($search)) {                
        //         $qs = "%$search%";
        //         $query->where('name', 'like', $qs)->orWhere('symbol', 'like', $qs);
        //     }            
        // })->paginate(20);
        // if (!empty($search)) {
        //     $dataList->appends(['search' => $search]);
        // }        
        // //dd($dataList->toArray());        
        
        return view('admin.dashboard.index'); // compact('dataList', 'search')
    }

    public function transactions()
    {
        return view('admin.dashboard.transactions');
    }

    public function notificationsLog()
    {
        return view('admin.dashboard.notifications_log');
    }

    public function exchangeInfo()
    {
        return view('admin.dashboard.exchange_info');
    }

    public function exchangeBotLog()
    {
        return view('admin.dashboard.exchange_bot_log');
    }
}