<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    public function addCoupon(Request $request)
    {
//        $check=Auth::user()->whereHas('coupons',function ($q)use($request){
//            $q->where('code',$request->code);
//        })->exists();
        $check=User::where('id',$request->user)->whereHas('coupons',function ($q)use($request){
            $q->where('code',$request->code);
        })->exists();
        if(!$check){
            $coupon=Coupon::where('code',$request->code)->first();
            $cart=Session::has('cart') ? Session::get('cart') : null;
//            dd($cart);
            $cart=new Cart($cart);
            $cart->addCoupon($coupon);
            $request->session()->put('cart',$cart);
//            $user=Auth::user();
            $user=User::findOrFail($request->user);
            $user->coupons()->attach($coupon->id);
            $user->save();
            return response()->json($request->session()->get('cart'),200);
        }else{
            return response()->json(['status'=>'error','message'=>'this coupon previously used by you. you cant use it again.'],200);

        }
    }
}
