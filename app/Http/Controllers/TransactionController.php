<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Reposite;

use Illuminate\Http\Request;

use App\DataTables\TransactionsDataTable;
use App\Utils\Util;
use Carbon\Carbon;
use Pusher\Pusher;

class TransactionController extends Controller
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
    public function index(TransactionsDataTable $dataTable)
    {
        return $dataTable->render('transactions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $reposite = auth()->user()->reposite()->where('balance', '>=', '1')->first();
        if(auth()->user()->id == 1){
            $reposites = Reposite::get();
        }else{
              $reposites = Reposite::where('user_id', '!=', auth()->user()->id)->get();
        }
//        return $reposites;
        return view('transactions.create', compact('reposite', 'reposites'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'date'=>'required',
            'from_id'=>'required',
            'to_id'=>'required',
            'cost'=>'required',
        ]);
        $transaction = Transaction::create(['cost' => $request->cost,
            'date' => $request->date,
            'user_id' => auth()->user()->id,
            'from_id' => $request->from_id,
            'to_id' => $request->to_id,
            'notes' => $request->notes
        ]);
        $this->util->activityLog(auth()->user()->id, 'create', 'accounts', 'transactions', $transaction->id, $this->dateNow, $this->timeNow, null, null );

        $reposite_to = Reposite::findOrFail($request->to_id);
        if ($reposite_to) {
            $this->push_notification(['user_id' => $reposite_to->user_id,'url'=>url('pending-pays')]);
        }
        if (Auth()->user()->id == 1) {
            $reposite_from = Reposite::findOrFail($request->from_id);
            if ($reposite_from){
                $reposite_from->decrement('balance',$request->cost);
            }

            if ($reposite_to){
                $reposite_to->increment('balance',$request->cost);
            }
//            return 'done';
            $transaction->update(['pending'=>0]);
        }
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('transactions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {

        return view('transactions.show', compact('actor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        $countries = Country::all();
        return view('transactions.edit', compact('actor', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $oldData = Transaction::find($transaction->id);
        $properties = [
            'old_data' => $oldData,
        ];
        $this->util->activityLog(auth()->user()->id, 'delete', 'accounts', 'transactions', $transaction->id, $this->dateNow, $this->timeNow, $properties, null );

        $transaction->delete();
        flash('تمت العملية بنجاح')->success();
        return redirect()->route('transactions.index');
    }
    public function push_notification($message)
    {
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
