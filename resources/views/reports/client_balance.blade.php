@extends('layout.app')
@section('title','التقارير')
@section('sub-title','كشف حساب عميل')
@section('content')

    <form action="{{ url('reports/client/get_index') }}" method="get" style="margin-bottom:20px;">
                    <div class="row">
                        <div class="col-md-2">
                            <button type="submit" name="" id="" class="btn btn-info">ابحث</button>
                        </div>
                        <div class="col-md-3">
                           <h5>العميل : <input type="text" readonly value="{{ $client->name ?? ''}}" ></h5>
                           <input style="display:none" value="{{ $client->id }}" name="client_id">
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                        من <input readonly type="text" class="form-control" name="from_date" id="to"
                                        autocomplete="off" value="{{ $from }}">
                                </div>    
                                <div class="col-md-6">
                                    الي <input readonly type="text" class="form-control" name="to_date" id="to"
                                        autocomplete="off" value="{{ $to }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                           <h4>رصيد المدة السابقة : <input type="text" readonly value="{{ $old_balance ?? ''}}" name="old_balance"></h4>
                        </div>
                    </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#orders-in-tab" data-toggle="tab" aria-expanded="true">فواتير شراء
                            العميل</a></li>
                    <li class=""><a href="#orders-out-tab" data-toggle="tab" aria-expanded="false">فواتير مرتجعات
                            العميل</a></li>
                    <li class=""><a href="#accounts-in-tab" data-toggle="tab" aria-expanded="false">مدفوع من العميل</a>
                    </li>
                    <li class=""><a href="#accounts-out-tab" data-toggle="tab" aria-expanded="false">مدفوع الي
                            العميل</a></li>
                    <li class=""><a href="#client-account-tab" data-toggle="tab" aria-expanded="false">كشف حساب عميل</a>
                    </li>
                    <li class=""><a href="#client-info-tab" data-toggle="tab" aria-expanded="false">بيانات العميل</a>
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
                    <div class="tab-pane fade" id="client-account-tab">
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
                                <?php $balance = 0; ?>
                                @foreach($client_accounts as $item)
                                    <tr>
                                        <td>{{$item['date']}}</td>
                                        <td>{{$item['order_in']}}</td>
                                        <td>{{$item['order_out']}}</td>
                                        <td>{{$item['return']}}</td>
                                        <td>{{$item['pay_in']}}</td>
                                        <td>{{$item['pay_out']}}</td>
                                        <td>{{ number_format($item['balance'], 2) }}</td>
                                        <?php $balance = $item['balance']; ?>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="client-info-tab">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="example_8">
                                        
                                        <?php 
                                        
                                            try{
                                                //$client->init = number_format((float)$client->init, 2);
                                                //$client->balance = number_format((float)$client->balance, 2);
                                                
                                            }catch(\Exception $e){}
                                        ?>
                                        @if( $client != null && $client != '')
                                            <tr>
                                                <td>المورد</td>
                                                <td id="supplierName">{{$client->name}}</td>
                                            </tr>
                                            <tr>
                                                <td>رصيد اول المدة</td>
                                                <td id="init"><?php echo $client->init ?></td>
                                            </tr>
                                            <tr>
                                                <td>رصيد نهاية المدة</td>
                                                <td id="balance"><?php echo $balance ?></td>
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
                            <h4 class="modal-title" id="modalLabel">كشف حساب عميل</h4>
                        </div>
                        <form action="{{url('reports/client/client_balance')}}" method="GET">
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
                                        <div class="form-group customerSelect">
                                            <label for="client_id">العميل</label>
                                            <select name="client_id" id="client_id"  >
                                                @foreach ($clients as $client)
                                                    <option value="{{$client->id}}">{{$client->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--
                                        <button type="button" class="btn btn-default btn-sm btn-flat ا"  onclick="getDateOfZeroBalance($('#client_id').val())" >
                                         اعادة حساب تاريخ اخر تصفير للرصيد
                                        </button>
                                        <span class="hint h6" ></span> 
                                        -->
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

@push('scripts')
<script>
    function getDateOfZeroBalance(client_id, select) {
        $(select).hide(300);
        $(".hint").html("من فظلك انتظر يتم حساب تاريخ اخر تصفير رصيد " + "<i class='fa fa-spinner fa-spin' ></i>");
        $.get('{{ url('/') }}'+"/dateBalanceZeroApi?client_id="+client_id, function(r){
            
            $(".hint").html("تاريخ اخر تصفير رصيد للعميل هو " + r);
            $(select).show(300);
            
        });
    }
    
    
    function getDateOfZeroBalance(client_id) { 
        $(".hint").html("من فظلك انتظر يتم حساب تاريخ اخر تصفير رصيد " + "<i class='fa fa-spinner fa-spin' ></i>");
        $.get('{{ url('/') }}'+"/dateBalanceZeroApi?renew=1&client_id="+client_id, function(r){
            
            $(".hint").html("تاريخ اخر تصفير رصيد للعميل هو " + r); 
            
        });
    }
</script>
@endpush
