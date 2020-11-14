<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Stripe;
use App\User;
use App\PaymentHistory;

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

        $user_id = Auth::user()->id;
        //save payment history
        $payment = new PaymentHistory;
        $payment->user_id = $user_id;
        $payment->payment_gateway = 'stripe';
        $payment->amount = 10.00;
        $payment->status = ($charge_result && ($charge_result->status == 'succeeded'))?'success':'failed';
        $payment->payment_datetime = date('Y-m-d H:i:s');
        $payment->payment_log = json_encode($charge_result);
        $payment->save();

        if($charge_result && ($charge_result->status == 'succeeded')){


            $user = User::find($user_id);
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
        $data = [];
        $data['year'] = $request->year?$request->year:2020;
        $data['month'] = $request->month?$request->month:11;
        $data['payment_history'] = PaymentHistory::whereMonth('payment_datetime', $data['month'])->whereYear('payment_datetime', $data['year'])->get();
        return view('payment_history',$data);
    }
}
