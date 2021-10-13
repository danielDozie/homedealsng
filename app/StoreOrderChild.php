<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreOrderChild extends Model
{
    //

    protected $fillable = [
        'order_id', 'item_id', 'price','quantity','shop_id','unit','unit_qty'
    ];
    protected $table = 'store_order_child';
    
    protected $appends = [ 'itemName'];
    public function getItemNameAttribute()
    {
        if($this->attributes['item_id'] != null){
            $item =  StoreItem::where('id',$this->attributes['item_id'])->first();
            if($item){
                return $item->name;
            }
            else{
                return null;
            }
        }
        else{
            return null;
        }
    }
    
}
