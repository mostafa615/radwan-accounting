<?php

namespace App\Http\Controllers\Reports;

use App\Models\Item;
use App\Models\Quantity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Store;
use App\Models\OrderDetail;
use Yajra\Datatables\Datatables;

class ItemCardController extends Controller
{
    public function index(Request $request)
    {

        $resources = Quantity::with('ownerable', 'item')->where(function ($q) use ($request) {
            if ($request->has('group_id') && !empty($request->group_id) && $request->group_id != null) {
                $q->whereHas('item', function ($q2) use ($request) {
                    $q2->where('group_id', $request->group_id);
                });
            }
            if ($request->has('items') && !empty($request->items) && $request->items != null) {
                $q->whereIn('item_id', $request->items);
            }
            if ($request->has('stores') && !empty($request->stores) && $request->stores != null) {
                $q->whereIn('ownerable_id', $request->stores);
            }
        })->where('ownerable_type', 'App\Models\Store')->get();

        if (auth()->user()->id == 1) {
            $stores = Store::has('quantities')->get();
        } else {
            $stores = Store::has('quantities')->where('branch_id', auth()->user()->branch_id)->get();
        }
        return view('reports.item-card', compact('stores', 'resources'));
    }

    // public function update_quantities(Request $request)
    // {
    //     // dd($request->all());
    //     $counter = 0;
    //     foreach ($request->item_id as $key=>$item) {
    //         $resource = Quantity::where('id', $request->id[$counter])->first();
    //         if ($resource) {
    //             $resource->update([
    //                 'quantity' => $request->quantity[$counter],
    //                 'init' => $request->init[$counter]
    //             ]);
    //         }
    //         $product = Item::where('id', $request->item_id[$counter])->first();
    //         if ($product) {
    //             $product->update([
    //                 'price' => $request->price[$counter],
    //                 'standard_weight' => $request->standard_weight[$counter],
    //             ]);
    //             // dd($product);

    //         }
    //         $counter++;
    //     }
    //     flash()->success('تم الحفظ بنجاح');
    //     return back();
    // }
    
    public function update_quantities(Request $request) {
        $counter = 0;
        foreach ($request->item_id as $key=>$item) {
            if(isset($request->id[$counter])) {
                $resource = Quantity::where('id', $request->id[$counter])->first();
                if ($resource) {
                    $resource->update([
                        'init' => $request->init[$counter],
                        'quantity' => $request->quantity[$counter],
                    ]);
                }
            }

            if(!isset($request->id[$counter])) {
                $product = Item::where('id', $request->item_id[$counter])->first();
                if ($product) {
                    $product->update([
                        'price' => $request->price[$counter],
                        'standard_weight' => $request->standard_weight[$counter],
                    ]);
                }
            }
            $counter++;
        }
        flash()->success('تم الحفظ بنجاح');
        return back();
    }

    public function perform(Request $request)
    {
        $query = OrderDetail::select('order_id', 'orders.date',
            'items.name as item',
            'quantity',
            'stores.name as store'
        )
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('items', 'items.id', '=', 'order_details.item_id')
            ->leftJoin('stores', 'stores.id', '=', 'order_details.store_id')
            ->whereHas('order', function ($query) use ($request) {

                if ($request->type == 'in') {
                    $query->where('type', 'in')
                        ->where('is_return', false);
                } else if ($request->type == 'return-in') {
                    $query->where('type', 'in')
                        ->where('is_return', true);
                } else if ($request->type == 'out') {
                    $query->where('type', 'out')
                        ->where('is_return', false);
                } else if ($request->type == 'return-out') {
                    $query->where('type', 'out')
                        ->where('is_return', true);
                }
            })
            ->whereBetween('orders.date', [
                $request->from,
                $request->to,
            ]);

        return Datatables::of($query)
            ->editColumn('date', function (OrderDetail $detail) {
                return optional(optional($detail->order)->date)->toDateString();
            })
            ->make(true);
    }
    
    
    public function reportPriscesItem  (Request $request)
    {
        // get all item where request group_id or where the items 
        $resources = Item::with("group")->where(function ($q) use ($request) {
           
           if ($request->has("items") && !empty($request->items) && $request->items != null) {
               $q->whereIn('id', $request->items);
           } 
           
           if ($request->has('group_id')  && !empty($request->group_id) && $request->group_id != null) {
               $q->where('group_id', $request->group_id);
           }
            
        })->get();
        
        return view('reports.reportPriscesItem', compact('resources'));

    }
    
    public function updateReportPriscesItem (Request $request)
    {
        // dd($request->all());
        $counter = 0;
        foreach ($request->item_id as $key=>$item) {
            $resource = Quantity::where('id', $request->id[$counter])->first();
            if ($resource) {
                $resource->update([
                    'quantity' => $request->quantity[$counter],
                    'init' => $request->init[$counter]
                ]);
            }
            $product = Item::where('id', $request->item_id[$counter])->first();
            if ($product) {
                $product->update([
                    'price' => $request->price[$counter],
                    'standard_weight' => $request->standard_weight[$counter],
                ]);
                // dd($product);

            }
            $counter++;
        }
        flash()->success('تم الحفظ بنجاح');
        return back();
    }
    
}
