<?php

namespace App\Models;

use App\Models\Configuration;

class Configuration extends BaseModel
{
    protected $fillable = [
        'id',
        'module',
        'name',
        'description',
        'key',
        'value',
        'forAdmin',
        'active',
    ];

    protected $importable = [
        'module',
        'name',
        'description',
        'key',
        'value',
        'forAdmin',
        'active',
    ];

    protected $exportable = [
        'module',
        'name',
        'description',
        'key',
        'value',
        'forAdmin',
        'active',
    ];

    public function get_importable(){
        $importables = $this->importable;
        return $importables;
    }

    public function get_exportable(){
        $exportables = $this->exportable;
        return $exportables;
    }

    public static function validationRules($id = 0){
        $rules['value'] = ['required'];
        return $rules;
    }

    public static function getList(){
        $return = Configuration::where('forAdmin', 0);
        return $return;
    }

    public static function actionButtons($data){
        $return = '<div class="btn-group" role="group">
                        <a class="btn" href="'.route('configuration.show', $data->id).'" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';

        if(\Common::canUpdate("configuration")) {
            $return .= '<a class="btn" href="'.route('configuration.edit', $data->id).'" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
        }

        $return .=  '</div>';
        return $return;
    }

    public static function filterValue($value){
        if($value == "true"){
            return "Yes";
        }

        if($value == "false"){
            return "No";
        }
        return $value;
    }
}
