<?php
namespace App\TraitLibraries;
use App\Models\CustomField;
use App\Models\CustomFieldResponse;

trait HasCustomFields
{
    use AlertMessages;

    public function deleteCustomFields($model){
        if(isset($model::$module)){
            $query = "UPDATE custom_field_responses SET 
                        deleted_by = '".\Auth::user()->username."', 
                        deleted_at = '".date('Y-m-d H:i:s')."', 
                        active = 0 
                    WHERE model = '".@$model::$module."' AND modelId = '".@$model->id."'";
            \DB::statement($query);
        }
    }

    public function saveCustomFields($request){
        $data = array();
        $customFields = \Common::getCustomFields(static::$module);
        if(!empty($customFields)){
            foreach($customFields as $row){
                if($row->active == 1){
                    if((@$request->{$row->name}) != ""){
                        $data[$row->name] = $request->{$row->name};
                    }
                }
            }

            $model = CustomFieldResponse::where('model', '=', static::$module)->where('modelId', '=', $this->id)->first();
            if(empty($model)){
                $model = new CustomFieldResponse();
            }
            $model->model = static::$module;
            $model->modelId = $this->id;
            $model->response = $data;
            if(isset($request->facilityId)){
                $model->facilityId = $request->facilityId;
            }
            if (!$model->save())
                throw new \Exception($this->setAlertError('Custom '.static::$module.' Fields'));

        }
        return true;
    }
}