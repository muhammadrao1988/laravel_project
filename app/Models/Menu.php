<?php

namespace App\Models;

use App\Models\Configuration;

class Menu extends BaseModel
{
    public static function getList(){
        $return = Configuration::where('module', 'menu')->where('forAdmin', 1);
        return $return;
    }

    public static function actionButtons($data){
        $return = '<div class="btn-group" role="group">
                        <form action="'.route('menu.store').'" method="post">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input type="hidden" name="id" value="'.$data->id.'">';
        if(\Common::canUpdate("menu")){
            if($data->active == 0){
                $return .= '<button type="submit" class="btn btn-xs btn-success">Enable</button>
                            <input type="hidden" name="active" value="1">
                            <input type="hidden" name="value" value="true">';
            }else{
                $return .= '<button type="submit" class="btn btn-xs btn-danger">Disable</button>
                            <input type="hidden" name="active" value="0">
                            <input type="hidden" name="value" value="false">';
            }
        }
        $return .= "</form></div>";
        return $return;
    }
}