<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributesGroup extends Model
{
    use HasFactory;
    protected $table='attributes_group';

    public function attributesValue()
    {
        return $this->hasMany(AttributesValue::class,'attributeGroup_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class,'atrributegroup_category','attribute_group_id','category_id');
    }
}
