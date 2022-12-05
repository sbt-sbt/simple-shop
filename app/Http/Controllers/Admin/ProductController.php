<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Expr\Array_;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product=Product::all();
        $lastestProduct=Product::orderBy('created_at','desc')->limit(10)->get();
        $modeCategoryProduct=Product::with(['categories'=>function ($q){
            $q->where('name','پوشاک');
        }])->limit(10)->get();
        return $modeCategoryProduct;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands=Brand::all();
        $categories=Category::all();
        return ['brands'=>$brands,'categories'=>$categories];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product=new Product();
        $product->title=$request->input('title');

        $product->sku=generateSKU();
        $product->slug=make_slug($request->input('title'));

        $product->status=$request->input('status');
        $product->price=$request->input('price');
        $product->discount=$request->input('discount');
        $product->description=$request->input('description');
        $product->brand_id=$request->input('brand_id');
        $product->meta_desc=$request->input('meta_desc');
        $product->meta_title=$request->input('meta_title');
        $product->meta_keywords=$request->input('meta_keywords');
        $product->user_id=1;

        $product->save();

        $product->categories()->sync(explode(',',$request->input('categories')));
        $product->attributevalues()->sync(explode(',',$request->input('attributesValue')));
        $product->photos()->sync(explode(',',$request->input('photos')));

        return [
            'data'=>'product saved successfully'];
    }

//    public function generateSKU()
//    {
//        $number=mt_rand(10000,99999);
//        if ($this->checkSKU($number)){
//            return $this->generateSKU();
//        }else{
//            return (string)$number;
//        }
//    }
//
//    public function checkSKU($number)
//    {
//        return Product::where('sku','=',$number)->exists();
//    }

//    function make_slug($string){
//        return preg_replace('/\s+/u','-',trim($string));
//    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return Product::with('categories','brand','attributevalues','photos')->where('id','=',$product->id)->first();;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $p=Product::with('categories','brand','attributevalues','photos')->where('id','=',$product->id)->first();
        return $p->brand;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product=Product::findOrFail($product->id);
//        return $product;
        $product->title=$request->input('title');

        $product->sku=generateSKU();
        $product->slug=make_slug($request->input('title'));

        $product->status=$request->input('status');
        $product->price=$request->input('price');
        $product->discount=$request->input('discount');
        $product->description=$request->input('description');
        $product->brand_id=$request->input('brand_id');
        $product->meta_desc=$request->input('meta_desc');
        $product->meta_title=$request->input('meta_title');
        $product->meta_keywords=$request->input('meta_keywords');
        $product->user_id=1;

        $product->save();

        $product->categories()->sync(explode(',',$request->input('categories')));
        $product->attributevalues()->sync(explode(',',$request->input('attributesValue')));
        $product->photos()->sync(explode(',',$request->input('photos')));

        return [
            'data'=>'product updated successfully'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product=Product::findOrFail($product->id);
        $product->delete();
        return [
            'data'=>'product deleted successfully'];
    }
}
