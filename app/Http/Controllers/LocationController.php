<?php

namespace App\Http\Controllers;

use App\Location;
use App\Coupon;
use App\Notification;
use App\StoreSubCategory;
use App\StoreOrder;
use App\StoreOrderChild;
use App\StoreItem;
use App\StoreShop;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $location = Location::orderBy('id', 'DESC')->paginate(10);
        return view('mainAdmin.location.viewLocation',['locations'=>$location]);
    }

    public function create()
    {
        return view('mainAdmin.location.addLocation');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'bail|required|unique:location',
            'status' => 'bail|required',
            'latitude' => 'bail|required|numeric',
            'longitude' => 'bail|required|numeric',
            'radius' => 'bail|required|numeric',
            'description' => 'bail|required',
        ]);
        $data = $request->all();

        if(isset($request->popular)){ $data['popular'] = 1; }
        else{ $data['popular'] = 0; }
        Location::create($data);
        return redirect('Location');
    }

    public function edit($id)
    {
        $data = Location::findOrFail($id);
        return view('mainAdmin.location.editLocation',['data'=>$data]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'bail|required|unique:location,name,' . $id . ',id',
            'status' => 'bail|required',
            'latitude' => 'bail|required|numeric',
            'longitude' => 'bail|required|numeric',
            'radius' => 'bail|required|numeric',
            'description' => 'bail|required',
        ]);
        $data = $request->all();

        if(isset($request->popular)){ $data['popular'] = 1; }
        else{ $data['popular'] = 0; }
           
        Location::findOrFail($id)->update($data);
        return redirect('Location');
    }

    public function destroy($id)
    {
        try {          
            $Storeshop = StoreShop::where('location',$id)->get();
            if($Storeshop){
                foreach ($Storeshop as $value) {
                    $StoreItem = StoreItem::where('shop_id',$value->id)->get();
                    if($StoreItem){
                        foreach ($StoreItem as $i) {
                            $i->delete();
                        } 
                    }  
                    $StoreSubCategory = StoreSubCategory::where('shop_id',$value->id)->get();                   
                    if($StoreSubCategory){
                        foreach ($StoreSubCategory as $g) {                           
                            $g->delete();
                        } 
                    }
                  
                    $Coupon = Coupon::where('location_id',$value->id)->get();
                    if($Coupon){
                        foreach ($Coupon as $c) {
                            $c->delete();
                        } 
                    }                                       

                    $Order = StoreOrder::where('shop_id',$value->id)->get();
                    if($Order){
                        foreach ($Order as $item) {                    
                            $Notification = Notification::where('order_id',$item->id)->get();
                            if($Notification){
                                foreach ($Notification as $n) {
                                    $n->delete();
                                } 
                            }
                            $OrderChild = StoreOrderChild::where('order_id',$item->id)->get();
                            if($OrderChild){
                                foreach ($OrderChild as $oc) {
                                    $oc->delete();
                                } 
                            }                                              
                            $item->delete();
                        } 
                    } 
                    $value->delete();
                } 
            }

            $delete = Location::findOrFail($id);
            $delete->delete();
            return 'true';
        } catch (\Exception $e) {
            return response('Data is Connected with other Data', 400);
        }
    }
}
