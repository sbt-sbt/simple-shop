<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributesValue extends Model
{
    use HasFactory;

    public function attributeGroup()
    {
     return $this->belongsTo(AttributesGroup::class,'attributeGroup_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class,'attributevalue_products','attribute_value_id','product_id');
    }
}
