<?php

namespace App\Models;
use App\Models\User;

class Role extends BaseModel
{
    protected static $logAttributes = [ 'roleName', 'permissions'];
    protected static $logName = "Role";
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    protected $fillable = [
        'id', 'roleName', 'permissions'
    ];

    public $rule = array(
        'roleName' => 'required|min:3',
        'permissions' => 'required'
    );

    public $searchableColumns = array(
        'roleName' =>
            array(
                'dataType' => 'like',
                'join' => 'roles.roleName'
            ),
        'created' => array(
            'dataType' => 'daterange',
            'join' => 'roles.created_at'
        ),
    );

    public function user(){
        return $this->belongsToMany(User::class, 'role_users');
    }

    public static function getList(){
        $return = Role::select("roles.*");
        return $return;
    }

    public static function actionButtons($data){
        $return = '<div class="btn-group" role="group">';

        if(\Common::canUpdate("role")) {
          $return .= '<a class="btn" href="'.route('roles.edit', $data->id).'" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
        }

        if(\Common::canDelete("role")) {
           $return .= '<form action="'.route('roles.destroy', $data->id).'" method="post" id="delete_form_'.$data->id.'">
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
}