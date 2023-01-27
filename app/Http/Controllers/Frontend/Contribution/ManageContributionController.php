<?php

namespace App\Http\Controllers\Frontend\Contribution;

use App\Http\Controllers\Controller;
use App\Models\BillingInfo;
use App\Models\ExceptionTransaction;
use App\Models\GifteeWishListItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusTimeline;
use App\Models\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Common;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;

class ManageContributionController extends Controller
{
    public $module = "Contribution";

    protected $_user;

    public function index(Request $request)
    {
        if ($request->ajax() && $request->input('use_credit')) {
            $auth_user = auth()->guard('web')->user();
            if (!empty($auth_user) && $auth_user->credit > 0) {
                if ($request->input('use_credit') == "yes") {
                    session()->put('use_credit_contributed', 1);
                } else {
                    session()->forget('use_credit_contributed');
                }
                return response()->json(['success' => true], 200);
            } else {
                return response()->json(['success' => false], 403);
            }
        }


        if (count((array)session('contributed_cart')) == 0) {
            session()->flash('info', 'Your cart is empty');
            return redirect(route('home'));
        }
        $auth_user = auth()->guard('web')->user();
        $use_credit = false;
        $current_credit = 0;
        if (!empty(session('use_credit_contributed'))) {
            $use_credit = true;
            $current_credit = !empty($auth_user->id) ? $auth_user->credit : 0;
        }


        $billing_detail = new \stdClass();

        $billing_detail->first_name = '';
        $billing_detail->last_name = '';
        $billing_detail->email = '';
        $billing_detail->address = '';
        $billing_detail->country = '';
        $billing_detail->city = '';
        $billing_detail->state = '';
        $billing_detail->postal_code = '';

        if (!empty($auth_user)) {
            $name = explode(" ", $auth_user->name);
            $billing_detail->first_name = !empty($name[0]) ? $name[0] : "";
            $billing_detail->last_name = !empty($name[1]) ? $name[1] : "";
            $billing_detail->email = $auth_user->email;
            $billing_detail->address = $auth_user->address;
            $billing_detail->country = $auth_user->country;
            $billing_detail->city = $auth_user->city;
            $billing_detail->state = $auth_user->state;
            $billing_detail->postal_code = $auth_user->zip;

        }
        $contributed_amount = 0;
        $all_item_total = 0;
        $previous_collection = 0;
        $cart_count = 0;


        $page = "checkout";
        $contributed_checkout = true;
        $field_name_checkout = "contributed_checkout";

        return view('frontend.cart.checkout', compact('field_name_checkout','contributed_checkout','billing_detail', 'previous_collection', 'contributed_amount', 'all_item_total', 'use_credit', 'cart_count',  'page','current_credit'));

    }


    public function addToCart(Request $request)
    {
        $rules = [
            'wishlist_item_id' => 'required',
            'amount' => 'required|numeric',
        ];

        $customMessages = [
            'wishlist_item_id.required' => 'Please select wishlist item',
            'amount.required' => 'Contributed amount is required',
            'amount.numeric' => 'Only numeric value is require'
        ];

        $request->validate($rules, $customMessages);
        $id = $request->wishlist_item_id;
        $product = GifteeWishListItem::where('id', $id)->first();
        if (empty($product)) {
            return new JsonResponse(['errors' => ['product_detail' =>  ["Item does not exist."]]], 422);
        }

        if ($product->accept_donation != 1) {
            return new JsonResponse(['errors' => ['product_detail' =>  ["Only contributed item is allowed to perform this operation."]]], 422);
        }

        $item_amount = ($product->price * $product->quantity) + $product->shipping_cost + $product->service_fee + $product->tax_rate;
        $collected_amount = $product->collected_amount;
        $remaining_amount = \Common::decimalPlace($item_amount - $collected_amount,2);

        if ($collected_amount >= $item_amount) {
            return new JsonResponse(['errors' => ['product_detail' =>  ["The selected item contribution has already been fulfilled"]]], 422);
        }

        if($remaining_amount < $request->amount){
            return new JsonResponse(['errors' => ['product_detail' =>  ["The amount must be less than or equal to $".$remaining_amount]]], 422);
        }

        if($request->amount < 1){
            return new JsonResponse(['errors' => ['product_detail' =>  ["The minimum amount should be 1$ "]]], 422);
        }

        if($remaining_amount - $request->amount > 0 && ($remaining_amount - $request->amount) < 1){
            return new JsonResponse(['errors' => ['product_detail' =>  ["Please enter total remaining amount"]]], 422);
        }

        session()->forget('contributed_cart');
        session()->save();
        $cart = session()->get('contributed_cart', []);
        $detail =
            [
                "contributed_amount"=>$request->amount,
            ];
        $cart[$id] = $detail;

        session()->put('contributed_cart', $cart);
        return new JsonResponse(['status' => 'success'], 200);

    }

}
