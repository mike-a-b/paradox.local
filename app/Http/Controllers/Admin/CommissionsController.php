<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commission;

class CommissionsController extends Controller
{
    protected $validationRools = [
        'name' => 'required|string|min:3|max:100|unique:commissions,name',        
        'value' => 'required|numeric',
        // 'type_id' => 'required|integer',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        $search = $request->get('search');
        //dd($search);
        $dataList = Commission::where(function($query) use ($search) {
            if (!empty($search)) {                
                $qs = "%$search%";
                $query->where('name', 'like', $qs);
            }            
        })->paginate(20);
        if (!empty($search)) {
            $dataList->appends(['search' => $search]);
        }        
        //dd($dataList->toArray());        
        
        return view('admin.commissions.index', compact('dataList', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.commissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate($this->validationRools);
    
        //dd($request->toArray());
        // $commission = new Commission();
        // $commission->name = $request->input('name');
        // $commission->value = $request->input('value');                 
        // $commission->save();
        
        return redirect()->route('admin.commissions.index');
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
    public function edit(Commission $commission)
    {                
        
        return view('admin.commissions.edit', ['commission' => $commission]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Commission $commission)
    {        
        $this->validationRools['name'] = $this->validationRools['name'] . ",{$commission->id},id";        
        $request->validate($this->validationRools);

        $commission->name = $request->input('name');
        $commission->value = $request->input('value');                         
        $commission->save();            

        return redirect()->route('admin.commissions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Commission $commission)
    {
        // $commission->delete();

        return redirect()->route('admin.commissions.index');
    }
}