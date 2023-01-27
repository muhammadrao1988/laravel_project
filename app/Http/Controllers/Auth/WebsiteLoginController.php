<?php

namespace App\Http\Controllers\Auth;
use Common;

use App\Http\Controllers\Controller;
use App\managers\UserManager;
use App\Providers\RouteServiceProvider;
use App\TraitLibraries\RolePermissionEngine;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountType;
use App\Models\Address;
use App\Models\AccountGroup;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WebsiteLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers,RolePermissionEngine;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
        session(['url.intended' => url()->previous()]);
        return view('frontend.auth.login', ['url' => '']);
    }
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required',
            'password' => 'required'
        ]);

        $user = User::where(['email'=>$request->email])->first();
        if($user ){

            if(Hash::check($request->password, $user->password) && $user->active != 1 && $user->updated_by != 'admin'){
               /* $user->active=1;
                $user->save();*/
                return back()->withInput($request->only('email', 'remember'))->withErrors(['general'=>'Your account is deactivated']);

            }
            elseif(Hash::check($request->password, $user->password) && $user->active != 1 && $user->updated_by == 'admin'){

                return back()->withInput($request->only('email', 'remember'))->withErrors(['general'=>'Your account is currently deactivated by admin.']);
            }
        }

        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password,'active'=>1], $request->get('remember'))) {
            if(!session()->has('url.intended'))
            {
                return redirect('/');
            }else{
                return redirect(session()->get('url.intended'));
            }

        }
        return back()->withInput($request->only('email', 'remember'))->withErrors(['general'=>'Invalid email or password.']);
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }
}
