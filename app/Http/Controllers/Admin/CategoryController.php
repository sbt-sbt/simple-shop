<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributesGroup;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories=Category::with('childrenRecursive')->where('parent_id','=',null)->get();
//        dd(response($categories));
        return response()->json($categories);
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam name string required Name of category. Example: cat 1
     * @bodyParam meta_desc string required meta_desc of category. Example: cat 1
     * @bodyParam meta_title string required meta_title of category. Example: cat 1
     * @bodyParam meta_keywords string required meta_keywords of category. Example: cat 1
     * @bodyParam parent_id int parent_id of category. Example: 1
     * @apiResourceModel App/Models/Category
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category=new Category();
        $category->name=$request->input('name');
        $category->parent_id=$request->input('parent_id');
        $category->meta_desc=$request->input('meta_desc');
        $category->meta_title=$request->input('meta_title');
        $category->meta_keywords=$request->input('meta_keywords');
        $category->save();
        return redirect('/api/administrator/categories');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @queryParam name string required Name of category. Example: cat 1
     * @bodyParam meta_desc string required meta_desc of category. Example: cat 1
     * @bodyParam meta_title string required meta_title of category. Example: cat 1
     * @bodyParam meta_keywords string required meta_keywords of category. Example: cat 1
     * @bodyParam parent_id int parent_id of category. Example: 1
     * @apiResourceModel App/Models/Category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category=Category::findOrFail($id);
        $category->name=$request->input('name');
        $category->parent_id=$request->input('parent_id');
        $category->meta_desc=$request->input('meta_desc');
        $category->meta_title=$request->input('meta_title');
        $category->meta_keywords=$request->input('meta_keywords');
        $category->save();
//        return redirect('/api/administrator/categories');
        return [
            'status'=>'success',
            'message'=>'category updated successfully'
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category=Category::with('childrenRecursive')->where('id','=',$id)->first();
        $parent_id=$category->parent_id;
        if (count($category->childrenRecursive)>0){
            foreach($category->childrenRecursive as $subCategory){
                $subCategory->parent_id=$parent_id;
                $subCategory->save();
//                return $subCategory;
            }
        }
        $category->delete();
        return redirect('/api/administrator/categories');
    }

    public function getCategoryAttributes($id)
    {
        $category=Category::findOrFail($id);
//        $attributeGroups=AttributesGroup::all();
        return $category->attributeGroups;
    }

    public function setCategoryAttributes(Request $request,$id)
    {
        $category=Category::findOrFail($id);
        $attributeGroups=AttributesGroup::findOrFail($request)->pluck('id');
        $category->attributeGroups()->sync($attributeGroups);
        $category->save();
        return redirect('api/administrator/categories/'.$id.'/settings');

    }
}
