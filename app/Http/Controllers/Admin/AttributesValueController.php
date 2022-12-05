<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributesValue;
use Illuminate\Http\Request;

class AttributesValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributesValue=AttributesValue::with('attributeGroup')->get();
        return $attributesValue;
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
        $attributeValue=new AttributesValue();
        $attributeValue->value=$request->input('value');
        $attributeValue->attributeGroup_id=$request->input('attributeGroup_id');
        $attributeValue->save();
        return redirect('api/administrator/attributes-value');
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attributeValue=AttributesValue::findOrFail($id);
        $attributeValue->value=$request->input('value');
        $attributeValue->attributeGroup_id=$request->input('attributeGroup_id');
        $attributeValue->save();
        return redirect('api/administrator/attributes-value');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attributeValue=AttributesValue::findOrFail($id);
        $attributeValue->delete();
        return redirect('api/administrator/attributes-value');

    }
}
