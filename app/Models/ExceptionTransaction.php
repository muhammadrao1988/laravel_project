<?php

namespace App\Models;
use App\Helpers\Common;
use App\Models\Category;
use App\Models\User;
use App\ModelsOrder;

use Illuminate\Database\Eloquent\Model;

class ExceptionTransaction extends Model
{
    protected $table='exception_transactions';

    public static $module = "Billing";

    protected $fillable = [
        'id',  'order_id','user_id', 'billing_id', 'transaction_id','description','active'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
