<?php

namespace App\Http\Controllers;

use App\StoreItem;
use App\StoreCategory;
use App\StoreSubCategory;
use App\Setting;
use App\StoreOrderChild;
use App\Currency;
use App\StoreShop;
use Auth;
use Illuminate\Http\Request;

class StoreItemController extends Controller
{
    public function index()
    {
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        $item = StoreItem::with(['category'])->where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->paginate(7);
        return view('admin.StoreItem.viewStoreItem',['items'=>$item,'currency'=>$currency]);

    }

    public function create()
    {
        $category = StoreCategory::orderBy('id', 'DESC')->get();
        $subcategory = StoreSubCategory::where('owner_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        $shop = StoreShop::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        return view('admin.StoreItem.addStoreItem',['shop'=>$shop,'category'=>$category,'subcategory'=>$subcategory]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'bail|required|unique:store_item',
            'description' => 'bail|required',
            'category_id' => 'bail|required',
            'subcategory_id' => 'bail|required',
            'shop_id' => 'bail|required',
            'status' => 'bail|required',
            'image' => 'bail|required',
            'stock' => 'bail|required',
        ]);
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        $ar = array();
        for ($i=0; $i < count($request->qty); $i++) {
            if($i == 0)
            {
                $data['fake_price'] = $request->fake_price[$i];
                $data['sell_price'] = $request->price[$i];
            }
            $array = ['qty' => $request->qty[$i], 'unit'  => $request->unit[$i], 'price' => $request->price[$i], 'fake_price' => $request->fake_price[$i]];
            array_push($ar, $array);
        }
        $data['detail'] = json_encode($ar);
        $attached_files = array();
        if(isset($request->gallery_img))
        {
            foreach($request->gallery_img as $gallery) {
                $name = uniqid() . '.' . $gallery->getClientOriginalExtension();
                $destinationPath = public_path('/images/upload');
                $gallery->move($destinationPath, $name);
                array_push($attached_files,$name);
            }
        }
        $data['gallery'] = implode(", ",$attached_files);
        $data = StoreItem::create($data);
        return redirect('StoreItem');
        
    }

    public function edit($id)
    {
        $shop = StoreShop::where('user_id',Auth::user()->id)->orderBy('id', 'DESC')->get();
        $item = StoreItem::with(['category'])->find($id);
        $category = StoreShop::find($item->shop_id)->category_id; 
        $category = StoreCategory::whereIn('id',explode(",",$category))->get();
        $subcategory = StoreSubCategory::where('category_id',$item->category_id)->orderBy('id', 'DESC')->get();
        return view('admin.StoreItem.editStoreItem',['category'=>$category,'subcategory'=>$subcategory,
        'shop'=>$shop,'data'=>$item]);
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'bail|required|unique:store_item,name,' . $id . ',id',
            'description' => 'bail|required',
            'category_id' => 'bail|required',
            'subcategory_id' => 'bail|required',
            'shop_id' => 'bail|required',
            'status' => 'bail|required',
            'stock' => 'bail|required',
        ]);
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $g = StoreItem::find($id);
        if ($request->hasFile('image')) {
            if(\File::exists(public_path('/images/upload/'. $g->image))){
                \File::delete(public_path('/images/upload/'. $g->image));
            }
            $image = $request->file('image');
            $name = uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }

        $ar = array();
        for ($i=0; $i < count($request->qty); $i++) {
            if($i == 0)
            {
                $data['fake_price'] = $request->fake_price[$i];
                $data['sell_price'] = $request->price[$i];
            }
            $array = ['qty' => $request->qty[$i], 'unit'  => $request->unit[$i], 'price' => $request->price[$i], 'fake_price' => $request->fake_price[$i]];

            array_push($ar, $array);
        }
        $data['detail'] = $ar;
        $data = StoreItem::find($id)->update($data);
       
        return redirect('StoreItem');
    }

    public function destroy($id)
    {
        try {        
            $delete = StoreItem::find($id);
            $child = StoreOrderChild::where('item_id',$id)->get();
            if(count($child)==0){
                $delete->delete();
                if(\File::exists(public_path('/images/upload/'. $delete->image))){
                    \File::delete(public_path('/images/upload/'. $delete->image));
                }
                return 'true';
            }  
            else{
                return response('Data is Connected with other Data', 400);
            } 
            
        } catch (\Exception $e) {
            return response('Data is Connected with other Data', 400);
        }
    }

    public function viewGallery($id)
    {
        $data = StoreItem::find($id);
        return view('admin.StoreItem.viewStoreItemGallery', compact('data'));
    }

    public function deleteGallery($id,$name)
    {
        $data = StoreItem::find($id);
        $gallery = explode(", ",$data->gallery);
        if (($key = array_search($name, $gallery)) !== false) {
            unset($gallery[$key]);
            if(\File::exists(public_path('/images/upload/'. $name))) {
                \File::delete(public_path('/images/upload/'. $name));
            }
        }
        $data['gallery'] = implode(", ",$gallery);
        if ($data['gallery'] == "") {
            $data['gallery'] = null;
        }
        $data->save();
        return true;
    }

    public function addGallery($id) {
        $data = StoreItem::find($id);
        return view('admin.StoreItem.addStoreItemGallery', compact('data'));
    }

    public function storeGallery(Request $request ,$id) {
        $request->validate([
            'gallery_img' => 'bail|required',
        ]);
        $data = StoreItem::find($id);
        $gallery = $data->gallery;
        if($gallery == null || $gallery == ""){
            $attached_files = array();
        } else {
            $attached_files = explode(", ",$gallery);
        }
        if(isset($request->gallery_img))
        {
            foreach($request->gallery_img as $gallery) {
                $name = uniqid() . '.' . $gallery->getClientOriginalExtension();
                $destinationPath = public_path('/images/upload');
                $gallery->move($destinationPath, $name);
                array_push($attached_files,$name);
            }
        }
        $data['gallery'] = implode(", ",$attached_files);
        $data->save();
        return redirect(url('/StoreItem/gallery/'.$data->id.'/edit'));
    }
    
    public function viewStoreItem() {
        $currency_code = Setting::where('id',1)->first()->currency;
        $currency = Currency::where('code',$currency_code)->first()->symbol;
        $item = StoreItem::with(['category'])->orderBy('id', 'DESC')->paginate(7);
        return view('mainAdmin.item.viewStoreItem',['items'=>$item,'currency'=>$currency]);
    }
}
