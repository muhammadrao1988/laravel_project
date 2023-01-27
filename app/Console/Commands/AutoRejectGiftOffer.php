<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Website;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AutoRejectGiftOffer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'giftoffer:rejected';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Rejected Gift Offer After 30 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $offers = Order::where('order_type','=','GiftOffer')->where('status','=','Pending')
            ->where('created_at', '<=', now()->subDays(30)->endOfDay())->get();
        $request = request()->merge([
                'accpeted_status'=>'Cancelled',
                'gifter_message'=> 'Offer has been cancelled automatically, due to inactivity of 30 days from giftee'
            ]
        );
        \Log::channel('cron')->info("Working....");

        foreach ($offers as $offer){
            $gift_offer = OrderItem::with(['order','order.fromUser','order.billingInfo'])
                ->where('order_id','=',$offer->id)->first();
            $auth_user = Website::find($offer->to_user);

            $result = Order::giftOfferAcceptedRejectedCancelled($request,$auth_user,$gift_offer,2,true);

            \Log::channel('cron')->info("Auto Reject GO Started");
            if($result['status']==true){
                \Log::channel('cron')->info("GO Success #".$offer->id);
            }else{
                \Log::channel('cron')->info("GO Error #".$offer->id.". Error: ".json_encode($result));
            }
            \Log::channel('cron')->info("Auto Reject GO END");

        }

    }
}
