<?php

namespace App\Http\Controllers\Frontend\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\TraitLibraries\ResponseWithHttpStatus;

class ManageNotificationController extends Controller
{
    use ResponseWithHttpStatus;
    protected $mainViewFolder = 'frontend.notifications.';

    public function index(Request $request){

        $auth_user = \auth()->guard('web')->user();
        $all_notification = "active";
        $unread_notification = "";
        $notifications = Notification::latest()->leftJoin('users','users.id','=','notifications.from_user_id')
                        ->select('notifications.*','users.profile_image')
                        ->where('notifications.user_id','=',$auth_user->id);
        if($request->input('show_unread')==1){
            $notifications = $notifications->whereNull('read_at');
            $unread_notification = "active";
            $all_notification = "";
        }

        $notifications = $notifications->paginate(config('constants.PER_PAGE_LIMIT'));
        $last_page = $notifications->lastPage();
        if ($request->ajax()) {

            return view($this->mainViewFolder .'notification_pagination', compact('notifications','auth_user'))->render();
            //return response()->json(['html'=>$view]);
        }

        return view($this->mainViewFolder . 'notification',compact('notifications','last_page','unread_notification','all_notification','auth_user'));

    }

    public function read_notification(Request $request){
        if($request->input('id')){

            if($request->input('className')=="read_all_notifications"){
                Notification::whereNull('read_at')->where('user_id', '=', auth('web')->user()->id)->update([
                    'read_at' => date('Y-m-d H:i:s')
                ]);
            }else {
                Notification::where('id', '=', $request->input('id'))->whereNull('read_at')->where('user_id', '=', auth('web')->user()->id)->update([
                    'read_at' => date('Y-m-d H:i:s')
                ]);
            }

            return new JsonResponse(['status'=>'success'],200);

        }

    }

}
