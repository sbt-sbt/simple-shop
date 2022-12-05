<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use function MongoDB\BSON\toJSON;

class Cart
{
    use HasFactory;
    public $items=null;
    public $totalQty=0;
    public $totalPrice=0;
    public $totalDiscount=0;
    public $couponDiscount=null;

    public function __construct($oldCart)
    {
        if ($oldCart){
            $this->items=$oldCart->items;
            $this->totalQty=$oldCart->totalQty;
            $this->totalPrice=$oldCart->totalPrice;
            $this->totalDiscount=$oldCart->totalDiscount;
        }

    }

    public function add($item,$id)
    {
        if ($item->discount){
            $storedItem=['qty'=>0,'price'=>$item->price-$item->discount,'item'=>$item];
        }else{
            $storedItem=['qty'=>0,'price'=>$item->price,'item'=>$item];
        }
        if ($this->items){
            if (array_key_exists($id,$this->items)){
                $storedItem=$this->items[$id];
            }
        }
        $storedItem['qty']++;
        if ($item->discount) {
            $storedItem['price']=($item->price-$item->discount)*$storedItem['qty'];
            $this->totalPrice+=($item->price-$item->discount);
            $this->totalDiscount+=$item->discount;
        }else{
            $storedItem['price']=$item->price*$storedItem['qty'];
            $this->totalPrice+=$item->price;
        }
        $this->items[$id]=$storedItem;
        $this->totalQty++;
    }

    public function remove($item,$id)
    {
        if ($this->items){
            if (array_key_exists($id,$this->items)){
                if ($item->discount){
                    $this->items[$id]['price']-=($item->price-$item->discount);
                    $this->totalPrice-=($item->price-$item->discount);
                    $this->totalDiscount-=$item->discount;
                }else{
                    $this->items[$id]['price']-=$item->price;
                    $this->totalPrice-=$item->price;
                }
                $this->totalQty--;
                if ($this->items[$id]['qty']>1){
                    $this->items[$id]['qty']--;
                }else{
                    unset($this->items[$id]);
                }
            }
        }
    }

    public function addCoupon($coupon)
    {
//        $couponData=['price'=>$coupon->price,'coupon'=>$coupon];
        $this->totalPrice-=$coupon->price;
        $this->couponDiscount+=$coupon->price;
    }
}
