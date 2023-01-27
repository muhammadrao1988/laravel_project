<?php

namespace App\Http\Controllers\Frontend\Pages;

use App\BusinessPersonalLoan;
use App\ContactUs;
use App\Helpers\Common;
use App\HomePage;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\FollowOperation;
use App\Models\Address;
use App\Models\States;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;

class ManagePagesController extends Controller
{
    protected $mainViewFolder = 'frontend.pages.';
    //
    public function index()
    {
       /* $msg = "Dear Muhammad,";
        $custom_html = "<table><tr><td>Name</td><td>Fer</td></tr></table>";
        $custom_html = "<p>Thank you for your purchasing, we have received your payment. We'll let the lucky giftee know that their Prezziez are on the way!</p>";
        $custom_html.= "<p>Order ID: 22</p>";
        $custom_html.= "<p>Total Amount(USD):5</p>";
        $body = [
            'msg'=>"",
            'url'=> "",
            'btn_text'=>"",
            'subject' => "test email",
            'module' => "OrderItem",
            //'send_to' => "muhammadrao1988@gmail.com",
            'custom_html'=>$custom_html,
            'send_to' => "clicky_dev@yopmail.com",
        ];
        dispatch(new SendEmailJob($body))->onQueue('default');*/
        /*$states = Common::statesWithTax();
        foreach ($states as $key=>$value){
            States::updateOrCreate([
                'name'=>$key
            ],[
                'name'=>$key,
                'tax_rate'=>$value,
            ]);
        }*/

        /*$stripe = new \Stripe\StripeClient(.env('STRIPE_SECRET'));
        $token = $stripe->taxRates->all(['limit' => 3]);
        dd($token);*/

        /* $ch = curl_init("https://www.daraz.pk/products/-i247985910-s1465799894.html?spm=a2a0e.home.flashSale.2.60674937V97woZ");
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         $result = curl_exec($ch);
         curl_close($ch);

         $title = preg_match('!<title>(.*?)</title>!i', $result, $matches) ? $matches[1] : 'No title found';
         $description = preg_match('!<meta name="description" content="(.*?)">!i', $result, $matches) ? $matches[1] : 'No meta description found';
         $keywords = preg_match('!<meta name="keywords" content="(.*?)">!i', $result, $matches) ? $matches[1] : 'No meta keywords found';

         echo $title . '<br>';
         echo $description . '<br>';
         echo $keywords . '<br>';
         dd($result);*/
        //die("ddd");
       /* $body = [
            'name'=>"Muhammad Rao",
            'url_a'=>'https://www.bacancytechnology.com/',
            'url_b'=>'https://www.bacancytechnology.com/tutorials/laravel',
            'subject' => "Follow Request",
            'module' => "Follower",
            'send_to' => "muhammadrao1988@gmail.com"
        ];
        dispatch(new SendEmailJob($body))->onQueue('default');*/
        return view($this->mainViewFolder .'home');

    }


    public function staticPage(Request $request)
    {
        $route_name = $request->route()->getName();
        if($route_name=="about-us"){
            return view($this->mainViewFolder .'aboutus');
        }else if($route_name=="contact-us"){
            return view($this->mainViewFolder .'contactus');
        }else if($route_name=="faq"){
            return view($this->mainViewFolder .'faq');
        }else if($route_name=="privacy-policy"){
            return view($this->mainViewFolder .'privacypolicy');
        }else if($route_name=="terms-condition"){
            return view($this->mainViewFolder .'termsandcondition');
        }


    }


    public function ajaxCall(Request $request){
        if($request->input('contact_form')){
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'message' => 'required',
            ]);


            if ($validator->passes()) {
                $input['ip'] = $request->ip();
                $body = "<table>
                            <tr>
                                <td>Name: </td>
                                <td> ".$request->input('name')." </td>
                            </tr>
                            <tr>
                                <td>Email: </td>
                                <td> ".$request->input('email')." </td>
                            </tr>
                            <tr>
                                <td>Message: </td>
                                <td> ".$request->input('message')." </td>
                            </tr>
                            </table>";
                session()->flash('success', 'Your message has been send successfully.');
                Common::sendEmail(["admin@javelinbroadband.net"],"Support Form Query - Javelin Broadband",$body);
                return response()->json(['success'=>true]);
            }
            $error = $validator->errors()->first();

            return response()->json(['success'=>false,'message'=>$error]);

        }

        if($request->input('check_availability_form')){
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);


            if ($validator->passes()) {
                $input['ip'] = $request->ip();
                $body = "<table>
                            <tr>
                                <td>Email: </td>
                                <td> ".$request->input('email')." </td>
                            </tr>
                            </table>";
                session()->flash('success', 'Your message has been send successfully.');
                Common::sendEmail(["admin@javelinbroadband.net"],"Service Availability Request- Javelin Broadband",$body);
                return response()->json(['success'=>true]);
            }
            $error = $validator->errors()->first();

            return response()->json(['success'=>false,'message'=>$error]);

        }
    }
    public function getGiftees (Request $request){
        if(!empty($request->input('q'))) {
            $q = $request->input('q');
            $result = Website::where(function($query) use ($q) {
                $query->where('name', 'LIKE', '%'.$q.'%')
                    ->orWhere('displayName', 'LIKE', '%'.$q.'%')
                    ->orWhere('username', 'LIKE', '%'.$q.'%');
            })->limit(50)->get();

            $html = "";
            $no_record = false;
            $show_heading = true;
            foreach ($result as $profile_detail_search_data){
                $html.= view('frontend.search.search_list', compact('profile_detail_search_data','no_record','show_heading'))->render();
                $show_heading = false;
            }
            if($html==""){
                $profile_detail_search_data = false;
                $no_record = true;
                $html.= view('frontend.search.search_list', compact('profile_detail_search_data','no_record'))->render();

            }
            return response()->json(['status'=>'success','data'=>$html],200);



        }
        return response()->json(['errors' => ['exception' => ["no record found"]]], 403);
    }
}
