<?php

namespace App\Http\Controllers;

use App\Models\AttributesGroup;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @urlParam slug string required The slug of the product. Example: p2
     */
    public function getProductBySlug($slug)
    {
//        $product=Product::with(['photos','categories','brand','attributevalues'])->where('slug',$slug)->first();
        $product = Product::with(['photos', 'categories', 'brand', 'attributevalues'])->whereSlug($slug)->first();
        foreach ($product->categories as $category) {
            $productCategories[] = $category->name;
        }
        $relatedProducts=Product::with(['photos','categories'])->whereHas('categories',function ($q) use ($productCategories){
            $q->whereIn('name',$productCategories);
        })->get();


        return response()->json([
            'product'=>$product,
            'relatedProducts' => $relatedProducts,
        ], 200);
    }

    public function getProductsByCategory($id,$page=10)
    {
        $category=Category::findOrFail($id);
        $products=Product::with(['photos'])->whereHas('categories',function ($q) use ($category){
            $q->where('name',$category->name);
        })->paginate($page);
        return response()->json([
            'data'=>[
                'category'=>$category,
                'products'=>$products,
            ],],200);

    }

    public function getProductsByBrand($id,$page=10)
    {
        $products=Product::with(['photos'])->where('brand_id',$id)->paginate($page);
        return response()->json([
            'data'=>[
                'products'=>$products,
            ],],200);
    }

    public function getSortedProducts($id,$sort,$page=10)
    {
        $category=Category::findOrFail($id);
        $products=Product::with(['photos'])->whereHas('categories',function ($q) use ($category){
            $q->where('name',$category->name);
        })->orderBy('price',$sort)
            ->paginate($page);

        return response()->json([
            'data'=>[
                'category'=>$category,
                'products'=>$products,
            ],],200);
    }

    public function getAttributes($id)
    {
        $attributesGroups=AttributesGroup::with('attributesValue')
            ->whereHas('categories',function ($q) use ($id){
            $q->where('category_id',$id);
        })->get();

        $result=['attributesGroups'=>$attributesGroups,];
        return response()->json($result,200);
    }

    public function filterProductByAttributes($categoryId,$sort,$attributes,$paginate=10)
    {
        //sort values = ASC or DESC
        $attributesArray=json_decode($attributes,true);
//        return $attributesArray;
        $products=Product::with('photos')
            ->whereHas('categories',function ($q) use ($categoryId){
            $q->where('category_id',$categoryId);
        })->whereHas('attributevalues',function ($q)use ($attributesArray){
            $q->whereIn('attribute_value_id',$attributesArray);
            })
            ->orderBy('price',$sort)
            ->paginate($paginate);

        $result=['products'=>$products,];
        return response()->json($result,200);


    }
}
