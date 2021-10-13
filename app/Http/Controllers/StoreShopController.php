<?php

namespace App\Http\Controllers;

use App\StoreShop;
use App\Setting;
use App\StoreCategory;
use App\Currency;
use App\StoreItem;
use App\StoreSubCategory;
use App\StoreOrder;
use Auth;
use App\StoreOrderChild;
use App\Notification;
use App\Coupon;
use Carbon\Carbon;
use App\Location;
use Illuminate\Http\Request;

class StoreShopController extends Controller
{
    public function index()
    {
        $data = StoreShop::with('locationData')->where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);
        return view('admin.StoreShop.viewStoreShop',['shops'=>$data]);
    }

    public function create()
    {
        $location = Location::orderBy('id', 'DESC')->get();
        $category = StoreCategory::orderBy('id', 'DESC')->get();
        return view('admin.StoreShop.addStoreShop',['location'=>$location,'category'=>$category]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'bail|required|unique:store_shop',
            'address' => 'bail|required',
            'description' => 'bail|required',
            'latitude' => 'bail|required|numeric',
            'longitude' => 'bail|required|numeric',
            'radius'=> 'bail|required|numeric',
            'status' => 'bail|required',
            'open_time' => 'bail|required',
            'close_time' => 'bail|required',
            'image' => 'bail|required',
            'cover_image' => 'bail|required',
            'location' => 'bail|required',
            'phone' => 'bail|required',

        ]);

        $data = $request->all();
        $data['category_id']= implode(",",$data['category_id']);
        $data['user_id'] = Auth::user()->id;
        $data['open_time'] = Carbon::parse($data['open_time'])->format('g:i A');
        $data['close_time'] = Carbon::parse($data['close_time'])->format('g:i A');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $cover_image_name = uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $cover_image_name);
            $data['cover_image'] = $cover_image_name;
        }
        $shop = StoreShop::create($data);

        return redirect('StoreShop');
    }

    public function show($id)
    {
        $shop = StoreShop::where('name',$id)->first();
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        return view('admin.StoreShop.storeShopDetail',['data'=>$shop,'currency'=>$currency]);
    }

    public function edit($id)
    {
        $data = StoreShop::find($id);
        $data['open_time'] = Carbon::parse($data->open_time)->format('H:i');
        $data['close_time'] = Carbon::parse($data->close_time)->format('H:i');
        $category = StoreCategory::orderBy('id', 'DESC')->get();
        $location = Location::orderBy('id', 'DESC')->get();
        return view('admin.StoreShop.editStoreShop',['data'=>$data,'category'=>$category,'location'=>$location]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'bail|required|unique:store_shop,name,' . $id . ',id',
            'address' => 'bail|required',
            'description' => 'bail|required',
            'latitude' => 'bail|required|numeric',
            'longitude' => 'bail|required|numeric',
            'radius'=> 'bail|required|numeric',
            'status' => 'bail|required',
            'open_time' => 'bail|required',
            'close_time' => 'bail|required',
            'location' => 'bail|required',
            'phone' => 'bail|required',
        ]);

        $data = $request->all();
        $data['category_id']= implode(",",$data['category_id']);
        $data['user_id'] = Auth::user()->id;
        $data['open_time'] = Carbon::parse($data['open_time'])->format('g:i A');
        $data['close_time'] = Carbon::parse($data['close_time'])->format('g:i A');

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $name = uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        if ($request->hasFile('cover_image')) {
            $cover_image = $request->file('cover_image');
            $cover_image_name = uniqid() . '.' . $cover_image->getClientOriginalExtension();
            $cover_destinationPath = public_path('/images/upload');
            $cover_image->move($cover_destinationPath, $cover_image_name);
            $data['cover_image'] = $cover_image_name;
        }
        $shop = StoreShop::find($id)->update($data);

        return redirect('StoreShop');
    }

    public function destroy($id)
    {
        try{
            $StoreItem = StoreItem::where('shop_id',$id)->get();
            if(count($StoreItem)>0){
                foreach ($StoreItem as $i) {
                    $i->delete();
                } 
            }  
             $StoreSubCategory = StoreSubCategory::where('shop_id',$id)->get();                   
            if(count($StoreSubCategory)>0){
                foreach ($StoreSubCategory as $g) {                           
                    $g->delete();
                } 
            }
            $Coupon = Coupon::where('shop_id',$id)->get();
            if(count($Coupon)>0){
                foreach ($Coupon as $c) {
                    $c->delete();
                } 
            } 
            
            $Order = StoreOrder::where('shop_id',$id)->get();
            if(count($Order)>0){
                foreach ($Order as $item) {                    
                    $Notification = Notification::where('order_id',$item->id)->get();
                    if(count($Notification)>0){
                        foreach ($Notification as $n) {
                            $n->delete();
                        } 
                    }
                  
                    $OrderChild = StoreOrderChild::where('order_id',$item->id)->get();
                    if(count($OrderChild)>0){
                        foreach ($OrderChild as $oc) {
                            $oc->delete();
                        } 
                    }                                              
                    $item->delete();
                } 
            }

            $shop = StoreShop::find($id);
            $shop->delete();
            return 'true';
        }catch (\Exception $e) {
            return response('Data is Connected with other Data', 400);
        }
    }

    public function shopCategory($id){
        $category = StoreShop::find($id)->category_id; 
        $category = StoreCategory::whereIn('id',explode(",",$category))->get();   
        return response()->json(['data' =>$category ,'success'=>true], 200);
    }

    public function itemSubcategory($id){
       $data = StoreSubCategory::where([['category_id',$id],['owner_id',Auth::user()->id]])->get();
       return response()->json(['data' =>$data ,'success'=>true], 200);
    }

}
