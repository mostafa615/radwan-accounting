@extends('layout.app')
@section('title','التقارير')
@section('sub-title','حساب مبيعات الخزنه')
@section('content')
<div class="box">
    <div class="box-header">
    </div>
    <div class="box-body">
        <table class="table table-bordered" id="example_1" >
            
            @php 
                $in = $lastBalance;
                $out = 0;
                $final = 0;
            @endphp
            <thead>
                <tr>
                    <td>أجل</td>
                    <td>وارد</td>
                    <td>منصرف</td>
                    <td>العميل</td>
                    @if($report_type == 'safe')
                    <td>المخزن</td>
                    <td>الكمية</td>
                    <td>السعر</td>
                    <td>البيان</td>
                    @endif 
                </tr>
                @if($report_type == 'safe')
                <tr>
                    <td>.</td>
                    <td>
                        {{ $lastBalance }}
                        <!--
                        
                        {{$resource->balance - ($orders_in->sum('cost') + $pay_in->sum('cost')+ $dialies_in->sum('cost')+$transactions_to->sum('cost') - $orders_out->sum('cost') - $pay_out->sum('cost') - $dialies_out->sum('cost') ) }}
                        -->
                    </td>
                    <td>.</td>
                    <td>.</td>
                    <td>.</td>
                    <td>.</td>
                    <td>.</td>
                    <td>رصيد أمس</td>
                </tr>
                @endif
            </thead>
            <tbody>
                @foreach($orders_in->toArray() as $item)
            @php 
                $in += $item['cost'];
            @endphp
                    <tr>
                        <td >{{$item['rest']}}</td>
                        <td >{{$item['cost']}}</td>
                        <td >0</td>
                        <td >{{optional($item['ownerable'])['name']}}</td>
                    @if($report_type == 'safe')
                        @if (isset($item['order_details']))
                            @foreach($item['order_details'] as $detail) 
                                <td>{{optional($detail['store'])['name']}}</td>
                                <td>{{$detail['quantity']}} </td>
                                <td>{{$detail['unite_price']}} </td>
                                <td>{{optional($detail['item'])['name']}}
                                <a target="_blank" href="{{ url('/') }}/orders-in/{{ $item['id'] }}" >{{ $item["id"] }}</a></td> 
                            @endforeach
                        @else
                            <td>.</td>
                            <td>.</td>
                            <td>.</td>
                            <td><a target="_blank" href="{{ url('/') }}/orders-in/{{ $item['id'] }}" >{{ $item["id"] }}</a></td> 
                        @endif
                    @endif
                    @if($report_type != 'safe')
                        <td>.</td>
                        <td>.</td>
                        <td>.</td>
                            <td><a target="_blank" href="{{ url('/') }}/orders-in/{{ $item['id'] }}" >{{ $item["id"] }}</a></td> 
                    @endif
                    </tr>
                @endforeach
            @foreach($orders_out->toArray() as $item)
            @php 
                $out += $item['cost'];
            @endphp
                <tr>
                    <td >0</td>
                    <td >0</td>
                    <td >{{$item['cost']}}</td>
                    <td >
                        {{optional($item['ownerable'])['name']}}
                    </td>
                @if($report_type == 'safe')
                    @if (isset($item['order_details']))
                        @foreach($item['order_details'] as $detail) 
                            <td>{{optional($detail['store'])['name']}}</td>
                            <td>{{$detail['quantity']}} </td>
                            <td>{{$detail['unite_price']}} </td>
                            <td>{{optional($detail['item'])['name']}}
                            <a target="_blank" href="{{ url('/') }}/orders-out/{{ $item['id'] }}" >{{ $item["id"] }}</a>
                            </td> 
                            </td> 
                        @endforeach 
                    @else
                        <td>.</td>
                        <td>.</td>
                        <td>.</td>
                            <td>
                            <a target="_blank" href="{{ url('/') }}/orders-out/{{ $item['id'] }}" >{{ $item["id"] }}</a></td> 
                    @endif
                @endif
                @if($report_type != 'safe')
                    <td>.</td>
                    <td>.</td>
                    <td>.</td>
                            <td>
                            <a target="_blank" href="{{ url('/') }}/orders-out/{{ $item['id'] }}" >{{ $item["id"] }}</a></td> 
                @endif
                </tr>
            @endforeach
            @foreach($pay_in->toArray() as $item)
            @php 
                $in += $item['cost'];
            @endphp
            <tr>
                <td>0</td>
                <td>{{$item['cost']}}</td>
                <td>0</td>
                <td>
                    {{optional($item['accountable'])['name']}}
                </td>
                @if($report_type == 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>استلام نقدية</td>
                @endif
                @if($report_type != 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>.</td>
                @endif
            </tr>
            @endforeach
            @foreach($pay_out->toArray() as $item)
            @php 
                $out += $item['cost'];
            @endphp
            <tr>
                <td>0</td>
                <td>0</td>
                <td>{{$item['cost']}}</td>
                <td>
                    {{optional($item['accountable'])['name']}}
                </td>
                @if($report_type == 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>صرف نقدية</td>
                @endif
                @if($report_type != 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>.</td>
                @endif
            </tr>
            @endforeach
            @foreach($dialies_out->toArray() as $item)
            @php 
                $out += $item['cost'];
            @endphp
            <tr>
                <td>0</td>
                <td>0</td>
                <td>{{$item['cost']}}</td>
                <td>.</td>
                @if($report_type == 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>{{$item['text']}}</td>
                @endif
                @if($report_type != 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>.</td>
                @endif
            </tr>
            @endforeach
            @foreach($dialies_in->toArray() as $item)
            @php 
                $in += $item['cost'];
            @endphp
            <tr>
                <td>0</td>
                <td>{{$item['cost']}}</td>
                <td>0</td>
                <td>.</td>
                @if($report_type == 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>{{$item['text']}}</td>
                @endif
                @if($report_type != 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>.</td>
                @endif
            </tr>
            @endforeach
            @foreach($salaries as $item)
            @php 
                $out += $item->net;
                $dateSalary = Carbon\Carbon::parse($item->date)->format('m');
            @endphp
            <tr>
                <td>0</td>
                <td>0</td>
                <td>{{ $item->net }}</td>
                <td>.</td>
                @if($report_type == 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>
                    مرتب ({{$dateSalary}})  {{ App\Models\Employee::find($item->employee_id)->name }}
                </td>
                @endif
                @if($report_type != 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>.</td>
                @endif
            </tr>
            @endforeach
            @foreach($loans as $item)
            @php 
                $out += $item->cost;
            @endphp
            <tr>
                <td>0</td>
                <td>0</td>
                <td>{{ $item->cost }}</td>
                <td>.</td>
                @if($report_type == 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td> 
                    @if ($item->type == "solfa")
                    سلفة  {{ App\Models\Employee::find($item->employee_id)->name }}
                    @else
                    مديونية  {{ App\Models\Employee::find($item->employee_id)->name }}
                    @endif
                </td>
                @endif
                @if($report_type != 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>.</td>
                @endif
            </tr>
            @endforeach
            @foreach($transactions_from->toArray() as $item)
            @php 
                $out += $item['cost'];
            @endphp
            <tr>
                <td>0</td>
                <td>0</td>
                <td>{{$item['cost']}}</td>
                <td>.</td>
                @if($report_type == 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>
                    تحويل الي
                    {{$item['to']['name']}}
                </td>
                @endif
                @if($report_type != 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>.</td>
                @endif
            </tr>
            @endforeach
            @foreach($transactions_to->toArray() as $item)
            @php 
                $in += $item['cost'];
            @endphp
            <tr>
                <td>0</td>
                <td>{{$item['cost']}}</td>
                <td>0</td>
                <td>.</td>
                @if($report_type == 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>
                    تحويل من
                    {{$item['from']['name']}}
                </td>
                @endif
                @if($report_type != 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>.</td>
                @endif
            </tr>
            @endforeach
            
            </tbody>
            <tfoot> 
                <tr>
                <td>{{$orders_in->sum('rest')}}</td>
                <td>
                    @php
                        // old formula for in 
                        //$i = ($resource->balance - ($orders_in->sum('cost') + $pay_in->sum('cost')+ $dialies_in->sum('cost')+$transactions_to->sum('cost') - $orders_out->sum('cost') - $pay_out->sum('cost') - $dialies_out->sum('cost') - $transactions_from->sum('cost'))) + $orders_in->sum('cost') + $pay_in->sum('cost')+ $dialies_in->sum('cost')+$transactions_to->sum('cost');
                    @endphp
                    {{ $in }}
                </td>
                <td>
                    @php
                        // old formula for out
                        // $o = $orders_out->sum('cost') + $pay_out->sum('cost') + $dialies_out->sum('cost') + $transactions_from->sum('cost');
                    @endphp
                    {{ $out }}
                    </td>
                <td>.</td>
                @if($report_type == 'safe')
                <td>الصافي</td>
                <td>
                    @php
                        // old formula for final 
                        //$final = ($resource->balance - ($orders_in->sum('cost') + $pay_in->sum('cost')+ $dialies_in->sum('cost')+$transactions_to->sum('cost') - $orders_out->sum('cost') - $pay_out->sum('cost') - $dialies_out->sum('cost') - $transactions_from->sum('cost'))) + $orders_in->sum('cost') + $pay_in->sum('cost')+ $dialies_in->sum('cost')+$transactions_to->sum('cost') - $orders_out->sum('cost') - $pay_out->sum('cost') - $dialies_out->sum('cost') - $transactions_from->sum('cost');
                        
                        $final = $in - $out;
                    @endphp
                    {{ $final }}
                    </td>
                <td>.</td>
                <td>.</td>
                @endif
                @if($report_type != 'safe')
                <td>.</td>
                <td>.</td>
                <td>.</td>
                <td>.</td>
                @endif
            </tr>
            </tfoot>
        </table>
        <!--
        <h1 style="color: green" >
            {{ $in }}
        </h1>
        <h1 style="color: red" >
            {{ $out }}
        </h1>
        
        -->
        @php
            DB::table('reposite_balances')->insert([
                "reposite_id" => $resource->id,
                "balance" => $final,
                "date" => request()->date_to,
                "created_at" => date('Y-m-d H:i:s'), 
                "updated_at" => date('Y-m-d H:i:s'),
            ]);
            $resource->update([ "last_balance" => $resource->balance ]);
        @endphp
    </div>
</div>
@stop
