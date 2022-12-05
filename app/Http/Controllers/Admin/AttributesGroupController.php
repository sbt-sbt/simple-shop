<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributesGroup;
use Illuminate\Http\Request;

class AttributesGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributesGroup=AttributesGroup::all();
        return $attributesGroup;
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
        $attributesGroup=new AttributesGroup();
        $attributesGroup->title=$request->input('title');
        $attributesGroup->type=$request->input('type');
        $attributesGroup->save();
        return redirect('api/administrator/attributes-group');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attributeGroup=AttributesGroup::findOrFail($id);
        return $attributeGroup->attributesValue;
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attributesGroup=AttributesGroup::findOrFail($id);
        $attributesGroup->title=$request->input('title');
        $attributesGroup->type=$request->input('type');
        $attributesGroup->save();
        return redirect('api/administrator/attributes-group');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attributesGroup=AttributesGroup::findOrFail($id);
        $attributesGroup->delete();
        return redirect('api/administrator/attributes-group');

    }
}
