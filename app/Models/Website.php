<?php

namespace App\Models;

use App\Helpers\Common;
use App\Models\Role;
use App\Notifications\ResetPassword;
use App\Notifications\ResetPasswordWebsite;
use App\TraitLibraries\ModelHelper;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Output\ConsoleOutput;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use GuzzleHttp\Client;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class Website extends Authenticatable
{
    use Notifiable, ModelHelper, HasApiTokens;

    public static $type = "Website";
    protected $guard = 'web';

    protected $table = "users";

    public static $alphaRole = "USERS";

    public static function boot(){
        parent::boot();
        static::addGlobalScope('userType', function (Builder $builder) {
            $builder->where('userType', '=', static::$type);
        });
    }

    public static $module = "Website";

    protected $fillable = [
        'id',
        'name',
        'username',
        'displayName',
        'userType',
        'prezziesType',
        'email',
        'credit',
        'password',
        'contactNumber',
        'alphaRole',
        'country',
        'city',
        'state',
        'zip',
        'address',
        'fulfill_orders',
        'offer_gift',
        'last_login_at',
        'last_login_ip',
        'active',
        'profile_image',
        'banner',
        'short_description',
        'wishlist_count',
        'privacy_setting',
        'timezone',
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

        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });
        Validator::extend('password_validation', function($attr, $value){
            return preg_match('/^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/', $value);
        });
        Validator::extend('valid_name', function($attr, $value){
            return preg_match(config('constants.VALID_NAME_VALIDATION'), $value);
        });

        $rules['name'] = ['required', 'max:100','valid_name'];
        $rules['profile_image'] = [ 'nullable',config('constants.IMG_VALIDATION'), config('constants.IMG_VALIDATION_SIZE')];
        $rules['displayName'] = ['required', 'string', 'max:100'];
        $rules['username'] = ['required','string','without_spaces','max:50' ,'unique:users,username,'.$id];
        $rules['contactNumber'] = ['nullable'];
        $rules['email'] = ['required',function($attr,$value,$fail) use($id){
            if(Website::where('email','=',$value)->where('id','!=',$id)->count() > 0){
                $fail("This email already exists.");
            }
        }];
        if(empty($id)){

            $rules['password'] = ['required' , 'string','without_spaces','passwordValidation', 'min:8'];
        }
        $rules['address'] = ['required','string'];
        $rules['country'] = ['required','string','max:30'];
        $rules['state'] = ['required','string','max:30'];
        $rules['city'] = ['required','string','max:30'];
        $rules['zip'] = ['required','max:30'];
        return $rules;
    }

    public static function validationRulesAdmin($id = 0){

        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });

        $rules['name'] = ['required', 'max:100'];
        $rules['displayName'] = ['required', 'string'];
        $rules['username'] = ['required','string','without_spaces','max:50' ,'unique:users,username,'.$id];
        $rules['email'] = ['required',function($attr,$value,$fail) use($id){
            if(Website::where('email','=',$value)->where('id','!=',$id)->count() > 0){
                $fail("This email already exists.");
            }
        }];
        return $rules;
    }

    public static function validationRulesBanner($id = 0){
        if(request()->input('type')=="banner") {
            $rules['banner'] = ['required', config('constants.IMG_VALIDATION'), config('constants.IMG_VALIDATION_SIZE')];
        }else{
            $rules['profile_img'] = ['required', config('constants.IMG_VALIDATION'), config('constants.IMG_VALIDATION_SIZE')];

        }
        return $rules;
    }
    public static function validationMsgBanner(){
        $msgs = [];
        $msgs['banner.dimensions'] = config('constants.BANNER_ERR_MSG');
        $msgs['profile_img.dimensions'] = config('constants.PROFILE_IMG_ERR_MSG');

        return $msgs;
    }

    public static function validationMsgs(){
        $msgs = [];
        $msgs['username.without_spaces'] = 'Space is not allowed';
        $msgs['password.without_spaces'] = 'Space is not allowed';
        $msgs['password.password_validation'] = 'Invalid password';
        $msgs['profile_image.dimensions'] = config('constants.PROFILE_IMG_ERR_MSG');

        return $msgs;
    }

    public static function getList(){
        $return = self::where('prezziesType', '=', 'Giftee');
        return $return;
    }

    public static function actionButtons($data){
        $return = '<div class="btn-group" role="group">
                      <a class="btn" href="'.route('giftee.show', $data->id).'" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';

        if(\Common::canUpdate(static::$module)) {
          $return .= '<a class="btn" href="'.route('giftee.edit', $data->id).'" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
        }

        /*if(\Common::canDelete(static::$module)) {
           $return .= '<form action="'.route('giftee.destroy', $data->id).'" method="post" id="delete_form_'.$data->id.'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <a class="btn delete-confirm-id" title="Delete" data-id="'.$data->id.'">
                                <i class="fas fa-trash"></i>
                            </a>
                        </form>';
        }*/
        $return .= '</div>';
        return $return;
    }

    public function getRoleIdsAttribute(){
        return $this->roles()->pluck('id', 'roleName')->all();
    }

    public function userCreateOrUpdate($id ,$request){

        $dataArray = [
            'userType' => 'Website',
            'alphaRole' => self::$alphaRole,
            'timezone' => 'US/Central',
            'prezziesType' => 'Giftee',
        ];

        $request->merge($dataArray);
        $user = new Website($request->all());
            if($id > 0){
                $user = Website::find($id);
                if(empty($request->password)){
                    $request->request->remove('password');
                }
                $user->loadModel($request->all());
            }
            if ($request->file('profile_image') && $request->remove_img != 1) {
                $imagePath = $request->file('profile_image');
                $ext = $imagePath->getClientOriginalExtension();
                $imageName = "profile_image_".date('YmdHis').".".$ext;
                $request->file('profile_image')->storeAs('uploads/profile_picture', $imageName, 'public');
                $user->profile_image = $imageName;
            }
            elseif($request->remove_img == 1){
                $user->profile_image =null;
            }
            if($user->save()){

                if($id > 0) {
                    if (!empty($request->notification_setting)) {
                        $user->notificationSettings()->forceDelete();
                        foreach ($request->notification_setting as $setting) {
                            GifteeNotificationSetting::create(
                                [
                                    'user_id' => $user->id,
                                    'setting_name' => $setting,
                                    'setting_description' => Common::notification_settings("value", $setting),
                                    'notification_type' => "notification",
                                    'active' => 1,
                                ]
                            );

                        }
                    }
                    if (!empty($request->email_notification_setting)) {
                        $user->emailNotificationSettings()->forceDelete();
                        foreach ($request->email_notification_setting as $setting) {
                            GifteeNotificationSetting::create(
                                [
                                    'user_id' => $user->id,
                                    'setting_name' => $setting,
                                    'setting_description' => Common::notification_settings("value", $setting),
                                    'notification_type' => "email",
                                    'active' => 1,
                                ]
                            );

                        }
                    }
                }else{
                    //\App\Helpers\Common::notification_settings("array") as $setting_key=>$setting_value
                    foreach (Common::notification_settings("array") as $key=>$value) {
                        GifteeNotificationSetting::create(
                            [
                                'user_id' => $user->id,
                                'setting_name' => $key,
                                'setting_description' => $value,
                                'notification_type' => "email",
                                'active' => 1,
                            ]
                        );
                    }
                    foreach (Common::notification_settings("array") as $key=>$value) {
                        GifteeNotificationSetting::create(
                            [
                                'user_id' => $user->id,
                                'setting_name' => $key,
                                'setting_description' => $value,
                                'notification_type' => "notification",
                                'active' => 1,
                            ]
                        );

                    }

                }
                $role = Role::where('roleName', 'Website User')->first();
                if (empty($role->id)) {
                    $role = \DB::table('roles')->insertGetId([
                        'roleName' => "Website User",
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
                return $user;
            }
            return false;

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
        $this->notify(new ResetPasswordWebsite($token));
    }
    public static function loggedUserData(){
        $authUser = auth()->guard('web')->user();
        return $authUser->toArray();
    }

    public static function getCustomerPaymentProfile($profile_id){
        $paymentDetail = Common::getOrganizationPaymentConfig_frontend();
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($paymentDetail['auth_id']);
        $merchantAuthentication->setTransactionKey($paymentDetail['auth_transaction_key']);
        // Set the transaction's refId
        $refId = 'ref' . time();

        // Retrieve an existing customer profile along with all the associated payment profiles and shipping addresses

        $request = new AnetAPI\GetCustomerProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setCustomerProfileId($profile_id);
        $controller = new AnetController\GetCustomerProfileController($request);
        if ($paymentDetail['auth_env'] == "Sandbox" || $paymentDetail['auth_env'] == "") {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }
        $result = [];
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
        {
            $profileSelected = $response->getProfile();
            $paymentProfilesSelected = $profileSelected->getPaymentProfiles();
            //print_r($paymentProfilesSelected);
            $email = $profileSelected->getEmail();


            foreach ($paymentProfilesSelected as $pp){

                $result[] = [
                    'success' => true,
                    'address' => $pp->getbillTo()->getAddress(),
                    'email' => $email,
                    'card' => $pp->getPayment()->getCreditCard()->getCardNumber(),
                    'expire_date' => $pp->getPayment()->getCreditCard()->getExpirationDate(),
                    'card_type' => $pp->getPayment()->getCreditCard()->getCardType(),
                    'profile_id' => Common::encrypt_decrypt($profile_id, 'encrypt'),
                    'payment_profile_id' => Common::encrypt_decrypt($pp->getCustomerPaymentProfileId(), 'encrypt'),
                ];

            }
        }
        else
        {
            /*echo "ERROR :  GetCustomerProfile: Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";*/
        }
        return $result;

    }

    public function notificationSettings()
    {
        return $this->hasMany(GifteeNotificationSetting::class, 'user_id')->where('notification_type','=','notification');
    }

    public function emailNotificationSettings()
    {
        return $this->hasMany(GifteeNotificationSetting::class, 'user_id')->where('notification_type','=','email');
    }

    public function wishLists()
    {
        return $this->hasMany(GifteeWishList::class, 'user_id');
    }
}
