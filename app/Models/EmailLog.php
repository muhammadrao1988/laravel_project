<?php

namespace App\Models;
use App\Helpers\Common;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EmailLog extends BaseModel
{
    public static $module = "";
    protected $table = "email_logs";

    protected $fillable = [
        'id',
        'from',
        'to',
        'subject',
        'body',
        'response',
        'response',
    ];

    public static function validationRules($id=0){

        foreach(request()->input('SLRow') as $sr => $row) {
            if($row['id'] > 0 && (!empty(request()->file('SLRow')[$sr]['logo']))){
                $rules['SLRow.'.$sr.'.logo'] = [config('constants.IMG_VALIDATION')];
            }else if(empty($row['id'])){
                $rules['SLRow.'.$sr.'.logo'] = ['required',config('constants.IMG_VALIDATION')];

            }
        }

        return $rules;
    }

    public static function validationMsgs(){
        $msgs = [];

        foreach(request()->input('SLRow') as $key => $value) {

            $msgs['SLRow.'.$key.'.logo.required'] = 'Logo is required ';
            $msgs['SLRow.'.$key.'.logo.mimes'] = 'Only allowed the following format(jpeg,png,jpg,gif,svg) ';
            $msgs['SLRow.'.$key.'.logo.max'] = 'Maximum file size is 2 Mb. ';

        }
        return $msgs;
    }
}
