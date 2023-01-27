<?php

namespace App\Http\Controllers\Frontend\GiftIdea;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\GifteeWishList;
use App\Models\GiftIdea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\InfiniteScroll;

class ManageGiftIdeaController extends Controller
{

    protected $mainViewFolder = 'frontend.giftidea.';

    public function index()
    {

         return view($this->mainViewFolder . 'index');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function addToCart($id)
    {
        if(!Website::canPurchaseService()){
            session()->flash('error', 'You are not allowed to do this operation. You have already been subscribed.');
            return redirect(route('services'));
        }

        if(!Website::canBuyMultipleService()){
            session()->flash('error', 'You can add only one service at a time.');
            return redirect(route('services'));
        }

        $product = Service::where('sonar_id',$id)->first();
        if(empty($product)){
            session()->flash('error', 'Invalid service selected');
        }

        $cart = session()->get('cart', []);
        $detail =
        [
            "name" => $product->name,
            "quantity" => 1,
            "amount" => $product->amount,
            "upload_speed_kilobits_per_second" => $product->upload_speed_kilobits_per_second,
            "download_speed_kilobits_per_second" => $product->download_speed_kilobits_per_second,
            "billing_frequency" => $product->billing_frequency,
            "type" => $product->type,
            "application" => $product->application,

        ];
        $cart[$id] = $detail;

        if(isset($cart[$id])) {
            //$cart[$id]['quantity']++;
        } else {

        }

        session()->put('cart', $cart);
        return redirect(route('cart'))->with('success', 'Product added to cart successfully!');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        }
    }

    public function showGiftIdeas(Request $request){

        $categories = Category::with('giftideas')->where('active','=',1)->get();
        $auth_user = auth()->user();
        if(!empty($auth_user)) {
            $wishlist = GifteeWishList::where('user_id', auth()->user()->id)->orderBy('id','Desc')->limit(20)->get();
        }else{
            $wishlist = [];

        }
        $show_selected_category = false;
        if(\request()->get('category')=="for-her" || \request()->get('category')=="for-him" ||
            \request()->get('category')=="for-techies" || \request()->get('category')=="for-children"
        ){
            $show_selected_category = \request()->get('category');
        }

        return view($this->mainViewFolder . 'index',compact('categories','wishlist','show_selected_category'));
    }

    public function getgiftideas(Request $request){

        if($request->ajax()){
            $data =  GiftIdea::where('category_id',$request->category_id)->where('active',1)->orderBy('id','desc')->paginate(config('constants.PER_PAGE_LIMIT'));
            $last_page = $data->lastPage();

            if($data->isEmpty()){
                $view = '';
            }else{
                $view = view($this->mainViewFolder .'gift_idea_pagination',compact('data'))->render();
            }
            return response()->json(['html'=>$view,'last_page'=>$last_page]);
        }
        return view($this->mainViewFolder . 'index');
    }

    public function gethowitworks(){
        return view('frontend.howitworks.index');
    }
}
