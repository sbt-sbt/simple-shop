<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function categories()
    {
        return $this->belongsToMany(Category::class,'category_products');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attributevalues()
    {
        return $this->belongsToMany(AttributesValue::class,'attributevalue_products','product_id','attribute_value_id');
    }

    public function photos()
    {
        return $this->belongsToMany(Photo::class,'photo_products');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}
