<?php

namespace App\Http\Controllers;

// use App\Models\Load;
// use App\Models\LoadDetail;
// use App\Models\Quantity;
// use Illuminate\Http\Request;

// use App\Models\Store;
// use App\Models\QuantityDailies;
// use App\Models\Group;

// use App\DataTables\LoadDataTable;

// use App\DataTables\LoadDetailsDataTable;
// use App\Utils\Util;
// use Carbon\Carbon;
// use Illuminate\Support\Facades\DB;
// use Pusher\Pusher;

// class LoadController extends Controller
// {
//     protected $util;
//     protected $dateNow;
//     protected $timeNow;

//     public function __construct(Util $util)
//     {
//         $this->util = $util;
//         $this->dateNow = Carbon::now()->format('Y-m-d');
//         $this->timeNow = Carbon::now()->format('H:i:s');
//     }
//     /**
//      * Display a listing of the resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function index(LoadDataTable $dataTable)
//     {
//         return $dataTable->render('load.index');
//     }

//     public function create(Request $request)
//     {
//         $store = Store::where('id', $request->store_id)->first();
//         $groups = Group::whereHas('items', function ($query) use ($store) {
//             $query->whereHas('quantities', function ($query) use ($store) {
//                 $query->where('ownerable_type', 'App\Models\Store')
//                     ->where('ownerable_id', $store->id);
//             });
//         })->get();
//         $to = Store::where('id', '!=', $request->store_id)->get();
//         return view('load.create', compact('store', 'to', 'groups'));
//     }

//     public function store(Request $request)
//     {
//         // return $request->all();
//         $this->validate($request, [
//             'group_id' => 'required',
//             'item_id' => 'required',
//             'quantity' => 'required',
//             'date' => 'required|date',
//             'to_store_id' => 'required',
//             'from_store_id' => 'required',
//         ]);
//         DB::beginTransaction();
//         $resource = Load::create([
//             'user_id' => auth()->user()->id,
//             'date' => $request->date,
//             'from_id' => $request->from_store_id,
//             'to_id' => $request->to_store_id,
//             'notes' => $request->notes,
//         ]);
//         $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'loads', $resource->id, $this->dateNow, $this->timeNow, null, null );

//         $counter = 0;
//         foreach ($request->group_id as $group) {
//             $from_quantity = Quantity::where('item_id', $request->item_id[$counter])
//                 ->where('ownerable_id', $request->from_store_id)
//                 ->where('ownerable_type', 'App\Models\Store')->first();
//             if ($from_quantity) {
//                 QuantityDailies::create([
//                     'ownerable_id'=>$request->from_store_id,
//                     'ownerable_type'=>'App\Models\Store',
//                     'item_id'=>$request->item_id[$counter],
//                     'quantity'=>$request->quantity[$counter],
//                     'type' => '6', // from Store
//                 ]);
//                 if ($from_quantity->quantity >= $request->quantity[$counter]) {
//                     $pending = 1;
//                     if (Auth()->user()->id == 1) {
//                         $pending = 0;
//                         $from_quantity->decrement('quantity', $request->quantity[$counter]);
//                         $to_quantity = Quantity::where('item_id', $request->item_id[$counter])
//                             ->where('ownerable_id', $request->to_store_id)
//                             ->where('ownerable_type', 'App\Models\Store')->first();
//                         $store_to=Store::where('id',$request->to_store_id)->first();
//                         if ($store_to) {
//                             QuantityDailies::create([
//                                 'ownerable_id'=>$request->to_store_id,
//                                 'ownerable_type'=>'App\Models\Store',
//                                 'item_id'=>$request->item_id[$counter],
//                                 'quantity'=>$request->quantity[$counter],
//                                 'type' => '7', // To store 
//                             ]);
//                             $this->push_notification_update(['user_id' => $store_to->user_id,'url'=>url('pending-loads'), 'message' => 'تمت اضافة عملية جديده في تحميل الخامات']);
//                         }
//                         if ($to_quantity) {
//                             $to_quantity->increment('quantity', $request->quantity[$counter]);

//                         }
//                     }
//                     $store_to=Store::where('id',$request->to_store_id)->first();
//                     if ($store_to) {
//                         $this->push_notification_update(['user_id' => $store_to->user_id,'url'=>url('pending-loads'), 'message' => 'تمت اضافة عملية جديده في تحميل الخامات']);
//                     }
//                     LoadDetail::create([
//                         'load_id' => $resource->id,
//                         'item_id' => $request->item_id[$counter],
//                         'quantity' => $request->quantity[$counter],
//                         'pending' => $pending
//                     ]);
                    
//                 } else {
//                     flash('الكمية المطلوبة أقل من الكمية المتاحة')->error();
//                 }
//             }
//             $counter++;
//         }
//         DB::commit();
//         flash('تمت العمليه بنجاح')->success();
//         return redirect()->route('load.index');
//     }

//     public function load_print_from($id){
//         $resource=Load::with('loadDetails.item')->where('id',$id)->first();
//         return view('load.printFrom',compact('resource'));
//     }
    
//     public function load_print_to($id){
//         $resource=Load::with('loadDetails.item')->where('id',$id)->first();
//         return view('load.printTo',compact('resource'));
//     }
    
//     public function show(Load $load, LoadDetailsDataTable $dataTable)
//     {
//         return $dataTable->render('load.show', ['load' => $load]);
//     }

//     public function edit($id)
//     {
//         $resource = Load::with('loadDetails.item')->where('id', $id)->first();
//         return view('load.edit', compact('resource'));
//     }

//     public function update(Request $request, $id)
//     {
//         $this->validate($request, [
//             'quantity' => 'required',
//             'detail_id' => 'required'
//         ]);
//         // dd($request->detail_id);
//         $loadDetailsArr = LoadDetail::whereIn('id', $request->detail_id)->where('pending',0)->get();
//         if(!$loadDetailsArr->isEmpty()) {
//             flash()->error(' لا يمكن التعديل لان تم استلام اصناف من قبل المستقبل برجاء المحاوله مرة اخري');
//             return back();
//         }


//         $oldData = Load::where('id', $id)->first();

//         $newData = $request->all();
//         $properties = [
//             'old_data' => $oldData,
//             'new_data' => $newData
//         ];

//         $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'loads', $oldData->id, $this->dateNow, $this->timeNow, $properties, null );

//         $counter = 0;
//         foreach ($request->detail_id as $value) {
//             $item = LoadDetail::where('id', $request->detail_id[$counter])->first();
//             if ($item) {
//                 $load = Load::where('id', $item->load_id)->first();
//                 if ($load) {
//                     if ($item->pending == 1) {
//                         // dd('vb');
//                         $from_quantity = Quantity::where('item_id', $item->item_id)
//                             ->where('ownerable_id', $load->from_id)
//                             ->where('ownerable_type', 'App\Models\Store')->first();

//                         $to_quantity = Quantity::where('item_id', $item->item_id)
//                             ->where('ownerable_id', $load->to_id)
//                             ->where('ownerable_type', 'App\Models\Store')->first();
//                         // $from_quantity->increment('quantity', $item->quantity);
//                         // $to_quantity->decrement('quantity', $item->quantity);
//                         if ($request->quantity[$counter] <= $from_quantity->quantity) {
//                             // $from_quantity->decrement('quantity', $request->quantity[$counter]);
//                             // $to_quantity->increment('quantity', $request->quantity[$counter]);
//                             $item->update(['quantity' => $request->quantity[$counter]]);
//                             $store_to=Store::where('id',$load->to_id)->first();
//                             if ($store_to) {
//                                 $this->push_notification_update(['user_id' => $store_to->user_id,'url'=>url('pending-loads'), 'message' => 'تم التعديل في تحميل الخامات عملية رقم  '. $oldData->id]);
//                             }
//                         } else {
//                             flash()->error('الكمية المتاحة أقل من الكمية المطلوبة');
//                             return back();
//                         }
//                     }
//                 }
//             }
//             $counter++;
//         }
//         //
//         flash('تمت العمليه بنجاح')->success();
//         return back();
// //         return redirect()->route('load.index');
//     }

//     public function delete_load_item($id)
//     {
//         $oldData = LoadDetail::where('id', $id)->first();
//         $properties = [
//             'old_data' => $oldData,
//         ];
//         $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'load-details', $oldData->load_id, $this->dateNow, $this->timeNow, $properties, null );

//         $item = LoadDetail::where('id', $id)->first();
//         if ($item) {
//             $load = Load::where('id', $item->load_id)->first();
//             if ($load) {
//                 if ($item->pending == 0) {
//                     $from_quantity = Quantity::where('item_id', $item->item_id)
//                         ->where('ownerable_id', $load->from_id)
//                         ->where('ownerable_type', 'App\Models\Store')->first();

//                     $to_quantity = Quantity::where('item_id', $item->item_id)
//                         ->where('ownerable_id', $load->to_id)
//                         ->where('ownerable_type', 'App\Models\Store')->first();
//                     $from_quantity->increment('quantity', $item->quantity);
//                     $to_quantity->decrement('quantity', $item->quantity);
//                 }
//             }
//         }
//         $item->delete();
//         // $store_to=Store::where('id',$oldData->to_id)->first();
//         // if ($store_to) {
//         //     $this->push_notification_update(['user_id' => $store_to->user_id,'url'=>url('pending-loads'), 'message' => 'تم التعديل في تحميل الخامات عملية رقم  '. $oldData->id]);
//         // }
//         flash()->success('تم الحذف بنجاح');
//         return back();
//     }

//     public function destroy(Load $load)
//     {
//         $oldData = Load::find($load->id);
//         $oldId   = $oldData->id;
//         // dd($oldId);
//         $loadDetailsArr = $oldData->loadDetails->where('pending',0);
//         if(!$loadDetailsArr->isEmpty()) {
//             flash()->error(' لا يمكن الحذف لان تم استلام اصناف من قبل المستقبل ');
//             return back();
//         }
//         $properties = [
//             'old_data' => $oldData,
//         ];
//         $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'loads', $load->id, $this->dateNow, $this->timeNow, $properties, null );

//         $load->deleteLoad();
//         $store_to = Store::where('id',$oldData->to_id)->first();
//         if ($store_to) {
//             $this->push_notification_update(['user_id' => $store_to->user_id,'url'=>url('pending-loads'), 'message' => 'تم حذف تحميل الخامات عملية رقم  '. $oldData->id]);
//         }
//         flash('تمت العمليه بنجاح')->success();
//         return redirect()->route('load.index');
//     }

//     public function push_notification($message)
//     {
//         $options = array(
//             'cluster' => 'eu',
//             'useTLS' => true
//         );
//         $pusher = new Pusher(
//             'e75d58425f4b10f93cfb',
//             '49edd2fdb43527c84354',
//             '417914',
//             $options
//         );
//         $data['message'] = $message;
//         $pusher->trigger('my-channel', 'my-event', $data);
//         return true;
//     }
//     public function push_notification_update($message)
//     {
//         $options = array(
//             'cluster' => 'eu',
//             'useTLS' => true
//         );
//         $pusher = new Pusher(
//             'e75d58425f4b10f93cfb',
//             '49edd2fdb43527c84354',
//             '417914',
//             $options
//         );
//         $data['message'] = $message;
//         $pusher->trigger('my-channel', 'my-event', $data);
//         return true;
//     }
// }



use App\Models\Load;
use App\Models\LoadDetail;
use App\Models\Quantity;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\QuantityDailies;
use App\Models\Group;
use App\DataTables\LoadDataTable;
use App\DataTables\LoadDetailsDataTable;
use App\Utils\Util;
use Carbon\Carbon;
use Pusher\Pusher;
use Illuminate\Support\Facades\DB;

class LoadController extends Controller
{
    protected $util;
    
    protected $dateNow;

    protected $timeNow;

    public function __construct(Util $util) {
        $this->util = $util;
        $this->dateNow = Carbon::now()->format('Y-m-d');
        $this->timeNow = Carbon::now()->format('H:i:s');
    }

    public function index(LoadDataTable $dataTable) {
        return $dataTable->render('load.index');
    }

    public function create(Request $request) {
        $store = Store::where('id', $request->store_id)->first();
        $groups = Group::whereHas('items', function ($query) use ($store) {
            $query->whereHas('quantities', function ($query) use ($store) {
                $query->where('ownerable_type', 'App\Models\Store')
                    ->where('ownerable_id', $store->id);
            });
        })->get();
        $to = Store::where('id', '!=', $request->store_id)->get();
        return view('load.create', compact('store', 'to', 'groups'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'group_id' => 'required',
            'item_id' => 'required',
            'quantity' => 'required',
            'date' => 'required|date',
            'to_store_id' => 'required',
            'from_store_id' => 'required',
        ]);

        DB::beginTransaction();
        $resource = Load::create([
            'user_id' => auth()->user()->id,
            'date' => $request->date,
            'from_id' => $request->from_store_id,
            'to_id' => $request->to_store_id,
            'notes' => $request->notes,
        ]);

        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'loads', $resource->id, $this->dateNow, $this->timeNow, null, null );

        $counter = 0;
        foreach($request->group_id as $group) {
            $from_quantity = Quantity::where('item_id', $request->item_id[$counter])
                ->where('ownerable_id', $request->from_store_id)
                ->where('ownerable_type', 'App\Models\Store')->first();

            if($from_quantity) {
                QuantityDailies::create([
                    'ownerable_id'=>$request->from_store_id,
                    'ownerable_type'=>'App\Models\Store',
                    'item_id'=>$request->item_id[$counter],
                    'quantity'=>$request->quantity[$counter],
                    'type' => '6', // from Store
                ]);

                if($from_quantity->quantity >= $request->quantity[$counter]) {
                    if(Auth()->user()->id == 1) {
                        // $from_quantity->decrement('quantity', $request->quantity[$counter]);
                        $to_quantity = Quantity::where('item_id', $request->item_id[$counter])
                            ->where('ownerable_id', $request->to_store_id)
                            ->where('ownerable_type', 'App\Models\Store')->first();
                        $store_to=Store::where('id',$request->to_store_id)->first();
                        if ($store_to) {
                            QuantityDailies::create([
                                'ownerable_id'=>$request->to_store_id,
                                'ownerable_type'=>'App\Models\Store',
                                'item_id'=>$request->item_id[$counter],
                                'quantity'=>$request->quantity[$counter],
                                'type' => '7', // To store 
                            ]);
                            // $this->push_notification_update(['user_id' => $store_to->user_id,'url'=>url('pending-loads'), 'message' => 'تمت اضافة عملية جديده في تحميل الخامات']);
                        }
                        if ($to_quantity) {
                            // $to_quantity->increment('quantity', $request->quantity[$counter]);

                        }
                    }
                    $store_to=Store::where('id',$request->to_store_id)->first();
                    if ($store_to) {
                        // $this->push_notification_update(['user_id' => $store_to->user_id,'url'=>url('pending-loads'), 'message' => 'تمت اضافة عملية جديده في تحميل الخامات']);
                    }

                    LoadDetail::create([
                        'load_id' => $resource->id,
                        'item_id' => $request->item_id[$counter],
                        'quantity' => $request->quantity[$counter],
                        'status' => 'pending'
                    ]);
                    
                } else {
                    flash('الكمية المطلوبة أقل من الكمية المتاحة')->error();
                }
            }
            $counter++;
        }
        DB::commit();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('load.index');
    }

    public function load_print_from($id) {
        $resource = Load::with('loadDetails.item')->where('id', $id)->first();
        return view('load.printFrom',compact('resource'));
    }
    
    public function load_print_to($id) {
        $resource = Load::with('loadDetails.item')->where('id', $id)->first();
        return view('load.printTo',compact('resource'));
    }
    
    public function show(Load $load, LoadDetailsDataTable $dataTable) {
        return $dataTable->render('load.show', ['load' => $load]);
    }

    public function edit($id) {
        $resource = Load::with('loadDetails.item')->where('id', $id)->first();
        return view('load.edit', compact('resource'));
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'quantity' => 'required',
            'detail_id' => 'required'
        ]);

        $loadDetailsArr = LoadDetail::whereIn('id', $request->detail_id)->where('status', 'accepted')->get();

        if(!$loadDetailsArr->isEmpty()) {
            flash()->error('لا يمكن التعديل لان تم استلام اصناف من قبل المستقبل برجاء المحاوله مرة اخري');
            return back();
        }

        $oldData = Load::where('id', $id)->first();

        $newData = $request->all();

        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'loads', $oldData->id, $this->dateNow, $this->timeNow, $properties, null );

        $counter = 0;
        foreach($request->detail_id as $value) {
            $item = LoadDetail::where('id', $request->detail_id[$counter])->first();
            if($item) {
                $load = Load::where('id', $item->load_id)->first();
                if($load) {
                    if($item->status != 'accepted') {
                        // dd('vb');
                        $from_quantity = Quantity::where('item_id', $item->item_id)
                            ->where('ownerable_id', $load->from_id)
                            ->where('ownerable_type', 'App\Models\Store')->first();

                        $to_quantity = Quantity::where('item_id', $item->item_id)
                            ->where('ownerable_id', $load->to_id)
                            ->where('ownerable_type', 'App\Models\Store')->first();

                        // $from_quantity->increment('quantity', $item->quantity);
                        // $to_quantity->decrement('quantity', $item->quantity);
                        if ($request->quantity[$counter] <= $from_quantity->quantity) {
                            // $from_quantity->decrement('quantity', $request->quantity[$counter]);
                            // $to_quantity->increment('quantity', $request->quantity[$counter]);
                            $item->update(['quantity' => $request->quantity[$counter]]);
                            $store_to=Store::where('id',$load->to_id)->first();
                            if ($store_to) {
                                // $this->push_notification_update(['user_id' => $store_to->user_id,'url'=>url('pending-loads'), 'message' => 'تم التعديل في تحميل الخامات عملية رقم  '. $oldData->id]);
                            }
                        } else {
                            flash()->error('الكمية المتاحة أقل من الكمية المطلوبة');
                            return back();
                        }

                        $load->update(['status'=> 'pending']);
                        $item->update(['status'=> 'pending']);
                    }
                }
            }
            $counter++;
        }

        flash('تمت العمليه بنجاح')->success();
        return back();
    }

    public function delete_load_item($id) {
        $oldData = LoadDetail::where('id', $id)->first();

        $properties = [
            'old_data' => $oldData,
        ];

        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'load-details', $oldData->load_id, $this->dateNow, $this->timeNow, $properties, null );

        $item = LoadDetail::where('id', $id)->first();

        // under testing > elbatal
        // if($item) {
        //     $load = Load::where('id', $item->load_id)->first();

        //     if($load) {
        //         if($item->status == 'accepted') {
        //             $from_quantity = Quantity::where('item_id', $item->item_id)
        //                 ->where('ownerable_id', $load->from_id)
        //                 ->where('ownerable_type', 'App\Models\Store')->first();

        //             $to_quantity = Quantity::where('item_id', $item->item_id)
        //                 ->where('ownerable_id', $load->to_id)
        //                 ->where('ownerable_type', 'App\Models\Store')->first();

        //             $from_quantity->increment('quantity', $item->quantity);
        //             $to_quantity->decrement('quantity', $item->quantity);
        //         }
        //     }
        // }
        
        $item->delete();
        // $store_to=Store::where('id',$oldData->to_id)->first();
        // if ($store_to) {
        //     $this->push_notification_update(['user_id' => $store_to->user_id,'url'=>url('pending-loads'), 'message' => 'تم التعديل في تحميل الخامات عملية رقم  '. $oldData->id]);
        // }
        flash()->success('تم الحذف بنجاح');
        return back();
    }

    public function destroy(Load $load) {
        $oldData = Load::find($load->id);
        $oldId   = $oldData->id;

        $loadDetailsArr = $oldData->loadDetails->where('status', '!=', 'pending');

        if(!$loadDetailsArr->isEmpty()) {
            flash()->error(' لا يمكن الحذف لانه تم استلام اصناف من قبل المستقبل');
            return back();
        }

        $properties = [
            'old_data' => $oldData,
        ];

        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'loads', $load->id, $this->dateNow, $this->timeNow, $properties, null );

        $load->deleteLoad();
        $store_to = Store::where('id', $oldData->to_id)->first();
        if ($store_to) {
            // $this->push_notification_update(['user_id' => $store_to->user_id,'url'=>url('pending-loads'), 'message' => 'تم حذف تحميل الخامات عملية رقم  '. $oldData->id]);
        }
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('load.index');
    }
    
    
    public function pendingLoads() {
        $query = Load::with('user', 'loadDetails', 'from', 'to')
            ->where('status', 'accepted')
            ->whereHas('loadDetails', function ($query) {
                $query->where('status', 'pending');
            });

        if(!auth()->user()->hasRole('admin'))
            $query->where('to_id', auth()->user()->store_id);
        
        $loads = $query->paginate(10);

        return view('load.final.index', compact('loads'));
    }

    // public function acceptLoad(Request $request) {
    //     $detail = LoadDetail::where('id', $request->id)->first();

    //     if(!$detail) {
    //         flash()->error('لقد تم حذف الصنف من الراسل');
    //         return back();
    //     }

    //     $exist = Quantity::where([
    //         'ownerable_type' => 'App\Models\Store',
    //         'ownerable_id' => $detail->parent->from_id,
    //         'item_id' => $detail->item_id
    //     ])->first();

    //     if($exist && $detail->status == 'pending') {
    //         if($exist->quantity >= $detail->quantity) {
    //             Quantity::where([
    //                 'ownerable_type' => 'App\Models\Store',
    //                 'ownerable_id' => $detail->parent->from_id,
    //                 'item_id' => $detail->item_id
    //             ])->decrement('quantity', $detail->quantity);

    //             Quantity::firstOrCreate([
    //                 'ownerable_type' => 'App\Models\Store',
    //                 'ownerable_id' => $detail->parent->to_id,
    //                 'item_id' => $detail->item_id
    //             ])->increment('quantity', $detail->quantity);

    //             $detail->update(['status' => 'accepted']);

    //             if(!empty($request->notes))
    //                 $detail->update(['notes' => $request->notes]);
    //         }
    //     }
        
    //     $load = Load::where('id', $detail->load_id)->first();
    //     $load->update(['receiver_id' => auth()->user()->id]);

    //     flash()->success('تم قبول الصنف بنجاح');
    //     return back();
    // }

    // public function refuseLoad(Request $request) {
    //     $detail = LoadDetail::where('id', $request->id)->first();

    //     if(!$detail) {
    //         flash()->error('لقد تم حذف الصنف من الراسل');
    //         return back();
    //     }

    //     $detail->update(['status' => 'refused']);

    //     if(!empty($request->notes))
    //         $detail->update(['notes' => $request->notes]);
            
    //     $load = Load::where('id', $detail->load_id)->first();
    //     $load->update(['receiver_id' => auth()->user()->id]);

    //     flash()->success('تم رفض الصنف بنجاح');
    //     return back();
    // }
    

    public function acceptLoad(Request $request)
    {
        // Check if we have multiple IDs (comma-separated)
        if (strpos($request->id, ',') !== false) {
            $ids = explode(',', $request->id);
            $loadId = null;
            $allAccepted = true;

            DB::beginTransaction();
            try {
                foreach ($ids as $id) {
                    $detail = LoadDetail::where('id', $id)->first();
                    if (!$detail) {
                        continue; // Skip if detail not found
                    }

                    // Store the load ID for later use
                    if (!$loadId) {
                        $loadId = $detail->load_id;
                    }

                    $exist = Quantity::where([
                        'ownerable_type' => 'App\Models\Store',
                        'ownerable_id' => $detail->parent->from_id,
                        'item_id' => $detail->item_id
                    ])->first();

                    if ($exist && $detail->status == 'pending') {
                        if ($exist->quantity >= $detail->quantity) {
                            Quantity::where([
                                'ownerable_type' => 'App\Models\Store',
                                'ownerable_id' => $detail->parent->from_id,
                                'item_id' => $detail->item_id
                            ])->decrement('quantity', $detail->quantity);

                            Quantity::firstOrCreate([
                                'ownerable_type' => 'App\Models\Store',
                                'ownerable_id' => $detail->parent->to_id,
                                'item_id' => $detail->item_id
                            ])->increment('quantity', $detail->quantity);

                            $detail->update(['status' => 'accepted']);

                            if (!empty($request->notes)) {
                                $detail->update(['notes' => $request->notes]);
                            }
                        } else {
                            $allAccepted = false;
                        }
                    }
                }

                // Update the load's receiver_id
                if ($loadId) {
                    $load = Load::where('id', $loadId)->first();
                    $load->update(['receiver_id' => auth()->user()->id]);

                    // Check if all details for this load are now accepted
                    $pendingDetails = LoadDetail::where('load_id', $loadId)
                        ->where('status', 'pending')
                        ->count();

                    if ($pendingDetails == 0) {
                        $load->update(['status' => 'accepted']);
                    }
                }

                DB::commit();

                if ($allAccepted) {
                    flash()->success('تم قبول الاصناف بنجاح');
                } else {
                    flash()->warning('تم قبول بعض الاصناف، والبعض الآخر لم يتم قبوله بسبب نقص الكمية');
                }

                return back();
            } catch (\Exception $e) {
                DB::rollback();
                flash()->error('حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage());
                return back();
            }
        }

        // Handle single item acceptance (existing code)
        $detail = LoadDetail::where('id', $request->id)->first();

        if (!$detail) {
            flash()->error('لقد تم حذف الصنف من الراسل');
            return back();
        }

        $exist = Quantity::where([
            'ownerable_type' => 'App\Models\Store',
            'ownerable_id' => $detail->parent->from_id,
            'item_id' => $detail->item_id
        ])->first();

        if ($exist && $detail->status == 'pending') {
            if ($exist->quantity >= $detail->quantity) {
                Quantity::where([
                    'ownerable_type' => 'App\Models\Store',
                    'ownerable_id' => $detail->parent->from_id,
                    'item_id' => $detail->item_id
                ])->decrement('quantity', $detail->quantity);

                Quantity::firstOrCreate([
                    'ownerable_type' => 'App\Models\Store',
                    'ownerable_id' => $detail->parent->to_id,
                    'item_id' => $detail->item_id
                ])->increment('quantity', $detail->quantity);

                $detail->update(['status' => 'accepted']);

                if (!empty($request->notes))
                    $detail->update(['notes' => $request->notes]);
            } else {
                flash()->error('الكمية المتاحة أقل من الكمية المطلوبة');
                return back();
            }
        }

        $load = Load::where('id', $detail->load_id)->first();
        $load->update(['receiver_id' => auth()->user()->id]);

        // Check if all details for this load are now accepted
        $pendingDetails = LoadDetail::where('load_id', $detail->load_id)
            ->where('status', 'pending')
            ->count();

        if ($pendingDetails == 0) {
            $load->update(['status' => 'accepted']);
        }

        flash()->success('تم قبول الصنف بنجاح');
        return back();
    }

    public function refuseLoad(Request $request)
    {
        // Check if we have multiple IDs (comma-separated)
        if (strpos($request->id, ',') !== false) {
            $ids = explode(',', $request->id);
            $loadId = null;

            DB::beginTransaction();
            try {
                foreach ($ids as $id) {
                    $detail = LoadDetail::where('id', $id)->first();

                    if (!$detail) {
                        continue; // Skip if detail not found
                    }

                    // Store the load ID for later use
                    if (!$loadId) {
                        $loadId = $detail->load_id;
                    }

                    if ($detail->status == 'pending') {
                        $detail->update(['status' => 'refused']);

                        if (!empty($request->notes)) {
                            $detail->update(['notes' => $request->notes]);
                        }
                    }
                }

                // Update the load's receiver_id
                if ($loadId) {
                    $load = Load::where('id', $loadId)->first();
                    $load->update(['receiver_id' => auth()->user()->id]);

                    // Check if all details for this load are now processed (accepted or refused)
                    $pendingDetails = LoadDetail::where('load_id', $loadId)
                        ->where('status', 'pending')
                        ->count();

                    if ($pendingDetails == 0) {
                        $load->update(['status' => 'accepted']);
                    }
                }

                DB::commit();
                flash()->success('تم رفض الاصناف بنجاح');
                return back();

            } catch (\Exception $e) {
                DB::rollback();
                flash()->error('حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage());
                return back();
            }
        }

        // Handle single item refusal (existing code)
        $detail = LoadDetail::where('id', $request->id)->first();

        if (!$detail) {
            flash()->error('لقد تم حذف الصنف من الراسل');
            return back();
        }

        if ($detail->status == 'pending') {
            $detail->update(['status' => 'refused']);

            if (!empty($request->notes))
                $detail->update(['notes' => $request->notes]);
        }

        $load = Load::where('id', $detail->load_id)->first();
        $load->update(['receiver_id' => auth()->user()->id]);

        // Check if all details for this load are now processed (accepted or refused)
        $pendingDetails = LoadDetail::where('load_id', $detail->load_id)
            ->where('status', 'pending')
            ->count();

        if ($pendingDetails == 0) {
            $load->update(['status' => 'accepted']);
        }

        flash()->success('تم رفض الصنف بنجاح');
        return back();
    }

    
    public function setEmployees(Request $request) {
        $validator = validator($request->all(), [
            'employee_id' => 'required',
        ], [
            'employee_id.required' => 'بالرجاء اختيار الموظفين القائمين على التحميل',
        ]);

        if($validator->fails()) {
            flash()->error($validator->errors()->first());
            return back();
        }

        $load = Load::where('id', $request->id)->first();

        if(!$load) {
            flash()->error('لقد تم حذف التحويل من الراسل');
            return back();
        }

        $load->receiveEmployees()->attach($request->employee_id, ['type' => 'receive']);

        flash()->success('تم اضافة الموظفين القائمين على التحميل بنجاح');
        return back();
    }


    public function checkLoads() {
        $query = Load::with('user', 'loadDetails', 'from', 'to')
            ->where('status', 'pending')
            ->whereHas('loadDetails', function ($query) {
                $query->where('status', 'pending');
            });

        if(!auth()->user()->hasRole('admin'))
            $query->where('from_id', auth()->user()->store_id);

        $loads = $query->paginate(10);
        
        return view('load.check.index', compact('loads'));
    }
    
    public function acceptCheckLoad(Request $request) {
        $validator = validator($request->all(), [
            'notes' => 'nullable',
            'employee_id' => 'required',
        ], [
            'employee_id.required' => 'بالرجاء اختيار الموظفين القائمين على التحميل',
        ]);

        if($validator->fails()) {
            flash()->error($validator->errors()->first());
            return back();
        }

        $load = Load::where('id', $request->id)->first();

        if(!$load) {
            flash()->error('لقد تم حذف التحويل من الراسل');
            return back();
        }

        $load->update(['status' => 'accepted', 'sender_id' => auth()->user()->id]);

        if(!empty($request->notes))
            $load->update(['notes' => $request->notes]);

        $load->sendEmployees()->attach($request->employee_id, ['type' => 'send']);

        $store_from = Store::where('id', $load->from_id)->first();
        if($store_from) {
            $this->push_notification_update(['user_id' => $store_from->user_id, 'url'=>url('loads'), 'message' => 'تم قبول التحويل رقم ' . $load->id . ' بعد عملية الفحص']);
        }

        flash()->success('تم قبول التحويل بنجاح وسوف يتم ارساله الى مصنع ' . $load->to->name);
        return back();
    }

    public function refuseCheckLoad(Request $request) {
        $load = Load::where('id', $request->id)->first();

        if(!$load) {
            flash()->error('لقد تم حذف التحويل من الراسل');
            return back();
        }

        $load->update(['status' => 'refused', 'sender_id' => auth()->user()->id]);

        if(!empty($request->notes))
            $load->update(['notes' => $request->notes]);

        $store_from = Store::where('id', $load->from_id)->first();
        if($store_from) {
            $this->push_notification_update(['user_id' => $store_from->user_id, 'url'=>url('loads'), 'message' => 'تم رفض التحويل رقم ' . $load->id . ' بعد عملية الفحص']);
        }

        flash()->success('تم رفض التحويل بنجاح');
        return back();
    }

    public function push_notification_update($message) {
        $options = array(
            'cluster' => 'eu',
            'useTLS' => true
        );
        $pusher = new Pusher(
            'e75d58425f4b10f93cfb',
            '49edd2fdb43527c84354',
            '417914',
            $options
        );
        $data['message'] = $message;
        $pusher->trigger('my-channel', 'my-event', $data);
        return true;
    }
}