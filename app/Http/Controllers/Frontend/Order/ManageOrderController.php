<?php

namespace App\Http\Controllers\Frontend\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\OrderStatusTimeline;
use App\Models\ReturnTransaction;
use Illuminate\Http\Request;

class ManageOrderController extends Controller
{
    public $module = "Order";

    protected $mainViewFolder = 'frontend.order.';

    protected $_user;

    public function index(Request $request)
    {
        $show_entries_array = [25,50,100];
        $show_entry = $request->input('show_entry');
        $search = $request->input('search');
        if(!in_array($show_entry,$show_entries_array)){
            $show_entry = 5;
        }
        $auth_user = auth()->guard('web')->user();
        $records =  Order::join('users','users.id','=','orders.to_user')->where('user_id','=',$auth_user->id);
        if(!empty($search)){
            $records = $records->where(function ($query) use ($search){
                $query->orWhere('users.displayName','LIKE','%'.$search.'%')
                    ->orWhere('orders.id','LIKE','%'.$search.'%')
                    ->orWhere('orders.status','LIKE','%'.$search.'%')
                    ->orWhere('orders.created_at','LIKE','%'.$search.'%')
                    ->orWhere('orders.total_amount','LIKE','%'.$search.'%');
            });
        }
        $records= $records->select('orders.id','orders.status','orders.created_at','users.displayName','users.username','orders.total_amount')
            ->orderBy('orders.id','desc')->paginate($show_entry);
        $last_page = $records->lastPage();
        if($request->ajax()){
            return view($this->mainViewFolder . 'order_listing_pagination',compact('records','last_page'))->render();
        }
        return view($this->mainViewFolder . 'index',compact('records','last_page','show_entry'));
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $auth_user = auth()->guard('web')->user();
        $model = Order::with(['toUser','orderItems','orderItems.wishlistItems','billingInfo','orderStatusHistory','orderItems.orderItemStatusHistory'])->where('user_id',$auth_user->id)->where('id',$id)->first();

        if(empty($model)){
            abort(404);
        }
        $return_transaction = [];
        if(strtolower($model->order_type) == "giftoffer" && strtolower($model->status)=="declined" || strtolower($model->status)=="cancelled"){
            $return_transaction = ReturnTransaction::where('order_id','=',$model->id)->first();
        }
        $orderStatusHistory = [];
        if($model->order_type=="Contribution"){
            $orderStatusHistory = OrderStatusTimeline::where('type','=','item')->where('order_id','=',$model->wishlist_item_id)->get();
        }

        return view($this->mainViewFolder . 'items', compact('model','return_transaction','orderStatusHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function cancelOrder($id){

        $order = Order::where('id',$id)->first();
        if($order->status == 'Order Shipped' || $order->status == 'Order Delivered')
        {
            session()->flash('error','This Order cannot be cancelled now, It has been shipped.');
            return redirect()->back();
        }
        $stripe = new \Stripe\StripeClient(config('app.STRIPE_SECRET'));
        $stripe->refunds->create(
        ['payment_intent' => $order->payment_id]
        );
        OrderItem::where('order_id',$order->id)->delete();
        $order->delete();
        session()->flash('success','Order has been cancelled.');
        return redirect()->back();
    }

}
