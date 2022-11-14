<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;
//use App\Models\Setting;

class CurrenciesController extends Controller
{
    protected $validationRools = [
        'name' => 'required|string|min:3|max:10|unique:currencies,name',
        'symbol' => 'required|string|min:3|max:10',
        'symbol_short' => 'required|string|min:1|max:1',
        'price_usd' => 'required|numeric',
        'is_active' => 'integer',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$dataList = Currency::all();
        $search = $request->get('search');
        //dd($search);
        $dataList = Currency::where(function($query) use ($search) {
            if (!empty($search)) {                
                $qs = "%$search%";
                $query->where('name', 'like', $qs)->orWhere('symbol', 'like', $qs);
            }            
        })->paginate(20);
        if (!empty($search)) {
            $dataList->appends(['search' => $search]);
        }        
        //dd($dataList->toArray());        
        
        return view('admin.currencies.index', compact('dataList', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->validationRools);
    
        //dd($request->toArray());
        $currency = new Currency();
        $currency->name = $request->input('name');
        $currency->symbol = $request->input('symbol');         
        $currency->symbol_short = $request->input('symbol_short');         
        $currency->price_usd = $request->input('price_usd');         
        $currency->is_active = empty($request->input('is_active')) ? 0 : 1;               
        $currency->save();
        
        return redirect()->route('admin.currencies.index');
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
    public function edit(Currency $currency)
    {                
        
        return view('admin.currencies.edit', ['currency' => $currency]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {        
        $this->validationRools['name'] = $this->validationRools['name'] . ",{$currency->id},id";        
        $request->validate($this->validationRools);

        $currency->name = $request->input('name');
        $currency->symbol = $request->input('symbol');         
        $currency->symbol_short = $request->input('symbol_short');         
        $currency->price_usd = $request->input('price_usd');         
        $currency->is_active = empty($request->input('is_active')) ? 0 : 1;               
        $currency->save();            

        return redirect()->route('admin.currencies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();

        return redirect()->route('admin.currencies.index');
    }
}