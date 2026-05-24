@extends('layout.app')

@section('title','التقارير')

@section('sub-title',' كشف حساب عميل مورد ')

@section('content')

    <div class="row">

        <div class="col-md-12">

            <div class="nav-tabs-custom">

                <ul class="nav nav-tabs">

                    <li class="active"><a href="#orders-in-tab" data-toggle="tab" aria-expanded="true">فواتير بيع

                            </a>

                    </li>

                    <li class=""><a href="#orders-out-tab" data-toggle="tab" aria-expanded="false">فواتير مرتجعات بيع

                            </a>

                    </li>

                    <li class="active"><a href="#sorders-in-tab" data-toggle="tab" aria-expanded="true">فواتير شراء

                            </a>

                    </li>

                    <li class=""><a href="#sorders-out-tab" data-toggle="tab" aria-expanded="false">فواتير مرتجعات شراء

                            </a>

                    </li>

                    <li class=""><a href="#accounts-in-tab" data-toggle="tab" aria-expanded="false">مدفوع من </a>

                    </li>

                    <li class=""><a href="#accounts-out-tab" data-toggle="tab" aria-expanded="false">مدفوع الي

                            </a></li>

                    <li class=""><a href="#client-account-tab" data-toggle="tab" aria-expanded="false">كشف حساب </a>

                    </li>

                    <li class=""><a href="#client-info-tab" data-toggle="tab" aria-expanded="false">بيانات </a>

                    </li>

                </ul>

                <div class="tab-content">

                    <div class="tab-pane  active" id="orders-in-tab">

                        <div class="table-responsive">

                            <table width="100%" id="example_5" class="table">

                                <thead>

                                <tr>

                                    <td>#</td>

                                    <td>التاريخ</td>

                                    <td>الاجمالي</td>

                                    <td>المدفوع</td>

                                    <td>المتبقي</td>

                                </tr>

                                </thead>

                                <tbody>

                                <?php

//                                $order_in_total_total=0;

                                ?>

                                @if(!empty($clientBalance["orders_in"]) && $clientBalance["orders_in"]!= null && $clientBalance["orders_in"] != '')

                                    @foreach($clientBalance["orders_in"] as $item)

                                        <tr>

                                            <td>

                                                <a target="_blank" href="{{url('orders-in/'.$item->id)}}">

                                                    {{$item->id}}

                                                </a>

                                            </td>

                                            <td>{{$item->date}}</td>

                                            <td> {{$item->total}}</td>

                                            <td> {{$item->cost}}</td>

                                            <td> {{$item->total - $item->cost - $item->discount}}</td>

                                        </tr>

                                    @endforeach

                                @endif

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <div class="tab-pane  active" id="sorders-in-tab">

                        <div class="table-responsive">

                            <table width="100%" id="example_5" class="table">

                                <thead>

                                <tr>

                                    <td>#</td>

                                    <td>التاريخ</td>

                                    <td>الاجمالي</td>

                                    <td>المدفوع</td>

                                    <td>المتبقي</td>

                                </tr>

                                </thead>

                                <tbody>

                                <?php

//                                $order_in_total_total=0;

                                ?>

                                @if(!empty($supplierBalance["orders_in"]) && $supplierBalance["orders_in"] != null && $supplierBalance["orders_in"] != '')

                                    @foreach($supplierBalance["orders_in"] as $item)

                                        <tr>

                                            <td>

                                                <a target="_blank" href="{{url('orders-in/'.$item->id)}}">

                                                    {{$item->id}}

                                                </a>

                                            </td>

                                            <td>{{$item->date}}</td>

                                            <td>{{$item->total}}</td>

                                            <td>{{$item->cost}}</td>

                                            <td>{{$item->total - $item->cost - $item->discount}}</td>

                                        </tr>

                                    @endforeach

                                @endif

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <!-- /.tab-pane -->

                    <div class="tab-pane fade" id="orders-out-tab">

                        <div class="table-responsive">

                            <table width="100%" id="example_4" class="table table-bordered data-table">

                                <thead>

                                <tr>

                                    <td>#</td>

                                    <td>التاريخ</td>

                                    <td>الاجمالي</td>

                                    <td>المدفوع</td>

                                    <td>المتبقي</td>

                                </tr>

                                </thead>

                                <tbody>

                                @if(!empty($clientBalance["returns"]) && $clientBalance["returns"] != null && $clientBalance["returns"] != '')

                                    @foreach($clientBalance["returns"] as $item)

                                        <tr>

                                            <td>

                                                <a  target="_blank" href="{{url('return-orders-in/'.$item->id)}}">

                                                    {{$item->id}}

                                                </a>

                                            </td>

                                            <td>{{$item->date}}</td>

                                            <td>{{$item->total}}</td>

                                            <td>{{$item->cost}}</td>

                                            <td>{{$item->total - $item->cost - $item->discount}}</td>

                                        </tr>

                                    @endforeach

                                @endif

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="sorders-out-tab">

                        <div class="table-responsive">

                            <table width="100%" id="example_4" class="table table-bordered data-table">

                                <thead>

                                <tr>

                                    <td>#</td>

                                    <td>التاريخ</td>

                                    <td>الاجمالي</td>

                                    <td>المدفوع</td>

                                    <td>المتبقي</td>

                                </tr>

                                </thead>

                                <tbody>

                                @if(!empty($supplierBalance["returns"]) && $supplierBalance["returns"] != null && $supplierBalance["returns"] != '')

                                    @foreach($supplierBalance["returns"] as $item)

                                        <tr>

                                            <td>

                                                <a  target="_blank" href="{{url('return-orders-in/'.$item->id)}}">

                                                    {{$item->id}}

                                                </a>

                                            </td>

                                            <td>{{$item->date}}</td>

                                            <td>{{$item->total}}</td>

                                            <td>{{$item->cost}}</td>

                                            <td>{{$item->total - $item->cost - $item->discount}}</td>

                                        </tr>

                                    @endforeach

                                @endif

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="accounts-in-tab">

                        <div class="table-responsive">

                            <table width="100%" id="example_6" class="table table-bordered data-table">

                                <thead>

                                <tr>

                                    <td>#</td>

                                    <td>التاريخ</td>

                                    <td>الخزنة</td>

                                    <td>المدفوع</td>

                                    <td>رقم الفاتورة</td>

                                </tr>

                                </thead>

                                <tbody>

                                @if(!empty($clientBalance["pay_in"]) && $clientBalance["pay_in"] != null && $clientBalance["pay_in"] != '')

                                    @foreach($clientBalance["pay_in"] as $item)

                                        <tr>

                                            <td>{{$item->id}}</td>

                                            <td>{{$item->date}}</td>

                                            <td>{{optional($item->reposite)->name}}</td>

                                            <td>{{$item->cost}}</td>

                                            <td>

                                                <a target="_blank" href="{{url('orders-in/'.$item->order_id)}}">

                                                    {{$item->order_id}}

                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                @endif

                                @if(!empty($supplierBalance["pay_in"]) && $supplierBalance["pay_in"] != null && $supplierBalance["pay_in"] != '')

                                    @foreach($supplierBalance["pay_in"] as $item)

                                        <tr>

                                            <td>{{$item->id}}</td>

                                            <td>{{$item->date}}</td>

                                            <td>{{optional($item->reposite)->name}}</td>

                                            <td>{{$item->cost}}</td>

                                            <td>

                                                <a target="_blank" href="{{url('orders-in/'.$item->order_id)}}">

                                                    {{$item->order_id}}

                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                @endif
                                </tbody>

                            </table>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="accounts-out-tab">

                        <div class="table-responsive">

                            <table width="100%" id="example_7" class="table table-bordered data-table">

                                <thead>

                                <tr>

                                    <td>#</td>

                                    <td>التاريخ</td>

                                    <td>الخزنة</td>

                                    <td>المدفوع</td>

                                    <td>رقم الفاتورة</td>

                                </tr>

                                </thead>

                                <tbody>

                                @if(!empty($supplierBalance["pay_out"]) && $supplierBalance["pay_out"] != null && $supplierBalance["pay_out"] != '')

                                    @foreach($supplierBalance["pay_out"] as $item)

                                        <tr>

                                            <td>{{$item->id}}</td>

                                            <td>{{$item->date}}</td>

                                            <td>{{optional($item->reposite)->name}}</td>

                                            <td>{{$item->cost}}</td>

                                            <td>

                                                <a target="_blank" href="{{url('orders-in/'.$item->order_id)}}">

                                                    {{$item->order_id}}

                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                @endif

                                @if(!empty($clientBalance["pay_out"]) && $clientBalance["pay_out"] != null && $clientBalance["pay_out"] != '')

                                    @foreach($clientBalance["pay_out"] as $item)

                                        <tr>

                                            <td>{{$item->id}}</td>

                                            <td>{{$item->date}}</td>

                                            <td>{{optional($item->reposite)->name}}</td>

                                            <td>{{$item->cost}}</td>

                                            <td>

                                                <a target="_blank" href="{{url('orders-in/'.$item->order_id)}}">

                                                    {{$item->order_id}}

                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                @endif
                                </tbody>

                            </table>

                        </div>

                    </div>

                    <?php $totalOfAccount = $client->init + $supplier->init; ?>

                    <div class="tab-pane fade" id="client-account-tab">

                        <div class="table-responsive">

                            <table width="100%" id="example_8___" class="table   table-bordered">

                                <thead>

                                <tr>

                                    <td>التاريخ</td>

                                    <td>فاتورة شراء</td>

                                    <td>فاتورة بيع</td>

                                    <td>فاتورة مرتجع شراء</td>

                                    <td>فاتورة مرتجع بيع</td>

                                    <td>مدفوع من</td>

                                    <td>مدفوع الى</td> 

                                    <td>الرصيد</td>

                                </tr>

                                </thead>

                                <tbody>

                                <!-- orders-in-tab content -->
 
                                @php
                                    $balance = 0;
                                    $balance2 = 0;
                                    $cbalance = 0;
                                    $sbalance = 0;
                                @endphp
                                @while(strtotime($from) <= strtotime($to))
                                
                                    @php
                                        $clientAccount = $clientBalance["client_accounts"];
                                        $supplierAccount = $supplierBalance["supplier_accounts"];
                                        
                                        $fromtime = strtotime($from);
                                        //
                                        $cbalance = isset($clientAccount[$fromtime])? $clientAccount[$fromtime]["balance"] : $cbalance;
                                        $sbalance = isset($supplierAccount[$fromtime])? $supplierAccount[$fromtime]["balance"] : $sbalance;
                                        
                                        $balance = isset($clientAccount[$fromtime])? $clientAccount[$fromtime]["balance"] : $cbalance;
                                        $balance -= isset($supplierAccount[$fromtime])? $supplierAccount[$fromtime]["balance"] : $sbalance;
                                        //
                                        $orderout = isset($clientAccount[$fromtime])? $clientAccount[$fromtime]["order_out"] : 0;
                                        $orderout -= isset($supplierAccount[$fromtime])? $supplierAccount[$fromtime]["order_out"] : 0;
                                        //
                                        $orderIn =  isset($clientAccount[$fromtime])? $clientAccount[$fromtime]["order_in"] : 0;
                                        $orderIn -=  isset($supplierAccount[$fromtime])? $supplierAccount[$fromtime]["order_in"] : 0;
                                        //
                                        $return1 = isset($supplierAccount[$fromtime])? $supplierAccount[$fromtime]["return"] : 0;
                                        $return2 = isset($clientAccount[$fromtime])? $clientAccount[$fromtime]["return"] : 0;
                                        //
                                        $payin =  isset($clientAccount[$fromtime])? $clientAccount[$fromtime]["pay_in"] : 0;
                                        $payin -=  isset($supplierAccount[$fromtime])? $supplierAccount[$fromtime]["pay_in"] : 0;
                                        //
                                        $payout =  isset($clientAccount[$fromtime])? $clientAccount[$fromtime]["pay_out"] : 0;
                                        $payout -=  isset($supplierAccount[$fromtime])? $supplierAccount[$fromtime]["pay_out"] : 0; 
                                        //
                                        $balance2 = ($payin + $orderIn + $return1) - ($orderout + $payout + $return2); 
                                                
                                    @endphp 
                                    @if ($balance2 != 0)
                                        <tr> 
                                            <td>{{ $from }}</td>  
                                            <td> 
                                                {{ $orderout }}
                                            </td> 
                                            <td> 
                                                {{ $orderIn }}
                                            </td> 
                                            <td>{{ $return1 }}</td> 
                                            <td>{{ $return2 }}</td> 
                                            <td> 
                                                {{ $payin }}
                                            </td>  
                                            <td> 
                                                {{ $payout }}
                                            </td>  
                                            <td>  
                                                {{ $balance }}
                                            </td>  
                                        </tr>
                                        @endif
                                @php
                                    
                                    $from = date('Y-m-d', strtotime($from. ' + 1 day'));
                                @endphp 
                                @endwhile  

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="client-info-tab">

                        <div class="row">

                            <div class="col-sm-12">

                                <div class="table-responsive">

                                    <table class="table table-bordered" id="example_8">

                                        @if( $client != null && $client != '')

                                            <tr>

                                                <td>العميل</td>

                                                <td id="supplierName">{{$client->name}}</td>

                                            </tr>

                                            <tr>

                                                <td>رصيد اول المدة</td>

                                                <td id="init">{{$client->init}}</td>

                                            </tr>

                                            <tr>

                                                <td>رصيد نهاية المدة</td>

                                                <td id="balance">{{ $cbalance }}</td>

                                            </tr>

                                        @endif

                                            @if( $supplier != null && $supplier != '')

                                                <tr>

                                                    <td>المورد</td>

                                                    <td id="supplierName">{{ $supplier->name }}</td>

                                                </tr>

                                                <tr>

                                                    <td>رصيد اول المدة</td>

                                                    <td id="init">{{$supplier->init}}</td>

                                                </tr>

                                                <tr>

                                                    <td>رصيد نهاية المدة</td>

                                                    <td id="balance">{{ $sbalance }}</td>

                                                </tr>

                                            @endif

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@stop

