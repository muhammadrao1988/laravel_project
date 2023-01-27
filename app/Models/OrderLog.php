<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    protected $table = 'order_logs';

    public $fillable = ['id','order_id','old_status','new_status'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
