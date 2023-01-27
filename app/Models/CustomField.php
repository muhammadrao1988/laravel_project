<?php

namespace App\Models;
use Common;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomField extends BaseModel
{
    public static $module = "CustomField";

    protected $fillable = [
        'id',
        'model',
        'title',
        'name',
        'description',
        'type',
        'rules',
        'acceptable',
        'defaultValue',
        'sort',
        'active',
    ];

    public static function validationRules($id = 0){
        $rules['model'] = ['required','string'];
        $rules['field.*.title'] = ['required','string', 'distinct'];
        $rules['field.*.name'] = ['required','string', 'distinct'];
        $rules['field.*.description'] = ['nullable','string'];
        $rules['field.*.type'] = ['required','string'];
        $rules['field.*.rules'] = ['nullable'];
        $rules['field.*.acceptable'] = ['nullable'];
        $rules['field.*.defaultValue'] = ['nullable'];
        $rules['field.*.sort'] = (request()->has('field.*.delete') == 0 ? ['required', 'numeric', 'gt:0', 'distinct'] : "");
        $rules['field.*.isRequired'] = ['required'];
        $rules['field.*.active'] = ['required'];
        return $rules;
    }

    public static function getList(){
        $return = CustomField::select('model')->distinct()->get();
        return $return;
    }

    public static function actionButtons($data){
        $return = '<div class="btn-group" role="group">
                      <a class="btn" href="' . route('customfield.show', $data->model) . '" title="View">
                         <i class="fas fa-eye"></i>
                      </a>';

        if(\Common::canUpdate(static::$module)) {
          $return .= '<a class="btn" href="' . route('customfield.edit', $data->model) . '" title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>';
        }

        $return .= '</div>';
        return $return;
    }

    public static function makeRules($row){
        $rules = '';
        if($row['isRequired'] == "1"){
            $rules .= "required";
        }else{
            $rules .= "nullable";
        }

        if($row['type'] == "text" || $row['type'] == "textarea"){
            $rules .= "|string";
        }

        if($row['type'] == "number"){
            $rules .= "|numeric";
        }

        if(!empty($row['acceptable']) && $row['type'] != "checkbox"){
            $rules .= "|in:".SELF::serializeAcceptable($row['acceptable']);
        }

        return $rules;
    }

    public static function serializeAcceptable($acceptable){
        $acceptable = explode(',', $acceptable);
        $acceptable = array_filter($acceptable);
        $acceptable = array_map('trim', $acceptable);
        $acceptable = implode(',', $acceptable);
        return $acceptable;
    }

    public static function validate($request){
        foreach ($request->field as $sr => $row) {
            if(!empty(@$row['id']) && @$row['delete'] == 1){
                $model = CustomField::find($row['id']);
                /*if(!empty($model->responses()->get()->toArray())){
                    return \Common::throwValidationError('', 'Field # '.$sr.' already has entries. It cannot be deleted.');
                }*/
            }

            if(@$row['delete'] == 0){
                $model = "App\Models\\".$request['model'];
                $model = new $model();
                if(property_exists($model, 'fillable')){
                    $fillables = $model->getFillable();
                    if(in_array($row['name'], (array)$fillables)){
                        return \Common::throwValidationError('', 'Field # '.$sr.' already exixts.');
                    }
                }
            }
        }
    }
}
