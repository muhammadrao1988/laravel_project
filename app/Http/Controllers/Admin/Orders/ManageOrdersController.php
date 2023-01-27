<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Order;
use App\Models\OrderLog;
use PDO;

class ManageOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */

    public $module = "Orders";

    protected $mainViewFolder = 'admin.order.';

    public $permissions = Array(
        "index" => "read",
        "show" => "read",
        "create" => "create",
        "update" => "update",
        "edit" => "update",
        "destroy" => "delete",
    );

    public function index(Request $request)
    {
        $orders = Order::orderBy('created_at','desc')->get();
        if($request->ajax()){
        return  Datatables::of($orders)
                    ->addColumn('action', function ($data){
                        return Order::actionButtons($data);
                    })->addColumn('customer_name', function ($data){
                        return $data->customer->name;
                    })->addColumn('giftee', function ($data){
                        return $data->giftee_username;
                    })->editColumn('status', function ($data){
                        return '<span class="btn btn-success btn-sm">'.$data->status.'</span>';
                    })->editColumn('created_at', function ($data){
                        return \Carbon\Carbon::parse($data->created_at)->format('d/M/Y H:i:s');
                    })->editColumn('updated_at', function ($data){
                        return \Carbon\Carbon::parse($data->updated_at)->format('d/M/Y H:i:s');
                    })->addColumn('logs', function ($data){
                        return '<a href="'.route("order-logs","$data->id").'">Order Logs</a>';
                    })
                    ->rawColumns(['action','customer_name','giftee','status','logs'])->make(true);

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
}
