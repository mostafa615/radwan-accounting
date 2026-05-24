<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Loan;
use Yajra\Datatables\Datatables;
use App\Models\Daily;


class PendingPaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        return auth()->user()->id;
        return view('pending-pays.index');
    }


    public function accountsDatatable(Request $request)
    {
        $query = Account::
        select(
            'accounts.id',
            'accounts.cost', 'accounts.type',
            'accounts.created_at', 'accounts.date',
            'reposites.name', 'accounts.order_id',
            'accountable_id',
            'accountable_type'
        )
            ->leftJoin('reposites', 'reposites.id', '=', 'accounts.reposite_id')
            ->where('reposites.user_id', auth()->user()->id)
            ->where('pending', true)
            ->whereDoesntHave('order', function ($query) {
                $query->whereHas('orderDetails', function ($query) {
                    $query->where('load_pending', true);
                });
            })
            ->where('type', $request->account_type)
            ->latest();

        return Datatables::of($query)
            ->editColumn('date', function (Account $account) {
                return optional($account->date)->toDateString();
            })
            ->addColumn('owner', function (Account $account) {
                return optional($account->accountable)->name;
            })
            ->addColumn('action', function (Account $account) {
                return '<button type="button" class="btn btn-success" data-toggle="modal"
                                                data-target="#pay_' . $account->id . '" ><i class="fa fa-check"></i> </button>
                                                <div id="pay_' . $account->id . '" class="modal fade" role="dialog">
                                            <div class="modal-dialog">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                        <h4 class="modal-title">الموافقة علي الدفع</h4>
                                                    </div>
                                                    <form action="' . route('api.pending-pays.account.update', $account->id) . '" enctype="multipart/form-data" method="POST">
                                                    <div class="modal-body">
                                                    <div class="form-group">
                                                    <label>رقم الشيك</label>
                                                    <div class="clearfix"></div>
                                                        <input type="text" name="chaque_no"  class="form-control" placeholder="رقم الشك ">                                                    
</div>
<div class="clearfix"></div>
<div class="form-group">
                                                    <label>صورة الشيك</label>
                                                    <div class="clearfix"></div>
                                                            <input type="file" name="image">
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">إغلاق
                                                        </button>
                                                        <button type="submit" class="btn btn-success" onclick="$(this).attr(\'style\',\'display:none\')"
                                                        
                                                        >موافقة
                                                        </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                                ';
            }/*'pending-pays.accounts.action'*/)
            ->make(true);
    }


    public function transactionsDatatable(Request $request)
    {

        $query = Transaction::
        select('transactions.id', 'cost',
            'transactions.created_at', 'date',
            'reposites.name',
            'users.name as user',
            'transactions.notes'
        )
            ->leftJoin('reposites', 'reposites.id', '=', 'transactions.to_id')
            ->leftJoin('users', 'users.id', '=', 'transactions.user_id')
            ->where('pending', true)
            ->where('reposites.user_id', auth()->user()->id)
            ->latest();

        return Datatables::of($query)
            ->editColumn('date', function (Transaction $account) {
                return optional($account->date)->toDateString();
            })
            ->addColumn('action', 'pending-pays.transactions.action')
            ->make(true);
    }


    public function loansDatatable(Request $request)
    {

        $query = Loan::
        select('loans.id', 'cost',
            'loans.created_at', 'date',
            'reposites.name',
            'employees.name as employee',
            'loans.notes'
        )
            ->leftJoin('reposites', 'reposites.id', '=', 'loans.reposite_id')
            ->leftJoin('employees', 'reposites.id', '=', 'loans.employee_id')
            ->where('pending', true)
            ->where('reposites.user_id', auth()->user()->id)
            ->latest();

        return Datatables::of($query)
            ->editColumn('date', function (Loan $loan) {
                return optional($loan->date)->toDateString();
            })
            ->addColumn('action', 'pending-pays.loans.action')
            ->make(true);
    }


    public function dailiesDatatable(Request $request)
    {

        $query = Daily::
        select('dailies.id', 'cost',
            'dailies.created_at', 'date',
            'dailies.notes',
            'trees.text'
        )
            ->leftJoin('trees', 'dailies.tree_id', '=', 'trees.id')
            ->where('pending', true)
            // ->where('reposite_id', optional(auth()->user()->reposite)->id)
            ->whereHas('reposite',function ($q){
                $q->where('user_id',Auth()->user()->id);
            })
            ->where('dailies.type', $request->daily_type)
            ->latest();

        return Datatables::of($query)
            ->editColumn('date', function (Daily $daily) {
                return optional($daily->date)->toDateString();
            })
            ->addColumn('action', 'pending-pays.dailies.action')
            ->make(true);
    }
}
