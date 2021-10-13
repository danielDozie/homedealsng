<?php

namespace App\Http\Controllers;

use App\StoreCategory;
use App\StoreShop;
use App\StoreItem;
use Illuminate\Http\Request;

class StoreCategoryController extends Controller
{
    public function index()
    {
        $data = StoreCategory::orderBy('id', 'DESC')->paginate(7);
        foreach ($data as $item) {
            $shop = StoreShop::get();       
            $shops = array();
            foreach ($shop as $value) {            
                $likes=array_filter(explode(',',$value->category_id));          
                if(count(array_keys($likes,$item->id))>0){
                    if (($key = array_search($item->id, $likes)) !== false) {
                        array_push($shops,$value->id); 
                    }
                }
            }    
            $item->total_shop = count($shops);  
        }
        return view('mainAdmin.StoreCategory.viewStoreCategory',['categories'=>$data]);
    }

    public function create()
    {
        return view('mainAdmin.StoreCategory.addStoreCategory');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'bail|required|unique:store_category',
            'status' => 'bail|required',
            'image' => 'bail|required',
        ]);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        StoreCategory::create($data);
        return redirect('StoreCategory');
    }

    public function edit($id)
    {
        $data = StoreCategory::findOrFail($id);
        return view('mainAdmin.StoreCategory.editStoreCategory',['data'=>$data]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'bail|required|unique:store_category,name,' . $id . ',id',
            'status' => 'bail|required',
        ]);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/upload');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        StoreCategory::findOrFail($id)->update($data);

        return redirect('StoreCategory');
    }

    public function destroy($id)
    {
        try {
            $item = StoreItem::where('category_id',$id)->get();           
            if(count($item)==0){
                $delete = StoreCategory::find($id);
                $delete->delete();
                return 'true';
            }     
            else{
                return response('Data is Connected with other Data', 400);
            }                               
        } catch (\Exception $e) {
            return response('Data is Connected with other Data', 400);
        }
    }
}
