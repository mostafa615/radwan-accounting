@extends('layout.app')
@section('title','التقارير')
@section('sub-title',' تقرير موظف  '.$user->name)
@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#orders-in-tab" data-toggle="tab" aria-expanded="true">فواتير شراء من
                            مورد</a></li>
                    <li class=""><a href="#orders-out-return-tab" data-toggle="tab" aria-expanded="false">فواتير مرتجعات
                            من مورد</a></li>
                    <li class=""><a href="#orders-out-tab" data-toggle="tab" aria-expanded="false">فواتير بيع الي
                            عميل</a></li>
                    <li class=""><a href="#orders-in-return-tab" data-toggle="tab" aria-expanded="false">فواتير مرتجع
                            عميل</a></li>
                    <li class=""><a href="#dialies-tab" data-toggle="tab" aria-expanded="false">تعاملات يومية</a></li>
                    <li class=""><a href="#load-tab" data-toggle="tab" aria-expanded="false">تحويل بين المخازن</a></li>
                    <li class=""><a href="#transaction-tab" data-toggle="tab" aria-expanded="false">تحويل الخزن</a></li>
                    <li class=""><a href="#accounts" data-toggle="tab" aria-expanded="false">استلام وصرف نقدية </a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane  active" id="orders-in-tab">
                        <div class="table-responsive">
                            <table width="100%" id="example_5" class="table">
                                <thead>
                                <tr>
                                    <td>#</td>
                                    <td>تاريخ الفاتروة</td>
                                    <td>تاريخ التسجيل</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($orders_out) && $orders_out!= null && $orders_out != '')
                                    @foreach($orders_out as $item)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="{{url('orders-out/'.$item->id)}}">
                                                    {{$item->id}}
                                                </a>
                                            </td>
                                            <td>{{$item->date}}</td>
                                            <td>{{$item->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="orders-out-return-tab">
                        <div class="table-responsive">
                            <table width="100%" id="example_5" class="table">
                                <thead>
                                <tr>
                                    <td>#</td>
                                    <td>تاريخ الفاتروة</td>
                                    <td>تاريخ التسجيل</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($orders_out_return) && $orders_out_return!= null && $orders_out_return != '')
                                    @foreach($orders_out_return as $item)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="{{url('orders-in/'.$item->id)}}">
                                                    {{$item->id}}
                                                </a>
                                            </td>
                                            <td>{{$item->date}}</td>
                                            <td>{{$item->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="orders-out-tab">
                        <div class="table-responsive">
                            <table width="100%" id="example_5" class="table">
                                <thead>
                                <tr>
                                    <td>#</td>
                                    <td>تاريخ الفاتروة</td>
                                    <td>تاريخ التسجيل</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($orders_in) && $orders_in!= null && $orders_in != '')
                                    @foreach($orders_in as $item)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="{{url('orders-in/'.$item->id)}}">
                                                    {{$item->id}}
                                                </a>
                                            </td>
                                            <td>{{$item->date}}</td>
                                            <td>{{$item->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="orders-in-return-tab">
                        <div class="table-responsive">
                            <table width="100%" id="example_5" class="table">
                                <thead>
                                <tr>
                                    <td>#</td>
                                    <td>تاريخ الفاتروة</td>
                                    <td>تاريخ التسجيل</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($orders_in_return) && $orders_in_return!= null && $orders_in_return != '')
                                    @foreach($orders_in_return as $item)
                                        <tr>
                                            <td>
                                                <a target="_blank" href="{{url('orders-in/'.$item->id)}}">
                                                    {{$item->id}}
                                                </a>
                                            </td>
                                            <td>{{$item->date}}</td>
                                            <td>{{$item->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="dialies-tab">
                        <div class="table-responsive">
                            <table width="100%" id="example_5" class="table">
                                <thead>
                                <tr>
                                    <td>#</td>
                                    <td>تاريخ اليومية</td>
                                    <td>تاريخ التسجيل</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($dialies) && $dialies!= null && $dialies != '')
                                    @foreach($dialies as $item)
                                        <tr>
                                            <td>
                                                {{$item->id}}
                                            </td>
                                            <td>{{$item->date}}</td>
                                            <td>{{$item->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="load-tab">
                        <div class="table-responsive">
                            <table width="100%" id="example_5" class="table">
                                <thead>
                                <tr>
                                    <td>#</td>
                                    <td>تاريخ التحويل</td>
                                    <td>تاريخ التسجيل</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($loads) && $loads!= null && $loads != '')
                                    @foreach($loads as $item)
                                        <tr>
                                            <td>
                                                {{$item->id}}
                                            </td>
                                            <td>{{$item->date}}</td>
                                            <td>{{$item->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="transaction-tab">
                        <div class="table-responsive">
                            <table width="100%" id="example_5" class="table">
                                <thead>
                                <tr>
                                    <td>#</td>
                                    <td>تاريخ التحويل</td>
                                    <td>تاريخ التسجيل</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($transactions) && $transactions!= null && $transactions != '')
                                    @foreach($transactions as $item)
                                        <tr>
                                            <td>
                                                {{$item->id}}
                                            </td>
                                            <td>{{$item->date}}</td>
                                            <td>{{$item->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="accounts">
                        <div class="table-responsive">
                            <table width="100%" id="example_6" class="table">
                                <thead>
                                <tr>
                                    <td>#</td>
                                    <td>المبلغ</td>
                                    <td>تاريخ الاستلام أو التسليم</td>
                                    <td>تاريخ التسجيل</td>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($accounts) && $accounts!= null && $accounts != '')
                                    @foreach($accounts as $item)
                                        <tr>
                                            <td>
                                                {{$item->id}}
                                            </td>
                                            <td>{{$item->cost}}</td>
                                            <td>{{$item->date}}</td>
                                            <td>{{$item->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
