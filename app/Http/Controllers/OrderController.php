<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $order=Order::paginate(20);
        return response()->json($order,200);
    }

    public function verify(Request $request,$status)
    {
        $cart=$request->session()->get('cart');

        if (!$cart){
            return response()->json([
                'data'=>[
                    'message'=>'your cart is empty',
                    'status'=>'error'
                ]
            ],200);
        }else{
//            return response()->json($cart);

//            $payment=new Payment($cart->totalPrice);
//            $result=$payment->rest();
//            dd($result);


            $productId=[];
            foreach ($cart->items as $product){
                $productId[$product['item']->id]=['qty'=>$product['qty']];
            }
            $order=new Order();
            $order->amount=$cart->totalPrice;
//            $order->user_id=Auth::id();
            $order->user_id=12;
            $order->status=$status;
            $order->save();

            $order->products()->attach($productId);


        }

    }

    public function orderDetails($id)
    {
        $order=Order::with('user.addresses.city','user.addresses.province','products.photos')->where('id',$id)->first();
        return response()->json($order,200);

    }

}
