<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
//use App\Models\Setting;

class AdminsController extends Controller
{
    protected $validationRools = [
        'name' => 'required|string|min:3|max:100',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:3|max:12',
        'is_active' => 'numeric',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$dataList = User::all();
        $search = $request->get('search');
        //dd($search);
        $dataList = User::where('is_admin', 1)->where(function($query) use ($search) {
            if (!empty($search)) {                
                $qs = "%$search%";
                $query->where('name', 'like', $qs)->orWhere('email', 'like', $qs);
            }            
        })->paginate(20);
        if (!empty($search)) {
            $dataList->appends(['search' => $search]);
        }        
        //dd($dataList->toArray());        
        
        return view('admin.admins.index', compact('dataList', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.admins.create');
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
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');         
        $user->is_active = empty($request->input('is_active')) ? 0 : 1;       
        $user->password = '';
        $user->is_admin = 1;
        $user->save();

        $password = $request->input('password');
        if (!empty($password)) {
            $user->setPassword($password);
        }

        // if ($user->id) {
        //     $settings = new Setting();
        //     $settings->createProfile($user->id);
        // }        

        return redirect()->route('admin.admins.index');
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
    public function edit(User $admin)
    {                
        
        return view('admin.admins.edit', ['user' => $admin]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $admin)
    {        
        $this->validationRools['email'] = $this->validationRools['email'] . ",{$admin->id},id";
        unset($this->validationRools['password']);
        $request->validate($this->validationRools);

        $admin->name = $request->input('name');
        $admin->email = $request->input('email');        
        $admin->is_active = empty($request->input('is_active')) ? 0 : 1;
        $admin->save();
        
        $password = $request->input('password');
        if (!empty($password)) {
            $admin->setPassword($password);
        }

        return redirect()->route('admin.admins.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // $settings = new Setting();
        // $settings->deleteProfile($user->id);

        $user->delete();

        return redirect()->route('admin.admins.index');
    }
}