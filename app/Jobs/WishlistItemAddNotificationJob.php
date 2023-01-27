<?php

namespace App\Jobs;

use App\Mail\FollowOperation;
use App\Models\EmailLog;
use App\Models\Followers;
use App\Models\Notification;
use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WishlistItemAddNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $wishlist_data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($wishlist_data)
    {

        $this->wishlist_data = $wishlist_data;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user_id = $this->wishlist_data->user_id;
        $user_detail = Website::find($user_id);

        //fetch followers of user
        $followers = Followers::with(['follower_detail'])->where('user_id','=',$user_id)->where('following_status','=',"Accepted")->get();
        foreach ($followers as $follower){
            $url = route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($this->wishlist_data->giftee_wishlist_id));
            $url = $url."?notify=1";

            if(Followers::notificationSetting($follower->follower_id,"add_gift_wishlist")) {
                //notification
                Notification::create([
                    'model_id' => $this->wishlist_data->id,
                    'model' => "GifteeWishListItem",
                    'user_id' => $follower->follower_id,
                    'from_user_id' => $user_id,
                    'url' => $url,
                    'title' => $user_detail->username." has added a new gift to their wishlist.",
                    'description' => $user_detail->username." has added a new gift to their wishlist."
                ]);
            }
            if(Followers::emailNotificationSetting($follower->follower_id,"add_gift_wishlist")) {
               /* \Log::channel('cron')->info("started");
                \Log::channel('cron')->info($follower->follower_detail->email);
                \Log::channel('cron')->info("ended");*/
                //send email
                $msg = $user_detail->username." has added a new gift ".$this->wishlist_data->gift_name." to their wishlist.";
                $body = [
                    'msg'=>$msg,
                    'url'=> $url,
                    'btn_text'=>"View Item",
                    'subject' => $user_detail->username." added a new wishlist gift",
                    'module' => "Wishlist",
                    //'send_to' => "muhammadrao1988@gmail.com",
                    'send_to' => $follower->follower_detail->email,
                ];
                dispatch(new SendEmailJob($body))->onQueue('default');
            }
        }
    }
}
