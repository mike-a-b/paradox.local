<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'table_name',
        'model_id',
        'title',
        'data'
    ];

    public function model() : DB
    {
        return DB::table($this->table_name)->find($this->model_id);
    }
}
