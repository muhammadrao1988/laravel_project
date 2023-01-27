<?php

namespace App\Models;

class Flag extends BaseModel
{
    public static $module = "Flag";

    protected $fillable = [
        'id',
        'flagType',
        'parentId',
        'name',
        'description',
        'field1',
        'field2',
        'active',
        'entryStatus',
    ];

    protected $importable = [
        'flagType',
        'parentId',
        'name',
        'description',
        'field1',
        'field2',
    ];

    protected $exportable = [
        'id',
        'flagType',
        'parentId',
        'name',
        'description',
        'field1',
        'field2',
        'active',
        'entryStatus',
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
        $rules['flagType'] = ['required'];
        return $rules;
    }

    public static function getList(){
        $return = Flag::select('flags.*', 'parent.name as parentName', 'flag_types.name as flagTypeName')
                    ->leftJoin('flags as parent','parent.id','=','flags.parentId')
                    ->leftJoin('flag_types','flag_types.code','=','flags.flagType');
        if(!empty(@$_GET['flagtype'])){
            $return = $return->where('flags.flagType', '=', strip_tags($_GET['flagtype']));
        }
        return $return;
    }

    public static function actionButtons($data){
        $return = '<div class="btn-group" role="group">
                        <a class="btn" href="'.(!empty(@$_GET['flagtype']) ? \URL::to('/flag/'.$data->id.'/?flagtype='.@$_GET['flagtype']) : \URL::to('/flag/'.$data->id)).'" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';

        if(\Common::canUpdate(static::$module)) {
            $return .= '<a class="btn" href="'.(!empty(@$_GET['flagtype']) ? \URL::to('/flag/'.$data->id.'/edit?flagtype='.@$_GET['flagtype']) : \URL::to('/flag/'.$data->id.'/edit')).'" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
        }

        if(\Common::canDelete(static::$module)) {
            $return .= '<form action="'.route('flag.destroy', $data->id).'" method="post" id="delete_form_'.$data->id.'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input type="hidden" name="flagtype" value="'.@$_GET['flagtype'].'">
                            <a class="btn delete-confirm-id" title="Delete" data-id="'.$data->id.'">
                                <i class="fas fa-trash"></i>
                            </a>
                        </form>';
        }

        $return .=  '</div>';
        return $return;
    }

    public static function getData($flagType = ""){
        $return = Flag::query();
        if(!empty($flagType)){
            $return = $return->where('flagType', $flagType);
        }
        $return = $return->get();
        return $return;
    }

}
