<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request,$id)
    {
        $product=Product::with('photos')->where('id',$id)->first();
        $oldCart=Session::has('cart') ? Session::get('cart') : null;
//        dd($oldCart);
        $cart=new Cart($oldCart);
        $cart->add($product,$product->id);
        $request->session()->put('cart',$cart);
        return response()->json($request->session()->get('cart'),200);
    }

    public function removeItem(Request $request,$id)
    {
        $product=Product::findOrFail($id);
//        $oldCart=Session::has('cart') ? Session::get('cart')->items :null;
        $oldCart=$request->session()->get('cart');
        $cart=new Cart($oldCart);
        $cart->remove($product,$product->id);
        $request->session()->put('cart',$cart);
        return response()->json($request->session()->get('cart'),200);
    }
}

