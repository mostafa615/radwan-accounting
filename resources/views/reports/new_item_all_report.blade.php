@extends('layout.app')
@section('title', 'التقارير')
@section('sub-title', 'حركات الصنف الجديده')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#order-in" data-toggle="tab" aria-expanded="false"> فواتير بيع الي عميل</a></li>
        <li><a href="#order-out" data-toggle="tab" aria-expanded="false">فواتير شراء من مورد</a></li>
        <li><a href="#order-in-return" data-toggle="tab" aria-expanded="false">المرتجعات فواتير البيع الي العملاء</a></li>
        <li><a href="#order-out-return" data-toggle="tab" aria-expanded="false">المرتجعات فواتير الشراء من مورد</a></li>
        <li><a href="#from_load" data-toggle="tab" aria-expanded="false">التحويل بين المخازن (من)</a></li>
        <li><a href="#to_load" data-toggle="tab" aria-expanded="false">التحويل بين المخازن (الى)</a></li>
        <li><a href="#from_factory" data-toggle="tab" aria-expanded="false">ناتج الخامة الناتجة</a></li>
        @if(count($resources) == 1)
        <li><a href="#itemReport" data-toggle="tab" aria-expanded="false">حساب مجمع</a></li>
        @endif
      </ul>

      <div class="tab-content">
        <div class="tab-pane active" id="order-in">
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
                <td><a href="{{url('orders-in/'.$item->order->id)}}" target="_blank">{{$item->order->id}}</a></td>
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

        <div class="tab-pane fade" id="order-out">
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
                <td><a href="{{url('orders-out/'.$item->order->id)}}" target="_blank">{{$item->order->id}}</a></td>
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

        <div class="tab-pane fade" id="order-in-return">
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
                <td><a href="{{url('return-orders-in/'.$item->order->id)}}" target="_blank">{{$item->order->id}}</a></td>
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

        <div class="tab-pane fade" id="order-out-return">
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
                <td><a href="{{url('return-orders-out/'.$item->order->id)}}" target="_blank">{{$item->order->id}}</a></td>
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

        <div class="tab-pane fade" id="from_load">
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
                $total_from_load_quantity = 0;
              ?>
              @foreach($loads_from as $item)
              <?php
                $total_from_load_quantity -= $item->quantity;
              ?>
              <tr>
                <td>{{optional($item->item)->name}}</td>
                <td>{{$item->quantity}}</td>
                <td>{{date('Y-m-d', strtotime(optional($item->parent)->date))}} {{date('H:i:s', strtotime(optional($item)->created_at))}}</td>
                <td><a href="{{route('load.show', $item->parent->id)}}" target="_blank">{{$item->parent->id}}</a></td>
                <td>{{optional(optional($item->parent)->from)->name}}</td>
                <td>{{optional(optional($item->parent)->to)->name}}</td>
              </tr>
              @endforeach
              <tr>
                <td>الإجمالي</td>
                <td>{{$total_from_load_quantity}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </table>
          </div>
        </div>

        <div class="tab-pane fade" id="to_load">
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
                $total_to_load_quantity = 0;
              ?>
              @foreach($loads_to as $item)
              <?php
                $total_to_load_quantity += $item->quantity;
              ?>
              <tr>
                <td>{{optional($item->item)->name}}</td>
                <td>{{$item->quantity}}</td>
                <td>{{date('Y-m-d', strtotime(optional($item->parent)->date))}} {{date('H:i:s', strtotime(optional($item)->created_at))}}</td>
                <td><a href="{{route('load.show', $item->parent->id)}}" target="_blank">{{$item->parent->id}}</a></td>
                <td>{{optional(optional($item->parent)->from)->name}}</td>
                <td>{{optional(optional($item->parent)->to)->name}}</td>
              </tr>
              @endforeach
              <tr>
                <td>الإجمالي</td>
                <td>{{$total_to_load_quantity}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </table>
          </div>
        </div>

        <div class="tab-pane fade" id="from_factory">
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <td>التاريخ</td>
                <td>أمر الشغل</td>
                <td>الكمية</td>
              </tr>
              <?php
                $total_from_factory = 0;
              ?>
              @foreach($from_factory as $item)
              <?php
                $total_from_factory += $item->actual_output;
              ?>
              <tr>
                <td>{{DB::table('operation_orders')->where('id', $item->operation_order_id)->first()->date}}</td>
                <td>{{DB::table('operation_orders')->where('id', $item->operation_order_id)->first()->id}}</td>
                <td>{{$item->actual_output}}</td>
              </tr>
              @endforeach
              <tr>
                <td>الاجمالى</td>
                <td></td>
                <td>{{$total_from_factory}}</td>
              </tr>
            </table>
          </div>
        </div>

        @if(count($resources) == 1)
        <div class="tab-pane fade" id="itemReport">
          <div class="table-responsive">
            <div>
              الرصيد الافتتاحي / {{ $resources[0]->init }}
            </div>
            <table class="table table-bordered datatable-no-paging" style="width: 100%!important"
              id="item-report-table2">
              <thead>
                <tr class="amount-header">
                  <td>التاريخ</td>
                  <td>بيع</td>
                  <td>شراء</td>
                  <td>مرتجع بيع</td>
                  <td>مرتجع شراء</td>
                  <td>تحويل من </td>
                  <td>تحويل الى </td>
                  <td>ناتج الخامة المستخدمة</td>
                  <td>ناتج الخامة الناتجة</td>
                  <td>الخردة</td>
                  <td>الفضل</td>
                  <td>الكميه </td>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $amountItem = $resources[0]->init;
                  $dateCounter = 1;
                ?>
                @foreach($coll_orders_in as $item)
                <?php
                  $amountItem -= $item->quantity;
                ?>
                <?php
                  $fromDate = Carbon\Carbon::parse(request('date_from'));
                  $toDate = Carbon\Carbon::parse(request('date_to'));
                  $rowDate = Carbon\Carbon::parse(optional($item->order)->date);
                  $hideRow = !($rowDate->between($fromDate, $toDate));
                ?>
                <tr data-amount="-{{$item->quantity}}" data-date="{{Carbon\Carbon::parse($item->order->date)->format('Y-m-d')}}" class="amount-row" style="{{ $hideRow ? 'display:none' : '' }}">
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
                  <td class="balance-col">{{ $amountItem }}</td>
                </tr>
                @endforeach

                @foreach($coll_orders_out as $item)
                <?php
                  $amountItem += $item->quantity;
                ?>
                <?php
                  $fromDate = Carbon\Carbon::parse(request('date_from'));
                  $toDate = Carbon\Carbon::parse(request('date_to'));
                  $rowDate = Carbon\Carbon::parse(optional($item->order)->date);
                  $hideRow = !($rowDate->between($fromDate, $toDate));
                ?>
                <tr data-amount="{{$item->quantity}}" data-date="{{Carbon\Carbon::parse($item->order->date)->format('Y-m-d')}}" class="amount-row" style="{{ $hideRow ? 'display:none' : '' }}">
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
                  <td class="balance-col">{{ $amountItem }}</td>
                </tr>
                @endforeach

                @foreach($coll_orders_in_return as $item)
                <?php
                  $amountItem += $item->quantity;
                ?>
                <?php
                  $fromDate = Carbon\Carbon::parse(request('date_from'));
                  $toDate = Carbon\Carbon::parse(request('date_to'));
                  $rowDate = Carbon\Carbon::parse(optional($item->order)->date);
                  $hideRow = !($rowDate->between($fromDate, $toDate));
                ?>
                <tr data-amount="{{$item->quantity}}"
                  data-date="{{Carbon\Carbon::parse($item->order->date)->format('Y-m-d')}}" class="amount-row" style="{{ $hideRow ? 'display:none' : '' }}">
                  <td>{{optional($item->order)->date}}</td>
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
                  <td class="balance-col">{{ $amountItem }}</td>
                </tr>
                @endforeach

                @foreach($coll_orders_out_return as $item)
                <?php
                  $amountItem -= $item->quantity;
                ?>
                <?php
                  $fromDate = Carbon\Carbon::parse(request('date_from'));
                  $toDate = Carbon\Carbon::parse(request('date_to'));
                  $rowDate = Carbon\Carbon::parse(optional($item->order)->date);
                  $hideRow = !($rowDate->between($fromDate, $toDate));
                ?>
                <tr data-amount="-{{$item->quantity}}" data-date="{{Carbon\Carbon::parse($item->order->date)->format('Y-m-d')}}" class="amount-row" style="{{ $hideRow ? 'display:none' : '' }}">
                  <td>{{optional($item->order)->date}}</td>
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
                  <td class="balance-col">{{ $amountItem }}</td>
                </tr>
                @endforeach

                @foreach($coll_loads_from as $item)
                <?php
                  $amountItem -= $item->quantity;
                ?>
                <?php
                  $fromDate = Carbon\Carbon::parse(request('date_from'));
                  $toDate = Carbon\Carbon::parse(request('date_to'));
                  $rowDate = Carbon\Carbon::parse(optional($item->parent)->date);
                  $hideRow = !($rowDate->between($fromDate, $toDate));
                ?>
                <tr data-amount="-{{$item->quantity}}" data-date="{{Carbon\Carbon::parse($item->parent->date)->format('Y-m-d')}}" class="amount-row" style="{{ $hideRow ? 'display:none' : '' }}">
                  <td>{{optional($item->parent)->date}}</td>
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
                  <td class="balance-col">{{ $amountItem }}</td>
                </tr>
                @endforeach

                @foreach($coll_loads_to as $item)
                <?php
                  $amountItem += $item->quantity;
                ?>
                <?php
                  $fromDate = Carbon\Carbon::parse(request('date_from'));
                  $toDate = Carbon\Carbon::parse(request('date_to'));
                  $rowDate = Carbon\Carbon::parse(optional($item->parent)->date);
                  $hideRow = !($rowDate->between($fromDate, $toDate));
                ?>
                <tr data-amount="{{$item->quantity}}" data-date="{{Carbon\Carbon::parse($item->parent->date)->format('Y-m-d')}}" class="amount-row" style="{{ $hideRow ? 'display:none' : '' }}">
                  <td>{{optional($item->parent)->date}}</td>
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
                  <td class="balance-col">{{ $amountItem }}</td>
                </tr>
                @endforeach

                @foreach($to_factory as $item)
                <?php
                  $result = DB::table('operation_order_results')
                              ->where('order_details_id', $item->id)                           
                              ->first();

                  if($result){
                    if($result->old_item_quantity){
                      $resultQnt = $result->old_item_quantity;
                    } else {
                      $resultQnt = $item->old_item_quantity;
                    }
                  } else {
                    $resultQnt = $item->old_item_quantity;
                  }
                  $amountItem -= $item->old_item_quantity;
                  $date = DB::table('operation_orders')->where('id', $item->operation_order_id)->first()->date;
                ?>
                <?php
                  $fromDate = Carbon\Carbon::parse(request('date_from'));
                  $toDate = Carbon\Carbon::parse(request('date_to'));
                  $rowDate = Carbon\Carbon::parse($date);
                  $hideRow = !($rowDate->between($fromDate, $toDate));
                ?>
                <tr data-amount="-{{$resultQnt}}" data-date="{{Carbon\Carbon::parse($date)->format('Y-m-d')}}" class="amount-row" style="{{ $hideRow ? 'display:none' : '' }}">
                  <td>{{$date}}</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>{{$resultQnt}}</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td class="balance-col">{{ $amountItem }}</td>
                </tr>
                @endforeach

                @foreach($from_factory as $item)
                <?php
                  $amountItem += $item->actual_output;
                  $date = DB::table('operation_orders')->where('id', $item->operation_order_id)->first()->date;
                ?>
                <?php
                  $fromDate = Carbon\Carbon::parse(request('date_from'));
                  $toDate = Carbon\Carbon::parse(request('date_to'));
                  $rowDate = Carbon\Carbon::parse($date);
                  $hideRow = !($rowDate->between($fromDate, $toDate));
                ?>
                <tr data-amount="{{$item->actual_output}}" data-date="{{Carbon\Carbon::parse($date)->format('Y-m-d')}}" class="amount-row" style="{{ $hideRow ? 'display:none' : '' }}">
                  <td>{{$date}}</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>{{$item->actual_output}}</td>
                  <td>0</td>
                  <td>0</td>
                  <td class="balance-col">{{ $amountItem }}</td>
                </tr>
                @endforeach

                @foreach($scrap_factory as $item)
                <?php
                  $amountItem = floatval($amountItem);
                  $amountItem = sprintf("%.2f",($amountItem - $item->damage_weight ) );

                  $date = DB::table('operation_orders')->where('id', $item->operation_order_id)->first()->date;
                ?>
                <?php
                  $fromDate = Carbon\Carbon::parse(request('date_from'));
                  $toDate = Carbon\Carbon::parse(request('date_to'));
                  $rowDate = Carbon\Carbon::parse($date);
                  $hideRow = !($rowDate->between($fromDate, $toDate));
                ?>
                <tr data-amount="-{{$item->damage_weight}}" data-date="{{Carbon\Carbon::parse($date)->format('Y-m-d')}}" class="amount-row" style="{{ $hideRow ? 'display:none' : '' }}">
                  <td>{{$date}}</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>{{$item->damage_weight}}</td>
                  <td>0</td>
                  <td class="balance-col">{{ $amountItem }}</td>
                </tr>
                @endforeach

                @foreach($pieces_factory as $item)
                <?php
                  $amountItem = floatval($amountItem);
                  $amountItem = sprintf("%.2f",($amountItem - $item->damage_weight ) );

                  $date = DB::table('operation_orders')->where('id', $item->operation_order_id)->first()->date;
                ?>
                <?php
                  $fromDate = Carbon\Carbon::parse(request('date_from'));
                  $toDate = Carbon\Carbon::parse(request('date_to'));
                  $rowDate = Carbon\Carbon::parse($date);
                  $hideRow = !($rowDate->between($fromDate, $toDate));
                ?>
                <tr data-amount="-{{$item->damage_weight}}" data-date="{{Carbon\Carbon::parse($date)->format('Y-m-d')}}" class="amount-row" style="{{ $hideRow ? 'display:none' : '' }}">
                  <td>{{$date}}</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>{{$item->damage_weight}}</td>
                  <td class="balance-col">{{ $amountItem }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
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
              <td>الرصيد الافتتاحي</td>
              <td>الرصيد الحالي</td>
            </tr>
          </thead>
          <tbody>
            @foreach($resources as $resource)
            <tr>
              <td>{{optional($resource->item)->name}}</td>
              <td>{{$resource->init}}</td>
              <td id="final-quantity-cell">{{$resource->quantity}}</td>
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
  var initValue = {{ count($resources) ? $resources[0]->init : 0 }};
  var dates = {!! json_encode($dates)!!};


// function calculateBalance() {
//   setTimeout(function () {
//     var initV = parseFloat({{ count($resources) ? $resources[0]->init : 0 }});
//     var lastBalance = initV;

//     $('.amount-row').each(function () {
//       var amount = $(this).attr('data-amount');
//       var date = $(this).attr('data-date');
//       initV += parseFloat(amount);
//       $(this).find('.balance-col').text(initV.toFixed(2));

//       lastBalance = initV;

//       if (!dates.includes(date)) {
//         $(this).css('display', 'none');
//       }
//     });

//     // Set the final quantity value
//     $('#final-quantity-cell').text(lastBalance.toFixed(2));

//     console.log('done');
//   }, 1000);
// }


  $('.amount-header td').click(function () {
    calculateBalance();
  });

  $('#item-report-table').DataTable({
    paging: true,
    serverSide: false,
    dom: 'Bfrtip',
    buttons: [
      'copy', 'csv', 'excel', 'pdf', 'print'
    ],
  });

  $(document).ready(function () {
    calculateBalance();
  });
</script>
@endsection