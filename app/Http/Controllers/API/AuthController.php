<?php

namespace App\Http\Controllers\API;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Role;
use App\Models\SmsLogs;
use App\Models\User;
use App\Models\UserRole;
use App\TraitLibraries\ResponseWithHttpStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use ResponseWithHttpStatus;

    public function login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if ($validator->fails()) {

            return $this->responseFailure($validator->errors()->first(), 422);
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return $this->responseFailure(config('api.response.messages.401'), 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;

        if ($request->remember_me):
            $token->expires_at = Carbon::now()->addWeeks(1);
        endif;

        $token->save();

        $data = [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user_id' => $user->id,
            'email' => $user->email,
            'phone' => $user->contactNumber,
            'name' => $user->name
        ];

        return $this->responseSuccess(config('api.response.messages.200'), $data, 200);

    }

    public function verifyotp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'phone' =>  'required|regex:/(03)[0-9]{9}/|digits:11',
            'otp_code' =>  'required|digits:6',
            'remember_me' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->responseFailure($validator->errors()->first(), 422);
        }


        $verify=SmsLogs::verifyOtp($request->phone,$request->otp_code);

        if (empty($verify['success']))
            return $this->responseFailure(config('api.response.messages.423'), 423);

        if (!Auth::loginUsingId($verify['data']->user_id))
            return $this->responseFailure(config('api.response.messages.401'), 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');



        $token = $tokenResult->token;

        if ($request->remember_me):
            $token->expires_at = Carbon::now()->addWeeks(1);
        endif;

        $token->save();


        $data = [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user_id' => $user->id,
            'email' => $user->email,
            'phone' => $user->contactNumber,
            'name' => $user->name
        ];

        return $this->responseSuccess(config('api.response.messages.200'), $data, 200);

    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->responseSuccess(config('api.response.messages.200'), null, 200);

    }

    public function user(Request $request)
    {
        return $this->responseSuccess(config('api.response.messages.200'), $request->user(), 200);
    }
    public function sendotp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'phone' =>  'required|regex:/(03)[0-9]{9}/|digits:11',
            'remember_me' => 'boolean'
        ]);
        if ($validator->fails()) {
            return $this->responseFailure($validator->errors()->first(), 422);
        }

        $driver=Driver::with('user')->where('phone',$request->phone)->first();

        if(empty($driver->user->id))
            return $this->responseFailure(config('api.response.messages.499'), 499);
        if(!empty($driver->user->id) && $driver->active!=1)
            return $this->responseFailure(config('api.response.messages.521'), 521);

            SmsLogs::saveSMS($driver->user->contactNumber,$driver->userId,'login_otp_code');

        return $this->responseSuccess("OTP Sent", [], 200);
    }

}
