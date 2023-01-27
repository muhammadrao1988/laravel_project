<?php

namespace App\Models;
use App\Models\User;

class States extends BaseModel
{
    protected $table = 'states';

    public static $module = "States";

    protected $fillable = [
        'id',  'name','tax_rate','active'
    ];

    public static function getTaxValue($state_name,$amount){
        $state =self::where('name',$state_name)->select('tax_rate')->first();
        if(!empty($state->tax_rate)){
            $rate = $state->tax_rate/100;
            $state_additional = 0;
            if(strtolower($state_name)=="colorado"){
                $state_additional = 0.27;
            }
            return ($amount*$rate) + $state_additional;
        }else{
            return 0.00;
        }
    }

    public static function getList(){
        $return = Role::select("roles.*");
        return $return;
    }

    public static function actionButtons($data){
        $return = '<div class="btn-group" role="group">';

        if(\Common::canUpdate(static::$module)) {
            $return .= '<a class="btn" href="'.route('states.edit', $data->id).'" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
        }

        if(\Common::canDelete(static::$module)) {
            $return .= '<form action="'.route('states.destroy', $data->id).'" method="post" id="delete_form_'.$data->id.'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <a class="btn delete-confirm-id" title="Delete" data-id="'.$data->id.'">
                                <i class="fas fa-trash"></i>
                            </a>
                        </form>';
        }
        $return .= '</div>';
        return $return;
    }

    public static function validationRules($id = 0){
        $rules['name'] = ['required','unique:states,name,'.$id];
        $rules['tax_rate'] = ['nullable','numeric'];
        return $rules;
    }
}