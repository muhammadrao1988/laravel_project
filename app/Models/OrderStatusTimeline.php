<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Order;
use App\Models\GifteeWishListItem;

class OrderStatusTimeline extends Model
{

    protected $table='order_status_timeline';
    public $fillable = ['id','type','order_id','status','description','active'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
}
