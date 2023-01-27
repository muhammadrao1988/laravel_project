<?php

namespace App\Http\Controllers\Frontend\MyAccount;

use App\ContactUs;
use App\Events\NotificationSent;
use App\Helpers\Common;
use App\HomePage;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\Followers;
use App\Models\GifteeNotificationSetting;
use App\Models\GifteeWishList;
use App\Models\Notification;
use App\Models\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;
use App\TraitLibraries\ResponseWithHttpStatus;

class ManageFollowController extends Controller
{
    use ResponseWithHttpStatus;
    protected $mainViewFolder = 'frontend.follows.';



    public function following(Request $request){

        $data['heading'] = "Following";
        $data['active_following']  = "active";
        $data['active_follower'] = "";
        $data['route_name'] = "following";
        $relation_name = "user_detail";
        $auth_user = auth()->guard('web')->user();
        $records =  Followers::with(['user_detail'])->where('follower_id','=',$auth_user->id)->paginate(config('constants.PER_PAGE_LIMIT'));
        $last_page = $records->lastPage();
        if($request->ajax()){
            return view($this->mainViewFolder . 'following_follower_pagination',compact('records','relation_name','last_page','data'))->render();
            //return response()->json(['html'=>$view]);
        }

        return view($this->mainViewFolder . 'following_follower',compact('data','records','relation_name','last_page'));

    }

    public function followers(Request $request)
    {
        $data['heading'] = "Followers";
        $data['active_following']  = "";
        $data['active_follower'] = "active";
        $data['route_name'] = "followers";
        $auth_user = auth()->guard('web')->user();
        //DB::enableQueryLog();
        $records =  Followers::with(['follower_detail'])->where('user_id','=',$auth_user->id)->orderBy('following_status','asc')->paginate(config('constants.PER_PAGE_LIMIT'));
        //dd($records->toArray());
        //dd(DB::getQueryLog());
        $last_page = $records->lastPage();
        $relation_name = "follower_detail";

        if($request->ajax()){
            return view($this->mainViewFolder . 'following_follower_pagination',compact('records','data','relation_name','last_page'))->render();
            //return response()->json(['html'=>$view]);
        }
        return view($this->mainViewFolder . 'following_follower',compact('data','records','relation_name','last_page'));
    }

    public function follow_operation(Request $request){
        if($request->input('operation_type') && $request->input('username')){

            $username = $request->input('username');
            $operation_type = $request->input('operation_type');
            $operation_type_array = array("follow_request","unfollow_request","rejected_request","accepted_request","cancel_request","unfollow_following_request");
            if(!in_array($operation_type,$operation_type_array)){
                return new JsonResponse(['errors' => ['general' => ['Invalid action performed']]], 422);

            }

            $auth_user = \auth()->guard('web')->user();
            if(empty($auth_user->id)){
                return new JsonResponse(['errors' => ['general' => ['Please first login or sign up to request follow']]], 422);
            }
            $user_profile = Website::where('username','=',$username)->first();
            if(empty($user_profile->id)){
                return new JsonResponse(['errors' => ['general' => ['The requested profile detail is not exist in system.']]], 422);
            }

            if($user_profile->id==$auth_user->id){
                return new JsonResponse(['errors' => ['general' => ['You can not perform any operation to your own profile.']]], 422);
            }

            if($request->input('redirect_url')){
                $redirect = $request->input('redirect_url');
            }else{
                $redirect = route('profileUrl',$user_profile->username);
            }

            if($operation_type=="follow_request"){
                $following_status = "Accepted";
                $following_message = "You have start following ".$user_profile->username." user";

                $user_id = $user_profile->id;
                $follower_id = $auth_user->id;
                $follower_name = $auth_user->username;
                $to_email = $user_profile->email;
                $check_follow_entry = Followers::where('user_id','=',$user_id)->where('follower_id','=',$follower_id)->first();
                $email_subject = "You just got a new follower!";
                $email_message =  $follower_name." just followed you.";
                $email_btn_txt = " View Profile ";
                $notification_message =  $follower_name." followed you";
                if(!empty($check_follow_entry->id)){
                    return new JsonResponse(['errors' => ['general' => ['Unable to perform your action. Your request has already been send to this profile.']]], 422);
                }
                if($user_profile->privacy_setting==1){
                    $following_status = "Pending";
                    $following_message = $user_profile->username." account is private.We have sent your follow request for approval.";
                    $email_subject = "New Follow Request";
                    $email_message = $follower_name." has requested to follow you.";
                    $notification_message = $follower_name." requested to follow you";
                    $email_btn_txt = " Respond to Request ";

                }
                $follower_record = Followers::create([
                    'user_id' => $user_id,
                    'follower_id' => $follower_id,
                    'following_status' => $following_status,
                    'active' => 1,
                ]);

                session()->flash('success', $following_message);

                if(Followers::notificationSetting($user_profile->id,"follow_profile")) {
                    //notification
                    Notification::create([
                        'model_id' => $follower_record->id,
                        'model' => "Followers",
                        'user_id' => $user_profile->id,
                        'from_user_id' => $auth_user->id,
                        'url' => route('profileUrl', $auth_user->username),
                        'title' => $notification_message,
                        'description' => $notification_message
                    ]);
                }
                if(Followers::emailNotificationSetting($user_profile->id,"follow_profile")) {
                    //send email
                    $body = [
                            'msg'=>$email_message,
                            'url'=> route('profileUrl',$auth_user->username),
                            'btn_text'=>$email_btn_txt,
                            'subject' => $email_subject,
                            'module' => "Follower",
                            //'send_to' => "muhammadrao1988@gmail.com",
                            'send_to' => $user_profile->email,
                        ];
                        dispatch(new SendEmailJob($body))->onQueue('default');
                }
                return new JsonResponse(['status'=>'success','action'=>'redirect','url'=>$redirect],200);

            }
            else if($operation_type=="unfollow_request"){
                $user_id = $auth_user->id;
                $follower_id = $user_profile->id;
                $check_follow_entry = Followers::where('user_id','=',$user_id)->where('follower_id','=',$follower_id)->first();
                if(empty($check_follow_entry->id)){
                    return new JsonResponse(['errors' => ['general' => ['Unable to perform your action because your profile is not exist in the following list']]], 422);
                }
                Followers::where('id',$check_follow_entry->id)->forceDelete();
                session()->flash('success', "You have unfollowed user ".$user_profile->username);
                return new JsonResponse(['status'=>'success','action'=>'redirect','url'=>$redirect],200);


            }
            else if($operation_type=="unfollow_following_request"){
                $user_id = $user_profile->id;
                $follower_id = $auth_user->id;
                $check_follow_entry = Followers::where('user_id','=',$user_id)->where('follower_id','=',$follower_id)->first();
                if(empty($check_follow_entry->id)){
                    return new JsonResponse(['errors' => ['general' => ['Unable to perform your action because your profile is not exist in the following list']]], 422);
                }
                Followers::where('id',$check_follow_entry->id)->forceDelete();
                session()->flash('success', "You have unfollowed user ".$user_profile->username);
                return new JsonResponse(['status'=>'success','action'=>'redirect','url'=>$redirect],200);


            }
            else if($operation_type=="cancel_request"){
                $user_id = $user_profile->id;
                $follower_id = $auth_user->id;
                $check_follow_entry = Followers::where('user_id','=',$user_id)->where('follower_id','=',$follower_id)->first();
                if(empty($check_follow_entry->id)){
                    return new JsonResponse(['errors' => ['general' => ['Unable to perform your action because your profile is not exist in the following list']]], 422);
                }
                //removing follow request
                Followers::where('id',$check_follow_entry->id)->forceDelete();
                //removing notification
                Notification::where('model','=','Followers')->where('model_id','=',$check_follow_entry->id)->forceDelete();
                session()->flash('success', "The request has cancelled successfully.");
                return new JsonResponse(['status'=>'success','action'=>'redirect','url'=>$redirect],200);


            }
            else if($operation_type=="rejected_request"){
                $user_id = $auth_user->id;
                $follower_id = $user_profile->id;
                $check_follow_entry = Followers::where('user_id','=',$user_id)->where('follower_id','=',$follower_id)->first();
                if(empty($check_follow_entry->id)){
                    return new JsonResponse(['errors' => ['general' => ['Unable to perform this action. Record does not exist']]], 422);
                }

                Followers::where('id',$check_follow_entry->id)->forceDelete();
                if(Followers::notificationSetting($user_profile->id,"follow_profile")) {
                    $msg = $auth_user->username . " has rejected your following request";
                    //notification
                    Notification::create([
                        'model_id' => $check_follow_entry->id,
                        'model' => "Followers",
                        'user_id' => $follower_id,
                        'from_user_id' => $user_id,
                        'url' => route('profileUrl', $auth_user->username),
                        'title' => $msg,
                        'description' => $msg
                    ]);
                }
                if(Followers::emailNotificationSetting($user_profile->id,"follow_profile")) {
                    //send email
                    $body = [
                        'msg'=>$msg,
                        'url'=> route('profileUrl',$auth_user->username),
                        'btn_text'=> " View Profile ",
                        'subject' => "Follow Request Rejected",
                        'module' => "Follower",
                        //'send_to' => "muhammadrao1988@gmail.com",
                        'send_to' => $user_profile->email,
                    ];
                    dispatch(new SendEmailJob($body))->onQueue('default');
                }
                session()->flash('success', "Request has been rejected successfully.");
                return new JsonResponse(['status'=>'success','action'=>'redirect','url'=>$redirect],200);


            }
            else if($operation_type=="accepted_request"){
                $user_id = $auth_user->id;
                $follower_id = $user_profile->id;
                $to_email = $user_profile->email;
                $check_follow_entry = Followers::where('user_id','=',$user_id)->where('follower_id','=',$follower_id)->first();
                if(empty($check_follow_entry->id)){
                    return new JsonResponse(['errors' => ['general' => ['Unable to perform this action. Record does not exist']]], 422);
                }

                Followers::where('id','=',$check_follow_entry->id)->update(['following_status'=>'Accepted']);
                session()->flash('success', "Request has been accepted successfully.");

                if(Followers::notificationSetting($user_profile->id,"follow_profile")) {
                    $msg = $auth_user->username . " has been accepted your following request";
                    //notification
                    Notification::create([
                        'model_id' => $check_follow_entry->id,
                        'model' => "Followers",
                        'user_id' => $follower_id,
                        'from_user_id' => $user_id,
                        'url' => route('profileUrl', $auth_user->username),
                        'title' => $msg,
                        'description' => $msg
                    ]);
                }
                if(Followers::emailNotificationSetting($user_profile->id,"follow_profile")) {
                    //send email
                    $body = [
                        'msg'=>$msg,
                        'url'=> route('profileUrl',$auth_user->username),
                        'btn_text'=> " View Profile ",
                        'subject' => "Follow Request Accepted",
                        'module' => "Follower",
                        //'send_to' => "muhammadrao1988@gmail.com",
                        'send_to' => $user_profile->email,
                    ];
                    dispatch(new SendEmailJob($body))->onQueue('default');
                }

                return new JsonResponse(['status'=>'success','action'=>'redirect','url'=>$redirect],200);

            }


        }

    }
}
