<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Reposite;
use Illuminate\Http\Request;

use App\DataTables\AccountsDataTable;


class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AccountsDataTable $dataTable  , $owner , $id)
    {
        $owners = [
            'supplier'=>'App\Models\Supplier',
            'client'=>'App\Models\Client',
        ];
        $owner = $owners[$owner]::findOrFail($id);
        // dd($owner);
        $reposites = Reposite::all();
        $names = $this->names();
        return $dataTable->render('accounts.index',compact('owner','reposites','names'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $names = $this->names();
        // $reposites = Reposite::all();
        if (auth()->user()->id != 1) {
            $reposites = Reposite::where(function ($query) {
                $query->where('branch_id', auth()->user()->branch_id)
                ->orWhere('mainly', 1);
                })->orderby('mainly')->latest()->get();
        }else{
            $reposites = Reposite::all();
        }

        return view('accounts.create',compact('reposites','names'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $route = [
            'supplier'=>'suppliers',
            'client'=>'clients',
        ];
        Account::createAccount($request);
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('accounts.index',[
            'owner'=>$request->accountable_type ,
            'id'=>$request->accountable_id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        //
        $owners = [
            'App\Models\Supplier'=>'supplier',
           'App\Models\Client'=>'client',
        ];
        $reposites = Reposite::all();
        $names = $this->names();
        return view('accounts.edit',compact('reposites','account','owners','names'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        //
        $route = [
            'supplier'=>'suppliers',
            'client'=>'clients',
        ];

        $account->updateAccount($request);
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('accounts.index',[
            'owner'=>$route[get_class($account->accountable)],
            'id'=>$account->accountable_id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        //
        $account->deleteAccount();
        flash('تمت العملية بنجاح')->success();
        return back();

    }


    public function names()
    {
        $inName = 'استلام نقدية';
        $outName = 'صرف نقدية';
        return compact('inName','outName');
    }
}
