<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Stripe;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $charge_result = Stripe\Charge::create ([
            "amount" => 10 * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Account Activation"
        ]);

        if($charge_result && ($charge_result->status == 'succeeded')){
            $user = User::find(Auth::user()->id);
            $user->status = 1;
            if($user->save()){
                Session::flash('status', 'Account activated successfully');
            }else{
                Session::flash('error', 'Account activation failed!');
            }
        }else{
            Session::flash('error', 'Payment Failed!');
        }

        return back();
    }

    public function PaymentHistory(Request $request){
        return view('payment_history');
    }
}
