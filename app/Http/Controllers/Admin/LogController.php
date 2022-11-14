<?php

namespace App\Http\Controllers\Admin;

use App\Models\Log;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    public function index()
    {
        $search = request()->get('search');

        $dataList = Log::where(function($query) use ($search) {
            if (! empty($search)) {
                $query->where('title', 'like', "%$search%");
            }
        })
        ->orderBy('id', 'desc')
        ->paginate(20);

        if (!empty($search)) $dataList->appends(['search' => $search]);

        return view('admin.logs.index', compact('dataList', 'search'));
    }

    public function destroy(Log $log)
    {
        $log->delete();

        return redirect()->route('admin.logs.index')->with(['tab'=>'info']);
    }
}
