@extends('layout.app')
@section('title','التقارير')
@section('sub-title',' حركات الصنف')
@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#order-in" data-toggle="tab" aria-expanded="false"> فواتير بيع الي
                            عميل</a></li>
                    <li><a href="#order-out" data-toggle="tab" aria-expanded="false">فواتير شراء من مورد</a></li>
                    <li><a href="#order-in-return" data-toggle="tab" aria-expanded="false">المرتجعات فواتير البيع الي
                            العملاء</a></li>
                    <li><a href="#order-out-return" data-toggle="tab" aria-expanded="false">المرتجعات فواتير الشراء من
                            مورد</a></li>
                            
                    <li><a href="#load" data-toggle="tab" aria-expanded="false">التحويل بين المخازن</a></li>
                    
                    @if(count($resources) == 1)
                    <li><a href="#itemReport" data-toggle="tab" aria-expanded="false">حساب مجمع</a></li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="tab-pane  active" id="order-in">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>الصنف</td>
                                    <td>الكمية</td>
                                    <td>التكلفة</td>
                                    <td>التاريخ</td>
                                    <td>رقم الفاتورة</td>
                                    <td>العميل أو المورد</td>
                                </tr>
                                <?php
                                $total_order_in_quantity = 0;
                                $total_order_in_unit_price = 0;
                                ?>
                                @foreach($orders_in as $item)
                                    <?php
                                    $total_order_in_quantity = $total_order_in_quantity + $item->quantity;
                                    $total_order_in_unit_price=$total_order_in_unit_price+$item->unite_price;
                                    ?>
                                    <tr>
                                        <td>{{optional($item->item)->name}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->unite_price}}</td>
                                        <td>{{optional($item->order)->date}}</td>
                                        <td>{{optional($item->order)->id}}</td>
                                        <td>{{optional(optional($item->order)->ownerable)->name}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>إجمالي الكمية</td>
                                    <td>{{$total_order_in_quantity}}</td>
                                    <td>إجمالي التكلفة</td>
                                    <td>{{$total_order_in_unit_price}}</td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade " id="order-out">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>الصنف</td>
                                    <td>الكمية</td>
                                    <td>التكلفة</td>
                                    <td>التاريخ</td>
                                    <td>رقم الفاتورة</td>
                                    <td>العميل أو المورد</td>
                                </tr>
                                <?php
                                $total_order_out_quantity = 0;
                                $total_order_out_unit_price= 0;
                                ?>
                                @foreach($orders_out as $item)
                                    <?php
                                    $total_order_out_quantity = $total_order_out_quantity + $item->quantity;
                                    $total_order_out_unit_price = $total_order_out_unit_price + $item->unite_price;
                                    ?>
                                    <tr>
                                        <td>{{optional($item->item)->name}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->unite_price}}</td>
                                        <td>{{optional($item->order)->date}}</td>
                                        <td>{{optional($item->order)->id}}</td>
                                        <td>{{optional(optional($item->order)->ownerable)->name}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>إجمالي الكمية</td>
                                    <td>{{$total_order_out_quantity}}</td>
                                    <td>إجمالي التكلفة</td>
                                    <td>{{$total_order_out_unit_price}}</td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade " id="order-in-return">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>الصنف</td>
                                    <td>الكمية</td>
                                    <td>التكلفة</td>
                                    <td>التاريخ</td>
                                    <td>رقم الفاتورة</td>
                                    <td>العميل أو المورد</td>
                                </tr>
                                <?php
                                $total_order_in_return_quantity = 0;
                                $total_order_in_return_unit_price= 0;
                                ?>
                                @foreach($orders_in_return as $item)
                                    <?php
                                    $total_order_in_return_quantity += $item->quantity;
                                    $total_order_in_return_unit_price += $item->unite_price;
                                    ?>
                                    <tr>
                                        <td>{{optional($item->item)->name}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->unite_price}}</td>
                                        <td>{{optional($item->order)->date}}</td>
                                        <td>{{optional($item->order)->id}}</td>
                                        <td>{{optional(optional($item->order)->ownerable)->name}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>إجمالي الكمية</td>
                                    <td>{{$total_order_in_return_quantity}}</td>
                                    <td>إجمالي التكلفة</td>
                                    <td>{{$total_order_in_return_unit_price}}</td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade " id="order-out-return">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>الصنف</td>
                                    <td>الكمية</td>
                                    <td>التكلفة</td>
                                    <td>التاريخ</td>
                                    <td>رقم الفاتورة</td>
                                    <td>العميل أو المورد</td>
                                </tr>
                                <?php
                                $total_order_out_return_quantity = 0;
                                $total_order_out_return_unit_price= 0;
                                ?>
                                @foreach($orders_out_return as $item)
                                    <?php
                                    $total_order_out_return_quantity += $item->quantity;
                                    $total_order_out_return_unit_price += $item->unite_price;
                                    ?>
                                    <tr>
                                        <td>{{optional($item->item)->name}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->unite_price}}</td>
                                        <td>{{optional($item->order)->date}}</td>
                                        <td>{{optional($item->order)->id}}</td>
                                        <td>{{optional(optional($item->order)->ownerable)->name}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>إجمالي الكمية</td>
                                    <td>{{$total_order_out_return_quantity}}</td>
                                    <td>إجمالي التكلفة</td>
                                    <td>{{$total_order_out_return_unit_price}}</td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="load">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>الصنف</td>
                                    <td>الكمية</td>
                                    <td>التاريخ</td>
                                    <td>رقم التحويل</td>
                                    <td>من مخزن</td>
                                    <td>الي مخزن</td>
                                </tr>
                                <?php
                                $total_load_quantity = 0;
                                ?>
                                @foreach($loads_from as $item)
                                    <?php
                                    $total_load_quantity -= $item->quantity;
                                    ?>
                                    <tr>
                                        <td>{{optional($item->item)->name}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td> {{date('Y-m-d', strtotime(optional($item->parent)->date))}} {{ date('H:i:s', strtotime(optional($item)->created_at)) }} </td>
                                        <td>{{optional($item->parent)->id}}</td>
                                        <td>{{optional(optional($item->parent)->from)->name}}</td>
                                        <td>{{optional(optional($item->parent)->to)->name}}</td>
                                    </tr>
                                @endforeach
                                @foreach($loads_to as $item)
                                    <?php
                                    $total_load_quantity += $item->quantity;
                                    ?>
                                    <tr>
                                        <td>{{optional($item->item)->name}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td> {{date('Y-m-d', strtotime(optional($item->parent)->date))}} {{ date('H:i:s', strtotime(optional($item)->created_at)) }} </td>
                                        <td>{{optional($item->parent)->id}}</td>
                                        <td>{{optional(optional($item->parent)->from)->name}}</td>
                                        <td>{{optional(optional($item->parent)->to)->name}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>الإجمالي</td>
                                    <td>{{$total_load_quantity}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if(count($resources) == 1)
                    <div class="tab-pane fade" id="itemReport">
                        <div class="table-responsive">
                            <div>
                                الرصيد الافتتاحي /  {{ $initQun }}
                            </div>
                            <table class="table table-bordered datatable-no-paging" style="width: 100%!important" id="item-report-table2" >
                                <thead>
                                    <tr class="amount-header" > 
                                        <td>التاريخ</td>
                                        <td>بيع</td>
                                        <td>شراء</td>
                                        <td>مرتجع بيع</td>
                                        <td>مرتجع شراء</td>
                                        <td>تحويل من </td>
                                        <td>تحويل الى </td>
                                        <td>أمر تشغيل</td>
                                        <td>ناتج أمر تشغيل</td>
                                        <td>الخردة</td>
                                        <td>الفضل</td>
                                        <td>الكميه </td>
                                    </tr>   
                                </thead>
                               <tbody>
                                <?php 
                                    $amountItem =  $initQun ;
                                    $dateCounter = 1;
                                ?>
                                @if(count($orders_in) > 0)
                                    @foreach($orders_in as $item)
                                        <?php
                                            $amountItem -= $item->quantity;
                                        ?>
                                        <tr> 
                                            <td>{{optional($item->order)->date}}</td>
                                            <td>{{$item->quantity}}</td> 
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td   class="amount-row">{{ $amountItem }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if(count($orders_out) > 0)
                                    @foreach($orders_out as $item)
                                        <?php
                                            $amountItem += $item->quantity;
                                        ?>
                                        <tr> 
                                            <td>{{optional($item->order)->date}}</td>
                                            <td>0</td>
                                            <td>{{$item->quantity}}</td> 
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td class="amount-row">{{ $amountItem }}</td>
                                        </tr>
                                    @endforeach 
                                @endif
                                @if(count($orders_in_return) > 0)
                                    @foreach($orders_in_return as $item)
                                        <?php
                                            $amountItem += $item->quantity;
                                        ?>
                                        <tr> 
                                            <td >{{optional($item->order)->date}}</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>{{$item->quantity}}</td> 
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td class="amount-row">{{ $amountItem }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if(count($orders_out_return) > 0)
                                    @foreach($orders_out_return as $item)
                                        <?php
                                            $amountItem -= $item->quantity;
                                        ?>
                                        <tr> 
                                            <td >{{optional($item->order)->date}}</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>{{$item->quantity}}</td> 
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td class="amount-row" >{{ $amountItem }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if(count($loads_from) > 0)
                                    @foreach($loads_from as $item)
                                        <?php
                                            $amountItem -= $item->quantity;
                                        ?>
                                        <tr> 
                                            <td   >{{optional($item->parent)->date}}</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>{{$item->quantity}}</td> 
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td class="amount-row">{{ $amountItem }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if(count($loads_to) > 0)
                                    @foreach($loads_to as $item)
                                        <?php
                                            $amountItem += $item->quantity;
                                        ?>
                                        <tr> 
                                            <td  >{{optional($item->parent)->date}}</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>{{$item->quantity}}</td> 
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                            <td class="amount-row">{{ $amountItem }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                               </tbody>
                            </table>
                            <div class="box">
                                <div class="box-body">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <td>الصنف</td>
                                            <td>الرصيد الافتتاحي </td>
                                            <td>الرصيد الحالي في هذه الفترة</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($resources as $resource)
                                            <tr>
                                                <td>{{optional($resource->item)->name}}</td>
                                                <td>{{$initQun}}</td>
                                                <td>{{$amountItem}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td>الصنف</td>
                            <td>الرصيد الافتتاحي </td>
                            <td>الرصيد الحالي</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($resources as $resource)
                            <tr>
                                <td>{{optional($resource->item)->name}}</td>
                                <td>{{$resource->init}}</td>
                                <td>{{$resource->quantity}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
 
@section('scripts')
<script>
     
    var initValue = {{ $resources[0]->init }};

function calculateBalance() {
    setTimeout(function(){
        var initV = parseFloat({{ $resources[0]->init }});
        $('.amount-row').each(function(){
            var amount = $(this).attr('data-amount');
            initV += parseFloat(amount);
            $(this).find('.balance-col').text(initV.toFixed(2));
        });
        console.log('done');
    }, 1000);
}
    
$('.amount-header td').click(function(){
    calculateBalance();
});

$('#item-report-table').DataTable({
        paging: true,
        serverSide: false,
        dom: 'Bfrtip',  
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],/**/
         
}); 

$(document).ready(function(){
    calculateBalance();
});

     
</script>
@endsection
