@extends('layout.app')
@section('title', 'التقارير')
@section('sub-title', 'تقرير حركات الصنف النهائي')
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
        <li><a href="#to_factory" data-toggle="tab" aria-expanded="false">ناتج الخامة المستخدمة</a></li>
        <li><a href="#from_factory" data-toggle="tab" aria-expanded="false">ناتج الخامة الناتجة</a></li>
        <li><a href="#scrap_factory" data-toggle="tab" aria-expanded="false">الخردة</a></li>
        <li><a href="#pieces_factory" data-toggle="tab" aria-expanded="false">الفضل</a></li>
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

        <div class="tab-pane fade" id="to_factory">
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <td>التاريخ</td>
                <td>أمر الشغل</td>
                <td>الكمية</td>
              </tr>
              <?php
                $total_to_factory = 0;
              ?>
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
                $total_to_factory += $resultQnt;
                $date = DB::table('operation_orders')->where('id', $item->operation_order_id)->first();
              ?>
              <tr>
                <td>{{$date ? $date->date : ''}}</td>
                <td>{{$date ? $date->id : ''}}</td>
                <td>{{$resultQnt}}</td>
              </tr>
              @endforeach
              <tr>
                <td>الاجمالى</td>
                <td></td>
                <td>{{$total_to_factory}}</td>
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

        <div class="tab-pane fade" id="scrap_factory">
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <td>التاريخ</td>
                <td>أمر الشغل</td>
                <td>الكمية</td>
              </tr>
              <?php
                $total_scrap_factory = 0;
              ?>
              @foreach($scrap_factory as $item)
              <?php
                $total_scrap_factory += $item->damage_weight;
                $date = DB::table('operation_orders')->where('id', $item->operation_order_id)->first();
              ?>
              <tr>
                <td>{{$date ? $date->date : ''}}</td>
                <td>{{$date ? $date->id : ''}}</td>
                <td>{{$item->damage_weight}}</td>
              </tr>
              @endforeach
              <tr>
                <td>الاجمالى</td>
                <td></td>
                <td>{{$total_scrap_factory}}</td>
              </tr>
            </table>
          </div>
        </div>

        <div class="tab-pane fade" id="pieces_factory">
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr>
                <td>التاريخ</td>
                <td>أمر الشغل</td>
                <td>الكمية</td>
              </tr>
              <?php
                $total_pieces_factory = 0;
              ?>
              @foreach($pieces_factory as $item)
              <?php
                $total_pieces_factory += $item->damage_weight;
                $date = DB::table('operation_orders')->where('id', $item->operation_order_id)->first();
              ?>
              <tr>
                <td>{{$date ? $date->date : ''}}</td>
                <td>{{$date ? $date->id : ''}}</td>
                <td>{{$item->damage_weight}}</td>
              </tr>
              @endforeach
              <tr>
                <td>الاجمالى</td>
                <td></td>
                <td>{{$total_pieces_factory}}</td>
              </tr>
            </table>
          </div>
        </div>

        @if(count($resources) == 1)
        <div class="tab-pane fade" id="itemReport">
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <div style="margin-bottom: 15px; font-size: 16px; font-weight: bold;">
              المدة المختارة: من {{ $periodFrom }} إلى {{ $periodTo }}
              &nbsp;|&nbsp;
              رصيد أول المدة (الرصيد الافتتاحي في {{ $periodFrom }}): {{ number_format($openingBalance, 2) }}
            </div>
            <table class="table table-bordered table-striped datatable-no-paging" style="width: 100%!important"
              id="item-report-table2">
              <thead>
                <tr class="amount-header">
                  <th>التاريخ</th>
                  <th>بيع</th>
                  <th>شراء</th>
                  <th>مرتجع بيع</th>
                  <th>مرتجع شراء</th>
                  <th>تحويل من </th>
                  <th>تحويل الى </th>
                  <th>ناتج الخامة المستخدمة</th>
                  <th>ناتج الخامة الناتجة</th>
                  <th>الخردة</th>
                  <th>الفضل</th>
                  <th>الكميه </th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $amountItem = floatval($openingBalance);
                  
                  // Collect transactions with their dates (only filtered by date range)
                  $allTransactions = [];
                  
                  // Use filtered transactions (orders_in, orders_out, etc.) which are already filtered by date
                  foreach($orders_in as $item) {
                    $date = Carbon\Carbon::parse(optional($item->order)->date)->format('Y-m-d');
                    if (!isset($allTransactions[$date])) {
                      $allTransactions[$date] = ['sell' => 0, 'buy' => 0, 'sell_return' => 0, 'buy_return' => 0, 'load_from' => 0, 'load_to' => 0, 'to_factory' => 0, 'from_factory' => 0, 'scrap' => 0, 'pieces' => 0];
                    }
                    $allTransactions[$date]['sell'] += floatval($item->quantity);
                  }
                  
                  foreach($orders_out as $item) {
                    $date = Carbon\Carbon::parse(optional($item->order)->date)->format('Y-m-d');
                    if (!isset($allTransactions[$date])) {
                      $allTransactions[$date] = ['sell' => 0, 'buy' => 0, 'sell_return' => 0, 'buy_return' => 0, 'load_from' => 0, 'load_to' => 0, 'to_factory' => 0, 'from_factory' => 0, 'scrap' => 0, 'pieces' => 0];
                    }
                    $allTransactions[$date]['buy'] += floatval($item->quantity);
                  }
                  
                  foreach($orders_in_return as $item) {
                    $date = Carbon\Carbon::parse(optional($item->order)->date)->format('Y-m-d');
                    if (!isset($allTransactions[$date])) {
                      $allTransactions[$date] = ['sell' => 0, 'buy' => 0, 'sell_return' => 0, 'buy_return' => 0, 'load_from' => 0, 'load_to' => 0, 'to_factory' => 0, 'from_factory' => 0, 'scrap' => 0, 'pieces' => 0];
                    }
                    $allTransactions[$date]['sell_return'] += floatval($item->quantity);
                  }
                  
                  foreach($orders_out_return as $item) {
                    $date = Carbon\Carbon::parse(optional($item->order)->date)->format('Y-m-d');
                    if (!isset($allTransactions[$date])) {
                      $allTransactions[$date] = ['sell' => 0, 'buy' => 0, 'sell_return' => 0, 'buy_return' => 0, 'load_from' => 0, 'load_to' => 0, 'to_factory' => 0, 'from_factory' => 0, 'scrap' => 0, 'pieces' => 0];
                    }
                    $allTransactions[$date]['buy_return'] += floatval($item->quantity);
                  }
                  
                  foreach($loads_from as $item) {
                    $date = Carbon\Carbon::parse(optional($item->parent)->date)->format('Y-m-d');
                    if (!isset($allTransactions[$date])) {
                      $allTransactions[$date] = ['sell' => 0, 'buy' => 0, 'sell_return' => 0, 'buy_return' => 0, 'load_from' => 0, 'load_to' => 0, 'to_factory' => 0, 'from_factory' => 0, 'scrap' => 0, 'pieces' => 0];
                    }
                    $allTransactions[$date]['load_from'] += floatval($item->quantity);
                  }
                  
                  foreach($loads_to as $item) {
                    $date = Carbon\Carbon::parse(optional($item->parent)->date)->format('Y-m-d');
                    if (!isset($allTransactions[$date])) {
                      $allTransactions[$date] = ['sell' => 0, 'buy' => 0, 'sell_return' => 0, 'buy_return' => 0, 'load_from' => 0, 'load_to' => 0, 'to_factory' => 0, 'from_factory' => 0, 'scrap' => 0, 'pieces' => 0];
                    }
                    $allTransactions[$date]['load_to'] += floatval($item->quantity);
                  }
                  
                  // Filter to_factory, from_factory, scrap_factory, pieces_factory by date range
                  $fromDate = Carbon\Carbon::parse(request('date_from'));
                  $toDate = Carbon\Carbon::parse(request('date_to'));
                  
                  foreach($to_factory as $item) {
                    $operationOrder = DB::table('operation_orders')->where('id', $item->operation_order_id)->first();
                    if (!$operationOrder) continue;
                    
                    $orderDate = Carbon\Carbon::parse($operationOrder->date);
                    if (!$orderDate->between($fromDate, $toDate)) continue;
                    
                    $result = DB::table('operation_order_results')
                                ->where('order_details_id', $item->id)                           
                                ->first();
                    if($result){
                      if($result->old_item_quantity){
                        $resultQnt = floatval($result->old_item_quantity);
                      } else {
                        $resultQnt = floatval($item->old_item_quantity);
                      }
                    } else {
                      $resultQnt = floatval($item->old_item_quantity);
                    }
                    $date = $orderDate->format('Y-m-d');
                    if (!isset($allTransactions[$date])) {
                      $allTransactions[$date] = ['sell' => 0, 'buy' => 0, 'sell_return' => 0, 'buy_return' => 0, 'load_from' => 0, 'load_to' => 0, 'to_factory' => 0, 'from_factory' => 0, 'scrap' => 0, 'pieces' => 0];
                    }
                    $allTransactions[$date]['to_factory'] += $resultQnt;
                  }
                  
                  foreach($from_factory as $item) {
                    $operationOrder = DB::table('operation_orders')->where('id', $item->operation_order_id)->first();
                    if (!$operationOrder) continue;
                    
                    $orderDate = Carbon\Carbon::parse($operationOrder->date);
                    if (!$orderDate->between($fromDate, $toDate)) continue;
                    
                    $date = $orderDate->format('Y-m-d');
                    if (!isset($allTransactions[$date])) {
                      $allTransactions[$date] = ['sell' => 0, 'buy' => 0, 'sell_return' => 0, 'buy_return' => 0, 'load_from' => 0, 'load_to' => 0, 'to_factory' => 0, 'from_factory' => 0, 'scrap' => 0, 'pieces' => 0];
                    }
                    $allTransactions[$date]['from_factory'] += floatval($item->actual_output);
                  }
                  
                  foreach($scrap_factory as $item) {
                    $operationOrder = DB::table('operation_orders')->where('id', $item->operation_order_id)->first();
                    if (!$operationOrder) continue;
                    
                    $orderDate = Carbon\Carbon::parse($operationOrder->date);
                    if (!$orderDate->between($fromDate, $toDate)) continue;
                    
                    $date = $orderDate->format('Y-m-d');
                    if (!isset($allTransactions[$date])) {
                      $allTransactions[$date] = ['sell' => 0, 'buy' => 0, 'sell_return' => 0, 'buy_return' => 0, 'load_from' => 0, 'load_to' => 0, 'to_factory' => 0, 'from_factory' => 0, 'scrap' => 0, 'pieces' => 0];
                    }
                    $allTransactions[$date]['scrap'] += floatval($item->damage_weight);
                  }
                  
                  foreach($pieces_factory as $item) {
                    $operationOrder = DB::table('operation_orders')->where('id', $item->operation_order_id)->first();
                    if (!$operationOrder) continue;
                    
                    $orderDate = Carbon\Carbon::parse($operationOrder->date);
                    if (!$orderDate->between($fromDate, $toDate)) continue;
                    
                    $date = $orderDate->format('Y-m-d');
                    if (!isset($allTransactions[$date])) {
                      $allTransactions[$date] = ['sell' => 0, 'buy' => 0, 'sell_return' => 0, 'buy_return' => 0, 'load_from' => 0, 'load_to' => 0, 'to_factory' => 0, 'from_factory' => 0, 'scrap' => 0, 'pieces' => 0];
                    }
                    $allTransactions[$date]['pieces'] += floatval($item->damage_weight);
                  }
                  
                  
                  // Sort by date
                  ksort($allTransactions);
                  
                  $totalSell = 0;
                  $totalBuy = 0;
                  $totalSellReturn = 0;
                  $totalBuyReturn = 0;
                  $totalLoadFrom = 0;
                  $totalLoadTo = 0;
                  $totalToFactory = 0;
                  $totalFromFactory = 0;
                  $totalScrap = 0;
                  $totalPieces = 0;
                  
                  // Display one row per day (all transactions are already filtered by date)
                  foreach($allTransactions as $date => $trans) {
                      // Calculate balance
                      $amountItem = floatval($amountItem);
                      $amountItem = $amountItem - $trans['sell'] + $trans['buy'] + $trans['sell_return'] - $trans['buy_return'] - $trans['load_from'] + $trans['load_to'] - $trans['to_factory'] + $trans['from_factory'] - $trans['scrap'] - $trans['pieces'];
                      $amountItem = sprintf("%.2f", $amountItem);
                      
                      // Add to totals
                      $totalSell += $trans['sell'];
                      $totalBuy += $trans['buy'];
                      $totalSellReturn += $trans['sell_return'];
                      $totalBuyReturn += $trans['buy_return'];
                      $totalLoadFrom += $trans['load_from'];
                      $totalLoadTo += $trans['load_to'];
                      $totalToFactory += $trans['to_factory'];
                      $totalFromFactory += $trans['from_factory'];
                      $totalScrap += $trans['scrap'];
                      $totalPieces += $trans['pieces'];
                ?>
                <tr data-amount="{{-$trans['sell'] + $trans['buy'] + $trans['sell_return'] - $trans['buy_return'] - $trans['load_from'] + $trans['load_to'] - $trans['to_factory'] + $trans['from_factory'] - $trans['scrap'] - $trans['pieces']}}" data-date="{{$date}}" class="amount-row">
                  <td><strong>{{$date}}</strong></td>
                  <td>{{ number_format($trans['sell'], 2) }}</td>
                  <td>{{ number_format($trans['buy'], 2) }}</td>
                  <td>{{ number_format($trans['sell_return'], 2) }}</td>
                  <td>{{ number_format($trans['buy_return'], 2) }}</td>
                  <td>{{ number_format($trans['load_from'], 2) }}</td>
                  <td>{{ number_format($trans['load_to'], 2) }}</td>
                  <td>{{ number_format($trans['to_factory'], 2) }}</td>
                  <td>{{ number_format($trans['from_factory'], 2) }}</td>
                  <td>{{ number_format($trans['scrap'], 2) }}</td>
                  <td>{{ number_format($trans['pieces'], 2) }}</td>
                  <td class="balance-col"><strong>{{ $amountItem }}</strong></td>
                </tr>
                <?php
                  }
                  
                ?>
                <tr style="background-color: #f9f9f9; font-weight: bold;">
                  <td><strong>الإجمالي</strong></td>
                  <td>{{ number_format($totalSell, 2) }}</td>
                  <td>{{ number_format($totalBuy, 2) }}</td>
                  <td>{{ number_format($totalSellReturn, 2) }}</td>
                  <td>{{ number_format($totalBuyReturn, 2) }}</td>
                  <td>{{ number_format($totalLoadFrom, 2) }}</td>
                  <td>{{ number_format($totalLoadTo, 2) }}</td>
                  <td>{{ number_format($totalToFactory, 2) }}</td>
                  <td>{{ number_format($totalFromFactory, 2) }}</td>
                  <td>{{ number_format($totalScrap, 2) }}</td>
                  <td>{{ number_format($totalPieces, 2) }}</td>
                  <td style=""><strong>{{ number_format((floatval($openingBalance) - $totalSell + $totalBuy + $totalSellReturn - $totalBuyReturn - $totalLoadFrom + $totalLoadTo - $totalToFactory + $totalFromFactory - $totalScrap - $totalPieces), 2) }}</strong></td>
                </tr>
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
        <div style="margin-bottom: 10px; font-size: 15px; font-weight: bold;">
          المدة المختارة: من {{ $periodFrom }} إلى {{ $periodTo }}
        </div>
        <table class="table table-bordered">
          <thead>
            <tr>
              <td>الصنف</td>
              <td>رصيد أول المدة ({{ $periodFrom }})</td>
              <td>حركة المدة</td>
              <td>رصيد آخر المدة ({{ $periodTo }})</td>
              <td>الرصيد الحالي الآن</td>
            </tr>
          </thead>
          <tbody>
            @foreach($resources as $resource)
            <tr>
              <td>{{optional($resource->item)->name}}</td>
              <td>{{ number_format($openingBalance, 2) }}</td>
              <td>{{ number_format($rangeMovements, 2) }}</td>
              <td id="final-quantity-cell">{{ number_format($balanceAtDateTo, 2) }}</td>
              <td>{{ number_format($liveStock, 2) }}</td>
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
<style>
  #item-report-table2 thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: #f4f4f4 !important;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
  }
  
  .table-responsive {
    max-height: 600px;
    overflow-y: auto;
  }
  
  .table-responsive::-webkit-scrollbar {
    width: 8px;
  }
  
  .table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
  }
  
  .table-responsive::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
  }
  
  .table-responsive::-webkit-scrollbar-thumb:hover {
    background: #555;
  }
</style>
<script>
  var initValue = {{ count($resources) ? $resources[0]->init : 0 }};
  var dates = {!! json_encode($dates)!!};

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

