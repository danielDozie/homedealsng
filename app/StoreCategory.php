<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreCategory extends Model
{
    //
    protected $fillable = [
        'name', 'status', 'image',
    ];

    protected $table = 'store_category';

    protected $appends = [ 'imagePath'];

    public function getImagePathAttribute()
    {
        return url('images/upload') . '/';
    }
}
