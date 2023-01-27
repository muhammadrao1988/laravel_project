<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

 class Audit extends Model implements \OwenIt\Auditing\Contracts\Audit
{
    use \OwenIt\Auditing\Audit;

    protected $connection = 'mongodb';
    protected $collection = 'audits';

    protected $guarded = [];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    public static function getList()
    {
        $return = self::all()->sortByDesc('_id')->take(1000);
        return $return;
    }

    public static function getDocumentId($model, $id){
        $model = $model::find($id);
        if(isset($model->documentId)){
            return $model->documentId;
        }

        if(isset($model->shipmentNumber)){
            return $model->shipmentNumber;
        }

        if(isset($model->lpnNumber)){
            return $model->lpnNumber;
        }

        if(isset($model->itemCode)){
            return $model->itemCode;
        }

        if(isset($model->display)){
            return $model->display;
        }

        if(isset($model->taskNo)){
            return $model->taskNo;
        }
    	return $id;
    }
}