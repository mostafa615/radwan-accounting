<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Transport;

class TransportController extends Controller
{
    public function index(Request $request) {
        $dateTo = $request->to;
        $dateFrom = $request->from;

        $query =  Transport::select('transports.id', 'transports.date', 'clients.name as client', 'branches.name as branch', 'transports.cost', 'transports.type')
            ->join('orders', 'orders.id', '=', 'transports.order_id')
            ->join('clients', 'clients.id', '=', 'orders.ownerable_id')
            ->join('branches','branches.id','=','transports.branch_id');

        if($request->driver) {
            $query->where('employee_id', $request->driver);
        }

        if($dateFrom && $dateTo) {
            $query->whereBetween('transports.date', [$dateFrom, $dateTo]);
        }
            
        $transports = $query->get();

        $resource = Employee::where('id', $request->driver)->first();

        return view('reports.transports', compact(['transports', 'resource', 'dateTo', 'dateFrom']));
    }
}