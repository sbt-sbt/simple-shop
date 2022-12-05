<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PhotoController;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands=Brand::all();
        return $brands;
    }

    /**
     * Show the form for creating a new resource.
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'title'=>'required|unique:brands',
            'description'=>'required'
        ],[
            'title.required'=>'برند شما نمی تواند بدون عنوان باشد',
            'title.unique'=>'این برند از قبل موجود می باشد',
            'description.required'=>'برند شما نمی تواند بدون توضیحات باشد',
        ]);
        if($validator->fails()){
//            return redirect('api/administrator/brands')->withErrors($validator)->withInput();
            return $validator->errors()->all();
        }else{
            $brand=new Brand();
            $brand->title=$request->input('title');
            $brand->description=$request->input('description');
            $brand->photo_id=$request->input('photo_id');
            $brand->save();
            return redirect('api/administrator/brands');
        }

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
        $brand=Brand::with('photo')->where('id','=',$id)->first();
        echo '<img src="http://localhost:8000/storage/photos/'.$brand->photo->path.'">';
//        return $brand->photo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator=Validator::make($request->all(),[
            'title'=>'required|unique:brands,title,'.$id,
            'description'=>'required'
        ],[
            'title.required'=>'برند شما نمی تواند بدون عنوان باشد',
            'title.unique'=>'این برند از قبل موجود می باشد',
            'description.required'=>'برند شما نمی تواند بدون توضیحات باشد',
        ]);
        if($validator->fails()){
//            return redirect('api/administrator/brands')->withErrors($validator)->withInput();
            return $validator->errors()->all();
        }else{
            $brand=Brand::findOrFail($id);
            $brand->title=$request->input('title');
            $brand->description=$request->input('description');
            $brand->photo_id=$request->input('photo_id');
            $brand->save();
            return redirect('api/administrator/brands');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand=Brand::findOrFail($id);
        Storage::delete('public/photos/'.$brand->photo->path);
        $brand->delete();
        return redirect('api/administrator/brands');

    }
}
