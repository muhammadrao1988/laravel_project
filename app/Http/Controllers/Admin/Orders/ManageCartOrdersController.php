<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Helpers\Common;
use App\Http\Controllers\BaseController;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Order;
use App\Models\OrderLog;
use PDO;

class ManageCartOrdersController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */

    public $module = "CartOrders";

    protected $mainViewFolder = 'admin.order.cart.';

    public $permissions = Array(
        "index" => "update",
        "show" => "update",
        "create" => "update",
        "update" => "update",
        "edit" => "update",
        "destroy" => "update",
    );

    public function index(Request $request)
    {
        if($request->ajax()){
        return  Datatables::of(Order::getListCartOrders())
                    ->addColumn('action', function ($data){
                        return Order::actionButtonsCart($data);
                    })->addColumn('gifter', function ($data){
                        if(!empty($data->fromUser->displayName)) {
                            return $data->fromUser->displayName;
                        }else{
                            return '-';
                        }
                    })->addColumn('giftee', function ($data){
                        return $data->toUser->displayName;
                    })->addColumn('total_amount', function ($data){
                        return "$".Common::numberFormat($data->total_amount);
                    })->editColumn('status', function ($data){
                        if($data->status=="Pending" || $data->status=="Rejected" || $data->status=="Returned"){
                            return '<span class="btn btn-danger btn-sm">'.$data->status.'</span>';
                        }else if($data->status=="Order Processing"){
                            return '<span class="btn btn-info btn-sm">'.$data->status.'</span>';
                        }else if($data->status=="Shipping"){
                            return '<span class="btn btn-warning btn-sm">'.$data->status.'</span>';
                        }
                        return '<span class="btn btn-success btn-sm">'.$data->status.'</span>';
                    })->addColumn('return_status', function ($data){
                            if($data->return_status==1 ){
                                return '<span class="btn btn-danger btn-sm">Yes</span>';
                            }else{
                                return '<span class="btn btn-success btn-sm">No</span>';
                            }
                    })->editColumn('order_num', function ($data){
                        if(empty($data->order_num)){
                            return "N/A";
                        }else{
                            return $data->order_num;
                        }
                    })->editColumn('updated_at', function ($data){
                        return \Common::CTL($data->updated_at);
                    })
                    ->rawColumns(['action','status','return_status'])->make(true);

        }
        return view($this->mainViewFolder . "index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $model = Order::with(['fromUser','toUser','orderItems','orderItems.orderItemStatusHistory','billingInfo','orderStatusHistory'])->where('order_type','Cart')->where('id',$id)->first();
        if(empty($model)){
            $request->session()->flash('warning', 'No record found');
            return redirect(route('cart_orders.index'));
        }
        $breadcrumb_route_name = "Cart Orders";
        $breadcrumb_route = route('cart_orders.index');
        return view($this->mainViewFolder.'show', compact('model','breadcrumb_route','breadcrumb_route_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Order::with('customer')->where('id',$id)->first();
        return view($this->mainViewFolder.'form', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $order = Order::with('customer.user')->where('id',$id)->first();
        if($request->status != $order->status){
            OrderLog::create([
                'order_id'=>$id,
                'old_status'=>$order->status,
                'new_status'=>$request->status,
            ]);
            if($order->recieve_purchase_status == 1){
                if(!empty($order->customer->user)){
                    // notification to this user
                }
            }
        }
        $order->customer->update(['name'=>$request->customer]);
        $order->update(['giftee_username'=>$request->giftee_username,'total_amount'=>$request->total_amount,'status'=>$request->status,'']);

        return redirect()->route('orders.index')->with('success','Order Updated Successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {

    }

    public function cancelledOrder(Request $request){
        if(!empty($request->accpeted_status) && !empty($request->id)) {
            $accepted_status = $request->accpeted_status;

            if(strtolower($accepted_status)!="cancelled"){
                return new JsonResponse(['errors' => ['general' => ['Only cancelled status is allowed.']]], 422);
            }

            $id = $request->id;
            $step = $request->step;

            $gift_offer = OrderItem::with(['order','order.fromUser','order.toUser','order.billingInfo'])
                ->where('id','=',$id)->first();
            if(empty($gift_offer)){
                return new JsonResponse(['errors' => ['general' => ['Wishlist item not found']]], 422);
            }
            if($gift_offer->order->order_type!="Cart"){
                return new JsonResponse(['errors' => ['general' => ['Order not found']]], 422);
            }
/*
            if(strtolower($gift_offer->item_status)=="shipped" || strtolower($gift_offer->item_status)=="delivered" || strtolower($gift_offer->item_status)=="cancelled" || strtolower($gift_offer->item_status)=="declined"){
                return new JsonResponse(['errors' => ['general' => ['You are not able to marked as cancelled']]], 422);
            }*/
            $auth_user = $gift_offer->order->toUser;
            $cancelled_order = Order::cartItemCancelled($request,$auth_user,$gift_offer,$step,false);
            if($cancelled_order['status']==true){
                session()->flash('success',"order updated successfully.");
                return new JsonResponse(['status'=>'success'],200);
            }else{
                return new JsonResponse(['errors' => $cancelled_order['errors']], 422);

            }
        }else{
            return new JsonResponse(['errors' => ['general' => ['Status or Id of wishlist is missing']]], 422);
        }

    }

    public function manageCartItemStatus(Request $request){

        $statusResponse = Order::cartItemOtherStatus($request,true,false);
        if($statusResponse['status']==true){
            session()->flash('success',"order updated successfully.");
            return new JsonResponse(['status'=>'success','action'=>'redirect'],200);
        }else{
            return new JsonResponse(['errors' => $statusResponse['errors']], 422);

        }

    }

    public function manageCartItemReturned(Request $request){

        $statusResponse = Order::cartItemReturnManage($request,true,false);
        if($statusResponse['status']==true){
            session()->flash('success',"Response send successfully.");
            return new JsonResponse(['status'=>'success','action'=>'redirect'],200);
        }else{
            return new JsonResponse(['errors' => $statusResponse['errors']], 422);

        }

    }

    public function log(Request $request,$id){
        $orderLogs = OrderLog::where('order_id',$id)->get();

        if($request->ajax()){
            return  Datatables::of($orderLogs)
            ->editColumn('updated_at', function ($data){
                return \Carbon\Carbon::parse($data->updated_at)->format('d/M/Y H:i:s');
            })
                ->rawColumns([])->make(true);

        }
        return view($this->mainViewFolder . "order-logs",compact('id'));
    }

    public function updateOrderNum(Request $request){
        if(!empty($request->order_num) && !empty($request->order_id)) {
            Order::where('id',$request->order_id)
                ->update(
                ['order_num'=>$request->order_num]
            );
            return new JsonResponse(['status'=>'success'],200);

        }else{
            return new JsonResponse(['errors' => ['general' => ['Order num or Id is missing']]], 422);
        }

    }
}
