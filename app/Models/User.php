<?php

namespace App\Models;

use App\Helpers\Common;
use App\Models\Role;
use App\TraitLibraries\ModelHelper;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Symfony\Component\Console\Output\ConsoleOutput;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use Notifiable, ModelHelper, HasApiTokens;

    public static $module = "User";

    protected $fillable = [
        'id',  'name','username', 'email', 'password','company_id','contactNumber','alphaRole', 'active','userType', 'displayName',
        'country',
        'city',
        'state',
        'zip',
        'address',
        'fulfill_orders',
        'offer_gift',
        'last_login_at',
        'last_login_ip',
        'updated_by'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function save(array $options = []){
        if ($this->isDirty('password')) {
            $this->password = bcrypt($this->password);
        }

        return parent::save($options);
    }
    public function roles(){
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public static function validationRules($id = 0){
        $rules['name'] = ['required', 'regex:/^[\pL\s\-]+$/u'];
        $rules['contactNumber'] = ['nullable', 'numeric'];
        if(empty($id)){
            $rules['email'] = ['required', 'unique:users,email'];
            $rules['password'] = ['required'];
        }
        $rules['alphaRole'] = ['required'];
        $rules['role_id'] = ['required_if:alphaRole,USER'];
        return $rules;
    }

    public static function getList(){
        $return = User::where('email', '<>', 'admin@admin.com');
        return $return;
    }

    public static function actionButtons($data){
        $return = '<div class="btn-group" role="group">
                      <a class="btn" href="'.route('users.show', $data->id).'" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';

        if(\Common::canUpdate(static::$module)) {
          $return .= '<a class="btn" href="'.route('users.edit', $data->id).'" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
        }

        if(\Common::canDelete(static::$module)) {
           $return .= '<form action="'.route('users.destroy', $data->id).'" method="post" id="delete_form_'.$data->id.'">
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

    public function getRoleIdsAttribute(){
        return $this->roles()->pluck('id', 'roleName')->all();
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class, 'userId', 'id');
    }

    public function transporters()
    {
        return $this->hasMany(Transporter::class, 'userId', 'id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'userId', 'id');
    }

    public static function availableUser(){
        return User::doesntHave('drivers')->doesntHave('transporters')->doesntHave('customers')->where('username','!=','admin')->get();
    }

    public static function userCreateUpdate($request, $id=0){ 

        $username = str_replace(" ","_",strtolower($request->name));
        $username = str_replace("-","_",strtolower($username));
        $username = preg_replace('/[^A-Za-z0-9\_]/', '', $username);

        if( $id == 0){
            $user = new User($request->all());
            $user->username = $username;
            if($user->save()){
                $role = Role::where('roleName', 'Web User')->first();
                if (empty($role->id)) {
                    $role = Role::create([
                        'roleName' => 'Web User',
                        'permissions' => '[]',
                    ]);
                }
                UserRole::create([
                    'role_id' => $role->id,
                    'user_id' => $user->id,
                ]);
                return $user;
            }
            return false;
        }else{
            $user = User::find($id);
            $request->request->remove('id');
            if($request->password==""){
                $request->request->remove('password');
            }

            $user->loadModel($request->all());
            $user->username = $username;
            if($user->save()){
                return $user;
            }
            return false;

        }

    }


    public static function updateRole($userId,$roleName){

        $role = Role::where('roleName', $roleName)->first();
        if (empty($role->id)) {
            $role = \DB::table('roles')->insertGetId([
                'roleName' => $roleName,
                'permissions' => '[]',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
            $roleId = $role;
        }else{
            $roleId = $role->id;
        }
        $userRole = UserRole::where('user_id',$userId)->where('role_id',$roleId)->first();

        if(empty($userRole)){
            \DB::table('user_roles')->insertGetId([
                'user_id' => $userId,
                'role_id' => $roleId,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]);
        }
    }
}
