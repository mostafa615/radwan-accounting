<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Account;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\Daily;

class PendingPaysController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateAccount(Request $request, Account $account)
    {
        $full_name='';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = storage_path();
            $destinationPath = $path . '/uploads/payments/'; // upload path
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $image->move($destinationPath, $name); // uploading file to given path
            $full_name = 'storage/uploads/payments/' . $name;
        }
        $account->update(['image'=>$full_name,'chaque_no'=>$request->chaque_no]);
        $done = $account->performPending();
        flash('تم الحفظ بنجاح')->success();
        return back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateTransaction(Request $request, Transaction $transaction)
    {
        $done = $transaction->performPending();
        return response()->json([
            'done' => $done
        ]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateLoan(Request $request, Loan $loan)
    {
        $done = $loan->performPending();
        return response()->json([
            'done' => $done
        ]);

    }


    public function updateDaily(Request $request, Daily $daily)
    {
        $done = $daily->performPending();
        return response()->json([
            'done' => $done
        ]);

    }
}
