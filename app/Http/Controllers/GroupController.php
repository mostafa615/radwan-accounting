<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

use App\DataTables\GroupsDataTable;
use App\Utils\Util;
use Carbon\Carbon;

class GroupController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GroupsDataTable $dataTable)
    {
        return $dataTable->render('groups.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            return view('groups.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $group = Group::create($request->all());
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'groups', $group->id, $this->dateNow, $this->timeNow, null, null );

        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('group.index');        
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {

    }
    
    /**
     * if active == 1  ===> active all items of group
     * if active == 0  ===> deactive all items of group
     */
    public function active(Request $request, Group $group) {  
        foreach($group->items()->get() as $item) {
            $item->active = $request->active;
            $item->update();
        }
        $properties = [
            'old_data' => 'active=0',
            'new_data' => $request->active
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'groups', $group->id, $this->dateNow, $this->timeNow, $properties, null );

        
        return back();
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
            return view('groups.edit',[
                'group'=>$group,
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        // dd($request->all());
        $oldGroup = Group::find($group->id);
        $request_data = $request->all();
        // if ($request_data['edit_by_permission'] == 'on' || $request_data['edit_by_permission'] == 1)
        //     $request_data['edit_by_permission'] = 1;
        // else
        // $request_data['edit_by_permission'] = 0;

        // if($request->price > 0){
        //     if($oldGroup->price != $request->price){
        //         foreach($group->items as $item){
        //             $itemPrice  = round($item->standard_weight*$request->price);
        //             $finalPrice = 0;
                    
        //             if($itemPrice > 0){
        //                 $lastDigit = abs($itemPrice % 10); // 8
        //                 $stringPrice = strval($itemPrice);
    
        //                 if($lastDigit <= 5){
        //                     $newStr = substr_replace($stringPrice, "5", -1);
        //                     $finalPrice = (int)$newStr;
        //                 }else{
        //                     $remain = 10 - $lastDigit;
        //                     $finalPrice = $itemPrice + $remain;
        //                 }
        //             }
                    
        //             $item->update([
        //                 'price' => $finalPrice
        //             ]);
        //         }
        //     }
        // }
        
        if($request->price > 0) {
            if($oldGroup->price != $request->price) {
                foreach($group->items as $item) {
                    $itemPrice = round($item->standard_weight * $request->price);
                    $finalPrice = 0;
            
                    if($itemPrice > 0) {
                        $lastDigit = abs($itemPrice % 10);
            
                        if($lastDigit == 0) {
                            $finalPrice = $itemPrice;
                        } elseif ($lastDigit <= 5) {
                            $finalPrice = $itemPrice - $lastDigit + 5;
                        } else {
                            $finalPrice = $itemPrice - $lastDigit + 10;
                        }
                    }
            
                    $item->update([
                        'price' => $finalPrice
                    ]);
                }
            }
        }

        $newData = $request->all();
        $properties = [
            'old_data' => $oldGroup,
            'new_data' => $newData
        ];

        $this->util->activityLog(auth()->user()->id, 'update', 'accounts', 'groups', $group->id, $this->dateNow, $this->timeNow, $properties, null );

        $group->update($request_data);
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('group.index');      
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $oldData = Group::find($group->id);
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'groups', $group->id, $this->dateNow, $this->timeNow, $properties, null );

        $group->delete();
        flash('تمت العمليه بنجاح')->success();
        return redirect()->route('group.index');  
    }
    
    // public function isEditByPermission(Request $request) {
    //     $group  = Group::findorFail($request->groupId);
    //     $state = $request->status == 1 ? 1 : 0;
    //     $group->update(['edit_by_permission' => $state]);
    //     return response()->json('done');
    // }
    
    public function isEditPermissionS(Request $request) {
        $group  = Group::findorFail($request->groupId);
        $state = $request->status == 1 ? 1 : 0;
        $group->update(['edit_permission_s' => $state]);
        return response()->json('done');
    }

    public function isEditPermissionQ(Request $request) {
        $group  = Group::findorFail($request->groupId);
        $state = $request->status == 1 ? 1 : 0;
        $group->update(['edit_permission_q' => $state]);
        return response()->json('done');
    }

    public function isEditPermissionO(Request $request) {
        $group  = Group::findorFail($request->groupId);
        $state = $request->status == 1 ? 1 : 0;
        $group->update(['edit_permission_o' => $state]);
        return response()->json('done');
    }
}
