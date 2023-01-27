<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\AccountType;
use App\Models\Address;
use App\Models\AccountGroup;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('frontend.auth.register', ['url' => '']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)

    {


        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });
        Validator::extend('password_validation', function($attr, $value){
            return preg_match('/^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@.#$%^&*])[a-zA-Z0-9!@.#$%^&*]{8,}$/', $value);
        });
        Validator::extend('valid_name', function($attr, $value){
            return preg_match(config('constants.VALID_NAME_VALIDATION'), $value);
        });

        $attributeNames = array(
            'name' => 'Full Name',
            'password_confirmation' => 'Confirm Password'

        );
        $messages = [
            'username.without_spaces'=>'Whitespace is not allowed',
            'password.without_spaces'=>'Whitespace is not allowed',
            'name.valid_name'=>'The full name can only contain alphabets and space. e.g Taylor Smith',
            'displayName.valid_name'=>'The display name can only contain alphabets and space. e.g Taylor Smith',
            'name.regex'=>'The name can only contain alphabets',
            'password.confirmed'=>'Password and confirm password should be similar',
            'password.password_validation'=>'Invalid password',
        ];
        $validator =  Validator::make($data, [
            'name' => ['required','valid_name', 'max:100'],
            'email' => ['required', 'string', 'email', 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', 'max:150',function($attr,$value,$fail){
                if(Website::where('email','=',$value)->count() > 0){
                    $fail("This email already exists.");
                }
            }],
            // email:'regex:/^[A-Za-z0-9\.]*@(example)[.](com)$/',
            'username' => ['required','string','without_spaces','max:50' ,'unique:users'],
            'displayName' => ['required','string','valid_name', 'max:100'],
            'password' => ['required', 'string','without_spaces','passwordValidation', 'min:8','confirmed'],
            'password_confirmation' => ['required'],
            'address' => ['required','string'],
            'country' => ['required','string','max:30'],
            'state' => ['required','string','max:30'],
            'city' => ['required','string','max:30'],
            'zipcode' => ['required','max:30'],
            'terms_condition' => ['required'],
        ],$messages);
        $validator->setAttributeNames($attributeNames);
        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create($request)
    {
        $website = new Website();
         return $website->userCreateOrUpdate(-1, $request);
    }

    public function register(Request $request)
    {

        $this->validator($request->all())->validate();
        $request->merge(['zip'=>$request->zipcode]);

        event(new Registered($user = $this->create($request)));

        session()->flash('success', 'Account has been created successfully.');

        //$this->guard('web')->login($user);

        if ($response = $this->registered($request, $user)) {
            return redirect(route("front"));
        }


        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
      //auth()->loginUsingId($user->id);
      Auth::guard('web')->loginUsingId($user->id);
        return redirect(route("front"));
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('web');
    }

}
