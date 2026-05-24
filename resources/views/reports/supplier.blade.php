@extends('layout.app')
@section('title','التقارير')
@section('sub-title','كشف حساب مورد')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#orders-in-tab" data-toggle="tab" aria-expanded="false">فواتير البيع الي مورد</a></li>
                    <li class=""><a href="#returns-tab" data-toggle="tab" aria-expanded="true">فواتير مرتجعات
                            المورد</a></li>
                    <li class=""><a href="#orders-out-tab" data-toggle="tab" aria-expanded="true">فواتير الشراء
                            من المورد</a></li>
                    <li class=""><a href="#accounts-in-tab" data-toggle="tab" aria-expanded="false">مدفوع من المورد</a>
                    </li>
                    <li class=""><a href="#accounts-out-tab" data-toggle="tab" aria-expanded="false">مدفوع الي
                            المورد</a></li>
                    <li class=""><a href="#supplier-account-tab" data-toggle="tab" aria-expanded="false">كشف حساب
                            مورد</a></li>
                    <li class=""><a href="#supplier-info-tab" data-toggle="tab" aria-expanded="false">بيانات المورد</a>
                    </li>
                    <li class="pull-left">
                        <button data-toggle="modal" data-target="#modal" class="btn btn-success btn-flat btn-sm">
                            <i class="fa fa-cog fa-spin"></i>
                        </button>
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
                                @if(!empty($orders_in) && $orders_in!= null && $orders_in != '')
                                    @foreach($orders_in as $item)
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
                    <div class="tab-pane " id="returns-tab">
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
                                @if(!empty($returns) && $returns!= null && $returns != '')
                                    @foreach($returns as $item)
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
                    <div class="tab-pane " id="orders-out-tab">
                        <div class="table-responsive">
                            <table width="100%" id="example_3" class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <td>#</td>
                                    <td>التاريخ</td>
                                    <td>الاجمالي</td>
                                    <td>المدفوع</td>
                                    <td>المتبقي</td>
                                </tr>
                                </thead>
                                <?php
                                $account = [];
                                ?>
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
                                @if(!empty($pay_in) && $pay_in != null && $pay_in != '')
                                    @foreach($pay_in as $item)
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
                                @if(!empty($pay_out) && $pay_out != null && $pay_out != '')
                                    @foreach($pay_out as $item)
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
                    <div class="tab-pane fade" id="supplier-account-tab">
                        <div class="table-responsive">
                            <table width="100%" id="example_8" class="table   table-bordered">
                                <thead>
                                <tr>
                                    <td>التاريخ</td>
                                    <td>فاتورةبيع</td>
                                    <td>فاتورة شراء</td>
                                    <td>فاتورة مرتجع</td>
                                    <td>المدفوع</td>
                                    <td>منصرف</td>
                                    <td>الرصيد</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $balance = 0;  ?>
                                @foreach($supplier_accounts as $item)
                                    <tr>
                                        <td>{{$item['date']}}</td>
                                        <td>{{$item['order_in']}}</td>
                                        <td>{{$item['order_out']}}</td>
                                        <td>{{$item['return']}}</td>
                                        <td>{{$item['pay_in']}}</td>
                                        <td>{{$item['pay_out']}}</td>
                                        <td>{{$item['balance']}}</td>
                                        <?php $balance = $item['balance'];  ?>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="supplier-info-tab">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="example_8">
                                        @if( $supplier != null && $supplier != '')
                                            <tr>
                                                <td>المورد</td>
                                                <td id="supplierName">{{$supplier->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>رصيد اول المدة</td>
                                                <td id="init">{{ number_format($supplier->init, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td>رصيد نهاية المدة</td>
                                                <td id="balance">{{ $balance }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modalLabel">كشف حساب مورد</h4>
                        </div>
                        <form action="{{url('reports/supplier')}}" method="GET">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="from">من</label>
                                            <input required type="text" class="form-control date" name="from" id="from"
                                                   autocomplete="off" value="{{Carbon\Carbon::now()->subDay(180)->format('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="to">الي</label>
                                            <input required type="text" class="form-control date" name="to" id="to"
                                                   autocomplete="off" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="supplier_id">المورد</label>
                                            <select name="supplier_id" id="supplier_id">
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-sm btn-flat" data-dismiss="modal">
                                    الغاء
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm btn-flat">موافق</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
