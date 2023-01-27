<?php

namespace App\Models;

class FlagType extends BaseModel
{
    public static $module = "FlagType";
    
    protected $fillable = [
        'id',
        'name',
        'code',
        'active',
        'entryStatus',
    ];

    protected $importable = [
        'name',
        'code',
    ];

    protected $exportable = [
        'id',
        'name',
        'code',
        'active',
    ];

    public function get_importable(){
        $importables = $this->importable;
        foreach(\Common::getCustomFields(static::$module) as $field){
            $importables[] = $field->name;
        }
        foreach($importables as $key => $field){
            if(\Common::getSetting(static::$module, $field, 'show', "true", "", 0) == "false"){
                unset($importables[$key]);
            }
        }
        return $importables;
    }

    public function get_exportable(){
        $exportables = $this->exportable;
        foreach(\Common::getCustomFields(static::$module) as $field){
            $exportables[] = $field->name;
        }
        foreach($exportables as $key => $field){
            if(\Common::getSetting(static::$module, $field, 'show', "true", "", 0) == "false"){
                unset($exportables[$key]);
            }
        }
        return $exportables;
    }

    public static function validationRules($id = 0){
        $rules['name'] = ['required'];
        return $rules;
    }

    public static function getList(){
        $return = FlagType::select("flag_types.*");
        return $return;
    }

    public static function actionButtons($data){
        $return = '<div class="btn-group" role="group">
                        <a class="btn" href="'.route('flagtype.show', $data->id).'" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';

        if(\Common::canUpdate(static::$module)) {
            $return .= '<a class="btn" href="'.route('flagtype.edit', $data->id).'" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
        }
         
        $return .=  '</div>';
        return $return;
    }
}