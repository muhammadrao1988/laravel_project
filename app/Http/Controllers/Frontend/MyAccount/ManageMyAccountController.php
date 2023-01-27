<?php

namespace App\Http\Controllers\Frontend\MyAccount;

use App\ContactUs;
use App\Helpers\Common;
use App\HomePage;
use App\Http\Controllers\Controller;
use App\Models\Followers;
use App\Models\GifteeWishList;
use App\Models\UserCreditHistory;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;
use App\TraitLibraries\ResponseWithHttpStatus;
use PhpOffice\PhpSpreadsheet\Calculation\Web;
use Session;

class ManageMyAccountController extends Controller
{
    use ResponseWithHttpStatus;
    protected $mainViewFolder = 'frontend.myaccount.';

    //
    public function index(Request $request)
    {

        $data = [];
        $auth_user = auth()->guard('web')->user();
        $model = Website::find($auth_user->id);
        $notification_setting = $model->notificationSettings()->get()->pluck('setting_name')->toArray();
        $email_notification_setting = $model->emailNotificationSettings()->get()->pluck('setting_name')->toArray();

        if($request->input('submit_profile')){
            $validatedData = Validator::make($request->all(),Website::validationRules($auth_user->id),Website::validationMsgs(),['name'=>'Full name','zip'=>'Zipcode']);
            if($validatedData->fails()){
                return Redirect::back()->withErrors($validatedData);
            }

            //$validatedData = (Website::validationRules($auth_user->id),Website::validationMsgs(),['name'=>'Full name','zip'=>'Zipcode']);
            //dd($validatedData);
            if(empty($request->input('privacy_setting'))){
                $request->merge(['privacy_setting'=>0]);
            }
            $website = new Website();
            $website->userCreateOrUpdate($auth_user->id,$request);

            if($request->input('active')==-1){
                Auth::guard('web')->logout();
                $request->session()->flash('success', "Account has been deactivated.");
                return redirect()->route('login_page');

            }else{
                $request->session()->flash('success', "Account updated successfully.");
            }

            $redirect = 'myaccount';
            return redirect()->route($redirect);

        }

        //dd($data);
        return view($this->mainViewFolder . 'myaccount',compact('model','notification_setting','email_notification_setting'));
    }

    public function profileData(Request $request,$username){

        session()->put('giftee_username', $username);

        $profile_detail = Website::where('username','=',$username)->where('active','=',1)->first();
        if(empty($profile_detail)){
            abort(404);
        }
        $auth_user = \auth()->guard('web')->user();
        $same_profile = false;
        $follower_detail = false;
        $respond = false;
        $following_detail = false;
        if($auth_user){
            if(empty($profile_detail)){
                $request->session()->flash('error', 'User not found.');
                return redirect(route('profileUrl',$auth_user->username));
            }
            if($profile_detail->id==$auth_user->id){
                $same_profile = true;
            }else{
                $follower_detail = Followers::where('user_id','=',$profile_detail->id)->where('follower_id',$auth_user->id)->first();
                $following_detail = Followers::where('user_id','=',$auth_user->id)->where('follower_id',$profile_detail->id)->where('following_status','=','Pending')->first();
                if(!empty($following_detail->id)){
                    $respond = true;
                    //dd("ddd");
                }
            }
        }else{
            if(empty($profile_detail)){
                $request->session()->flash('error', 'User not found.');
                return redirect(route('login_page'));
            }

        }
        $show_wishlist = true;
        if($profile_detail->privacy_setting==1 && $follower_detail==false && $same_profile==false){
            $show_wishlist = false;
        }else if(!empty($follower_detail) && $follower_detail->following_status!="Accepted"){
            $show_wishlist = false;
        }
        $wishLists = [];
        $last_page = 0;
        if($show_wishlist) {
            $wishLists = GifteeWishList::with(['wishListItems'])->where('user_id', '=', $profile_detail->id)->paginate(config('constants.PER_PAGE_LIMIT'));
            $last_page = $wishLists->lastPage();
            if ($request->ajax()) {
                return view('frontend.wishlist.wishlist_pagination', compact('wishLists','same_profile'))->render();
            }
        }
        $className = "";
        return view($this->mainViewFolder . 'profile',compact('respond','following_detail','auth_user','same_profile','profile_detail','wishLists','className','follower_detail','show_wishlist','last_page'));

    }

    public function changeProfileBanner(Request $request)
    {
        $validatedData = $request->validate(Website::validationRulesBanner(),Website::validationMsgBanner());

        try {
            DB::beginTransaction();
            $user_data = auth()->guard('web')->user();
            if($request->input('type')=="banner") {
                $imagePath = $request->file('banner');
                $ext = $imagePath->getClientOriginalExtension();
                //$imageName = $imagePath->getClientOriginalName();
                $imageName = Common::encrypt_decrypt($user_data->id . "_" . date('YmdHis'), "encrypt") . "." . $ext;
                $request->file('banner')->storeAs('uploads/banner', $imageName, 'public');
                Website::where('id', $user_data->id)->update(['banner' => $imageName]);
                session()->flash('success', 'Profile banner updated successfully.');
                DB::commit();
            }else{
                $imagePath = $request->file('profile_img');
                $ext = $imagePath->getClientOriginalExtension();
                //$imageName = $imagePath->getClientOriginalName();
                $imageName = Common::encrypt_decrypt($user_data->id . "_" . date('YmdHis'), "encrypt") . "." . $ext;
                $request->file('profile_img')->storeAs('uploads/profile_picture', $imageName, 'public');
                Website::where('id', $user_data->id)->update(['profile_image' => $imageName]);
                session()->flash('success', 'Profile image updated successfully.');
                DB::commit();
            }

            if($request->wantsJson()){
                return new JsonResponse(['status'=>'success','action'=>'redirect','url'=>route('profileUrl',$user_data->username)],200);
            }else{
                $route =route('myaccount');
                return redirect($route."?show=1");
            }

        }catch (\Exception $exception){
            DB::rollBack();
            if($request->wantsJson()){
                return new JsonResponse(['errors' => ['exception' => [$exception->getMessage()]]], 422);
            }else{
                session()->flash('error', $exception->getMessage());
                return redirect()->route("profileUrl",$user_data->username);
            }
        }
    }

    public function removeProfileBanner(Request $request)
    {
        try {
            DB::beginTransaction();
            $user_data = auth()->guard('web')->user();
            if($request->input('type')=="banner") {
                Website::where('id', $user_data->id)->update(['banner' => '']);
                session()->flash('success', 'Profile banner removed successfully.');
                DB::commit();
            }

            if($request->wantsJson()){
                return new JsonResponse(['status'=>'success','action'=>'redirect','url'=>route('profileUrl',$user_data->username)],200);
            }else{
                $route =route('myaccount');
                return redirect($route."?show=1");
            }

        }catch (\Exception $exception){
            DB::rollBack();
            if($request->wantsJson()){
                return new JsonResponse(['errors' => ['exception' => [$exception->getMessage()]]], 422);
            }else{
                session()->flash('error', $exception->getMessage());
                return redirect()->route("profileUrl",$user_data->username);
            }
        }
    }

    public function removeProfileImage(Request $request)
    {
        try {
            DB::beginTransaction();
            $user_data = auth()->guard('web')->user();
            if($request->input('type')=="profile") {
                Website::where('id', $user_data->id)->update(['profile_image' => null]);
                session()->flash('success', 'Profile image removed successfully.');
                DB::commit();
            }

            if($request->wantsJson()){
                return new JsonResponse(['status'=>'success','action'=>'redirect','url'=>route('profileUrl',$user_data->username)],200);
            }else{
                return redirect()->route("profileUrl",$user_data->username);
            }

        }catch (\Exception $exception){
            DB::rollBack();
            if($request->wantsJson()){
                return new JsonResponse(['errors' => ['exception' => [$exception->getMessage()]]], 422);
            }else{
                session()->flash('error', $exception->getMessage());
                return redirect()->route("profileUrl",$user_data->username);
            }
        }
    }

    public function credits(Request $request)
    {

        $auth_user = auth()->guard('web')->user();
        $credits = $auth_user->credit;
        $records =  UserCreditHistory::with(['order','order_item'])->where('user_id','=',$auth_user->id)->orderBy('order_id','DESC')->paginate(10);

        $last_page = $records->lastPage();
        if($request->ajax()){
            return view($this->mainViewFolder . 'credit_listing_pagination',compact('records','last_page','auth_user'))->render();
        }
        return view($this->mainViewFolder . 'credit',compact('records','last_page','credits','auth_user'));
    }

    public function storeUserImage(Request $request){
        return $request->hasFile('image_profile');
    }

}
