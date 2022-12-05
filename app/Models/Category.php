<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public function children(){
        return $this->hasMany(Category::class,'parent_id');
    }

    public function childrenRecursive()
    {
     return $this->children()->with('childrenRecursive');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class,'category_products');
    }

    public function attributeGroups()
    {
        return $this->belongsToMany(AttributesGroup::class,'atrributegroup_category','category_id','attribute_group_id');
    }
}
