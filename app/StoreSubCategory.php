<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreSubCategory extends Model
{
    //
    protected $fillable = [
        'name','category_id','owner_id', 'status', 'image'
    ];

    protected $table = 'store_sub_category';

    protected $appends = [ 'imagePath','categoryName'];

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }

    public function getCategoryNameAttribute()
    {
        return StoreCategory::find($this->attributes['category_id'])->name;
    }
}
