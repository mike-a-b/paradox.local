<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationsLog;
use App\Services\NotificationsLogService;
use App\Http\Resources\NotificationsLogResource;
use App\Http\Resources\NotificationsLogCollection;

class NotificationsLogController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, NotificationsLog $notificationsLog)
    {        
        $request->validate([
            'is_checked' => 'integer',
            'offset' => 'integer|gt:-1',
            'count' => 'integer|gt:0',
        ]);        
        $offset = $request->get('offset', 0);
        $count = $request->get('count', 100);        

        $isChecked = $request->get('is_checked');        

        $list = $notificationsLog::
                when(isset($isChecked), function($query) use ($isChecked) {
                    $query->where('is_checked', $isChecked ? 1 : 0);                    
                })
                ->orderBy('is_checked')
                ->orderByDesc('id')
                ->offset($offset)
                ->limit($count)
                ->get()
                ->map(function($item) {
                    $item->type_name = NotificationsLogService::TYPE_ID_LABELS[$item->type_id] ?? '';                     
                    return $item;
                });
        //dd($list->toArray());

        return new NotificationsLogCollection($list);
    }

    public function count(Request $request, NotificationsLog $notificationsLog)
    {        
        $isChecked = $request->get('is_checked');        

        $count = $notificationsLog::
                when(isset($isChecked), function($query) use ($isChecked) {
                    $query->where('is_checked', $isChecked ? 1 : 0);                    
                })
                ->count();                
                
        //dd($count);

        return [
            'data' => [
                'count' => $count
            ]
        ];
    }
    
    public function checkAll(Request $request, NotificationsLog $notificationsLog)
    {   
        $request->validate([
            'is_checked' => 'integer',
        ]);

        $isChecked = $request->post('is_checked') ?? 1;
        $isChecked = $isChecked ? 1 : 0;

        $count = $notificationsLog->where('is_checked', '<>', $isChecked)
                                  ->update(['is_checked' => $isChecked]);
                
        //dd($count);

        return [
            'data' => [
                'count' => $count
            ]
        ];
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NotificationsLog $notificationsLog)
    {
        $request->validate([
            'is_checked' => 'integer',
        ]);
        
        $notificationsLog->update($request->all());        

        return new NotificationsLogResource($notificationsLog);
    }
}