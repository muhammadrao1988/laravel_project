<?php

namespace App\Models;

use App\Helpers\Common;
use App\Models\Role;
use App\Notifications\ResetPassword;
use App\TraitLibraries\ModelHelper;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\Console\Output\ConsoleOutput;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;

class Admin extends Authenticatable
{
    use Notifiable, ModelHelper, HasApiTokens;

    public static $module = "User";

    protected $guard = 'admin';

    public static $type = "Admin";
    protected $table = "users";

    public static function boot(){
        parent::boot();
        static::addGlobalScope('userType', function (Builder $builder) {
            $builder->where('userType', '=', static::$type);
        });
    }

    protected $fillable = [
        'id',  'name','username', 'email', 'password','organizationId','contactNumber','alphaRole', 'active','userType','timezone','organization_admin',
        'last_login_at',
        'last_login_ip',
        'created_by',
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
        $rules['email'] = ['required','email',function($attr,$value,$fail) use($id){
            if(Admin::where('email','=',$value)->where('id','!=',$id)->count() > 0){
                $fail("This email has already exists.");
            }
        }];
       /* $rules['username'] = ['required','regex:/^\S*$/u',function($attr,$value,$fail) use($id){
            if(Admin::where('username','=',$value)->where('id','!=',$id)->count() > 0){
                $fail("This username has already exists.");
            }
        }];*/
        if(empty($id)){
            $rules['password'] = ['required'];
        }
        $rules['contactNumber'] = ['nullable', 'numeric'];
        $rules['active'] = [Rule::in(["1","0"])];
        $rules['alphaRole'] = ['required'];
        $rules['role_id'] = [function($attr,$value,$fail) use ($id){
            if(request()->input('alphaRole')=="USERS"){

                $roleSelected = "";
                foreach (request()->input('role_id') as $role){
                    if($role=="" || $role==0){
                        $fail("Please select role");
                        break;
                    }else{
                        $roleSelected = Role::find($value);
                        if(empty($roleSelected)){
                            $fail("Invalid role selected");
                            break;
                        }
                    }
                }
                $roleType = $roleSelected[0]->roleType;

                if($roleType=="Organization"){
                    if(empty(request()->organizationId)){
                        $fail("Organization field is required");
                    }
                    else if(empty(Organization::find($value)))
                    {
                        $fail("Invalid organization selected.");
                    }

                }else{
                    if(!empty(request()->organizationId)){
                        $fail("You can not select organization for the selected role.");
                    }
                }
            }

        }];

        return $rules;
    }

    public static function getList(){
        $return = Admin::where('userType','=','Admin');
        return $return;
    }

    public static function actionButtons($data,$organizationId=0){
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


    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organizationId', 'id');
    }

    public static function availableUser(){
        return User::doesntHave('drivers')->doesntHave('transporters')->doesntHave('customers')->where('username','!=','admin')->get();
    }

    public static function userCreateUpdate($id=0,$request){

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

    public static function userCreateOrUpdate($id = 0,$request){
        $username = str_replace(" ","_",strtolower($request->name));
        $username = str_replace("-","_",strtolower($username));
        $username = preg_replace('/[^A-Za-z0-9\_]/', '', $username);

        if( $id == 0){
            if(empty($request->password)){
                $password = rand();
                $request->merge(['password'=>$password]);
            }else{
                $password = $request->password;
            }
            $user = new User($request->all());
            $user->username = $username;
            if($user->save()){
                $role = Role::where('roleName', @$request->roleName)->first();
                if (empty($role->id)) {
                    $role = \DB::table('roles')->insertGetId([
                        'roleName' => @$request->roleName,
                        'permissions' => '[]',
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                    ]);
                    $roleId = $role;
                }else{
                    $roleId = $role->id;
                }
                UserRole::create([
                    'role_id' => $roleId,
                    'user_id' => $user->id,
                ]);
                if(Common::isAPIRequest()){
                    return ['id'=>$user->id,'password'=>$password,'email'=>$user->email];
                }
                return $user;
            }
            return false;
        }
        return false;

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
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
    public static function loggedUserData(){
        $authUser = Auth::guard('admin')->user();
        return $authUser->toArray();
    }
}
