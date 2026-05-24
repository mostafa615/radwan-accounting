<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Group;
use App\Models\Item;
use App\Models\OldCategory;
use App\Models\OldItem;
use App\Models\Quantity;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
//    public function old_db()
//    {
////return OldCategory::with('items')->get();
//        foreach (OldCategory::all() as $group) {
//            $new_group = Group::create([
//                'name'=>$group->Sub_Category_Name_A
//            ]);
//            $old_items=OldItem::where('Sub_Category_ID',$group->Sub_Category_ID)->get();
//            foreach ($old_items as $old_item){
//                Item::create([
//                    'code'=>$old_item->Item_Code,
//                    'price'=>$old_item->Price2,
//                    'name'=>$old_item->Item_Name_A,
//                    'group_id'=>$new_group->id
//                ]);
//            }
//        }
//    }
    public function store_group_items(Request $request)
    {
        $resources=Item::where('group_id',$request->group_id)->whereHas('quantities',function ($q) use($request){
            $q->where('ownerable_id',$request->store_id);
            $q->where('ownerable_type','App\Models\Store');
        })->get();
        
        $data = [
            'status' => 1,
            'message' => 'done',
            'data' => $resources,
        ];
        return response()->json($data);
    }

    public function my_test()
    {
        $stores = Store::get();
        foreach ($stores as $store) {
            $items = Item::get();
            foreach ($items as $item) {
                $old_quantites = Quantity::where([
                    ['ownerable_id', '=', $store->id],
                    ['ownerable_type', '=', 'App\Models\Store'],
                    ['item_id', '=', $item->id]
                ])->get();
                if (count($old_quantites) <= 0) {
                    Quantity::create([
                        'ownerable_id' => $store->id,
                        'ownerable_type' => 'App\Models\Store',
                        'item_id' => $item->id,
                        'quantity' => 0
                    ]);
                }
            }
        }
    }

    public function change_price(Request $request)
    {
        $item = Item::where('id', $request->id)->first();
        if ($item) {
            $item->update(['price' => $request->price]);
        }
        $data = [
            'status' => 1,
            'msg' => 'تم تغيير السعر بنجاح'
        ];
        return response()->json($data);
    }
    



    // public function group_items($id)
    // {
    //     $resources = Item::where('group_id', $id)->where("active", 1)->get();
    //     $group = DB::table('groups')->where('id', $id)->first();
    //     $disabled = false;
        
    //     if($group->edit_by_permission == 1){
    //         $disabled= true;
    //     }else{
    //         $disabled = false;
    //     }
    //         $data = [
    //             'status' => 1,
    //             'message' => 'done',
    //             'disabled' => $disabled,
    //             'data' => $resources
    //         ];
    //     return response()->json($data);
        
    // }

    public function group_items($id) {
        $group = DB::table('groups')->where('id', $id)->first();
        $resources = Item::where('group_id', $id)->where("active", 1)->get();

        $disabled = false;
        if($group->edit_permission_s == 1 && auth()->user()->branch_id == 1){
            $disabled = true;
        }
        else if($group->edit_permission_q == 1 && auth()->user()->branch_id == 2){
            $disabled = true;
        }
        else if($group->edit_permission_o == 1 && auth()->user()->branch_id == 6){
            $disabled = true;
        }
        else {
            $disabled = false;
        }

        $data = [
            'status' => 1,
            'message' => 'done',
            'disabled' => $disabled,
            'data' => $resources
        ];

        return response()->json($data);
    }
    
    // public function group_items($id)
    // {
    //     $resources = Item::with(['quantities' => function ($q) {
    //         $q->where('quantity', '>=', 1);
    //     }])->whereHas('quantities', function ($q) {
    //         $q->where('quantity', '>=', 1);
    //     })->where('group_id', $id)->where("active", 1)->get();
    //     $group = DB::table('groups')->where('id', $id)->first();
    //     $disabled = false;

    //     if ($group->edit_by_permission == 1) {
    //         $disabled = true;
    //     } else {
    //         $disabled = false;
    //     }
    //     $data = [
    //         'status' => 1,
    //         'message' => 'done',
    //         'disabled' => $disabled,
    //         'data' => $resources
    //     ];

    //     return response()->json($data);
    // }
    
    public function getItems(Request $request)
    {
        $groupId = $request->input('group_id');
    
        // Fetch the items for the selected group
        $items = Item::where('group_id', $groupId)->get();
    
        return response()->json(['items' => $items]);
    }
    
    
    public function group_items_buy($id)
    {
        $group = Group::where('id', $id)->first();
        if($group->name == "NO4"){
            $resources = DB::table('supplies')->get();
            // dd($resources);
            $data = [
                'status' => 1,
                'message' => 'done',
                'data' => $resources
            ];
        }else{
            $resources = Item::where('group_id', $id)->where("active", 1)->get();
            $data = [
                'status' => 1,
                'message' => 'done',
                'data' => $resources
            ];
        }
        return response()->json($data);

    }

    public function item_stores($id)
    {
        $resources = Store::/*whereHas('quantities', function ($q) use ($id) {
            $q->where('item_id', $id)->where('quantity', '>', 0);

        })->*/get();
        
        // if (auth()->user()->id != 1) {
        //     $resources = Store::where(function ($query) {
        //         $query->where('user_id', auth()->user()->id);
        //         })->get();
        // }else{
        //     $resources = Store::all();
        // }
        $data = [
            'status' => 1,
            'message' => 'done',
            'data' => $resources
        ];
        return response()->json($data);
    }

    public function branch_employees($id)
    {
        $resources = Employee::where('branch_id',$id)->where('active',1)->get();
        
        
        $data = [
            'status' => 1,
            'message' => 'done',
            'data' => $resources
        ];
        return response()->json($data);
    }

    public function item_store_info(Request $request)
    {
        $resources = Quantity::with('item')->where('ownerable_id', $request->store_id)->where('ownerable_type', 'App\Models\Store')->where('item_id', $request->item_id)->first();
        $data = [
            'status' => 1,
            'message' => 'done',
            'data' => $resources
        ];
        return response()->json($data);
    }

    public function get_ownerable($type)
    {
        $resources = '';
        if ($type == 'Supplier') {
            $resources = Supplier::get();
        } elseif ($type == 'Client') {
            $resources = Client::get();
        }
        $data = [
            'status' => 1,
            'message' => 'done',
            'type' => $type,
            'data' => $resources
        ];
        return response()->json($data);
    }
    
    public function summer_holiday_permission(Request $request)
    {
        $employee = Employee::find($request['emp_id']);
        if ($employee) {
            $status = $request->status == 1 ? 1 : 0;
            $employee->update(['summer_holiday_permission' => $status]);
            return response()->json('done');
        }
    }
}
