<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\TraitLibraries\ModelHelper;
use App\TraitLibraries\HasCustomFields;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;

//class BaseModel extends Model implements Auditable
class BaseModel extends Model
{

    use SoftDeletes, ModelHelper, HasCustomFields;
    //use \OwenIt\Auditing\Auditable;

    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            if(\Auth::check()){
                $user = \Auth::user();
                $model->created_by = $user->username;
            }
        });
        static::updating(function($model)
        {
            if(\Auth::check()) {
                $user = \Auth::user();
                $model->updated_by = $user->username;
            }
        });
        static::deleting(function($model)
        {
            if(\Auth::check()) {
                $model->deleteCustomFields($model);
                $user = \Auth::user();
                $model->deleted_by = $user->username;
                if ($model->active) {
                    $model->active = 0;
                }
                $model->save();
            }
        });
    }
}
