<?php
namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Meta;
use App\Models\Group;
use App\Models\Quantity;
use App\Models\Store;
use Illuminate\Http\Request;

use App\DataTables\ItemsDataTable;
use App\Utils\Util;
use Carbon\Carbon;

class ItemController extends Controller
{
    protected $util;
    protected $dateNow;
    protected $timeNow;

    public function __construct(Util $util)
    {
        $this->util = $util;
        $this->dateNow = Carbon::now()->format('Y-m-d');
        $this->timeNow = Carbon::now()->format('H:i:s');
    }
    public function index(ItemsDataTable $dataTable)
    {
        $query = Item::query();
        $query->where('is_damage', null);
        // $query->where('is_special', null);
                
         $resources=$query->with('group','quantities')->get();
        //   dd($resources[1]->quantities->where('ownerable_type','App\Models\Store'));
        return view('items.index',compact('resources'));
    }

    public function create()
    {
        $metas = Meta::all();
        $groups = Group::all();
        return view('items.create', compact('metas', 'groups'));
    }
    public function store(Request $request)
    {

        $oldItem = Item::where('name', $request->name)
                        ->where('length', $request->length)
                        ->where('width', $request->width)
                        ->where('weight_one', $request->weight_one)
                        ->first();
                    
        if ($oldItem)
        {
            return redirect()->back()->withErrors('هذا الصنف موجود مسبقا');
        }else{
            $item = Item::create($request->except('details'));
            $item->code = $item->id;
            $item->save();
            if (is_array($request->details)) {
                $item->detail()->create($request->details);
            }
            $stores=Store::get();
            foreach ($stores as $store){
                Quantity::create([
                    'ownerable_id' => $store->id,
                    'ownerable_type' => 'App\Models\Store',
                    'item_id' => $item->id,
                    'quantity' => 0
                ]);
            }
            $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'items', $item->id, $this->dateNow, $this->timeNow, null, null );

            flash('تمت العمليه بنجاح')->success();
            return redirect()->route('items.index');
        }

        
    }
    public function show(Item $item)
    {
        $metas = Meta::all();
        return view('items.show', [
            'item' => $item,
            'metas' => $metas
        ]);
    }

    public function edit(Item $item)
    {
        $metas = Meta::all();
        $groups = Group::all();
        return view('items.edit', [
            'item' => $item,
            'metas' => $metas,
            'groups' => $groups
        ]);
    }
    
    public function active(Request $request, Item $item) {
        if ($request->active == 1) {
            $item->active = 1;
            $item->update();
            $properties = [
                'old_data' => 'active=0',
                'new_data' => 'active=1'
            ];
    
            $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'items', $item->id, $this->dateNow, $this->timeNow, $properties, null );
    
        } else {
            $item->active = 0;
            $item->update();
            $properties = [
                'old_data' => 'active=1',
                'new_data' => 'active=0'
            ];
    
            $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'items', $item->id, $this->dateNow, $this->timeNow, $properties, null );
    
        }
        
        return back();
    }

    public function update(Request $request, Item $item)
    {
        $oldData = Item::find($item->id);

        $item->update($request->except('details'));
        if (is_array($request->details)) {
            $item->detail()->update($request->details);
        }
        
        $newData = $request->all();
        $properties = [
            'old_data' => $oldData,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'items', $item->id, $this->dateNow, $this->timeNow, $properties, null );

        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('items.index');
    }
    public function destroy(Item $item)
    {
        $oldData = Item::find($item->id);
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'items', $item->id, $this->dateNow, $this->timeNow, $properties, null );

        $item->delete();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('items.index');
    }
    
    public function updateItemsData(Request $request){
        
        foreach($request->resource as $res){
            $weight_one = 0;
            $item = Item::where('id', $res['itemId'])->first();
            $itemInQuantities = Quantity::where('item_id', $res['itemId'])->where('ownerable_type', 'App\Models\Store')->get();
            
           if($res['quantity'] == 0){
                  $weight_one = 0;
            }else{
                   $weight_one = $res['weight'] / $res['quantity'];
            }
            
            foreach($itemInQuantities as $itemInQuantity){
                // dd($itemInQuantity->quantity);
                //  $itemInQuantity->update([
                // 'length' => $res['length'],
                // 'width'=>$res['width'],
                // 'weight_one'=>$weight_one,
                // ]);
            }
          
            $item->update([
                'weight'=>$res['weight'],
                'standard_weight'=>$res['standard_weight'],
                'length' => $res['length'],
                'width'=>$res['width'],
                'thickness'=>$res['thickness'],
                'weight_one'=>$res['weight_one'],
                ]);
 
        }
        
        
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('items.index');
    }
}
