@extends('layout.app')
@section('title','فواتير الشراء')
@section('sub-title','الرئسية')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">فواتير الشراء</h3>
        <div class="box-btn">
          @if(Auth::user()->can('add_order'))
          <a class="btn btn-success  btn-sm btn-flat" href="{{route('orders-out.create')}}">
            اضافة
          </a>
          @endif
        </div>
      </div>
      <div class="box-body">
        <form action="">
          <div class="row">
            <div class="col-xs-3">
              <div class="form-group">
                <label>بحث</label>
                <input type="text" name="search" class="form-control" placeholder="بحث برقم الفاتورة">
              </div>
            </div>
            <button class="btn btn-primary btn-sm btn-flat" style="margin-top: 27px;">بحث</button>
          </div>

          <div class="row">
            <div class="col-xs-6">
              <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 20px;">
                <div class="form-group">
                  <?php
                    $pendingOrders = App\Models\Order::where('is_return', false)->where('type', 'out')
                      ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                      ->where('order_details.status', 'pending')
                      ->distinct()
                      ->select('orders.*');
        
                    if(!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('admin2'))
                      $pendingOrders->where('branch_id', auth()->user()->branch_id);
        
                    $pendingOrders = $pendingOrders->get();
                  ?>
                  <a class="btn btn-warning btn-sm btn-flat" href="?status=2">
                    الفواتير المنتظرة <span class="badge badge-light">{{$pendingOrders->count()}}</span>
                  </a>
                </div>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 20px;">
                <div class="form-group">
                  <?php
                    $refusedOrders = App\Models\Order::where('is_return', false)->where('type', 'out')
                      ->where('date', '>=', '2024-07-18')
                      ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                      ->where('order_details.status', 'refused')
                      ->distinct()
                      ->select('orders.*');
        
                    if(!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('admin2'))
                      $refusedOrders->where('branch_id', auth()->user()->branch_id);
        
                    $refusedOrders = $refusedOrders->get();
                  ?>
                  <a class="btn btn-danger btn-sm btn-flat" href="?status=3">
                    الفواتير المرفوضة <span class="badge badge-light">{{$refusedOrders->count()}}</span>
                  </a>
                </div>
              </div>
              <div class="col-lg-3 col-md-3 col-sm-12" style="margin-top: 20px;">
                <div class="form-group">
                  <a class="btn btn-primary btn-sm btn-flat" href="?">
                    جميع الفواتير <i class="fa fa-globe" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </form>
        <div class="table-responsive">
          <table class="table table-bordered" id="example_10">
            <thead>
              <tr>
                <th>#</th>
                <th>التاريخ</th>
                <th>رقم الفاتورة</th>
                <th>المورد</th>
                <th>هاتف المورد</th>
                <th>الفرع</th>
                <th>عرض</th>
                <th>الاجمالي</th>
                <th>الخصم</th>
                <th>المدفوع</th>
                <th>المتبقي</th>
                <th>الحالة</th>
                <th>العمليات</th>
              </tr>
            </thead>
            <?php
              $counter = 1;
            ?>
            <tbody>
              @if (count($resources) <= 0) <tr>
                <td colspan="11" class="text-center">
                  <b>لا توجد سجلات</b>
                  <br>
                  <a class="btn btn-success  btn-sm btn-flat" href="?">
                    عرض الكل
                  </a>
                </td>
                </tr>
                @endif
                @foreach($resources as $resource)
                <tr>
                  <td>{{$counter}}</td>
                  <td>{{$resource->date or '-'}}</td>
                  <td>{{$resource->id or '-'}}</td>
                  <td>{{$resource->ownerable->name or '-'}}</td>
                  <td>{{$resource->ownerable->phone_1 or '-'}}</td>
                  <td>{{$resource->branch->name or '-'}}</td>
                  <td>
                    @if(\App\Models\Order::where('id', $resource->id)->whereHas('orderDetails', function ($query) {
                      $query->where('status', 'refused');
                    })->count() > 0)
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#invoice_{{$resource->id}}">
                      <i class="fa fa-television"></i>
                    </button>
                    @else
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#invoice_{{$resource->id}}">
                      <i class="fa fa-television"></i>
                    </button>
                    @endif

                    <!-- Modal -->
                    <div id="invoice_{{$resource->id}}" class="modal fade" role="dialog">
                      <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                              &times;
                            </button>
                            <h4 class="modal-title">الفاتورة</h4>
                          </div>
                          <div class="modal-body">
                            <div class="box">
                              <div class="box-header">
                                <table class="table table-responsive table-bordered">
                                  <tr>
                                    <td>رقم الفاتورة</td>
                                    <td>{{$resource->id}}</td>
                                    <td>التاريخ</td>
                                    <td>{{$resource->date}}</td>
                                  </tr>
                                  <tr>
                                    <td>العميل</td>
                                    <td>{{$resource->ownerable->name or '-'}}</td>
                                    <td>الموظف</td>
                                    <td>{{$resource->user->name or '-'}}</td>
                                  </tr>
                                  <tr>
                                    <td>الخزنة</td>
                                    <td>{{$resource->reposite->name or '-'}}</td>
                                    <td></td>
                                    <td></td>
                                  </tr>
                                  <tr>
                                    <td>المدفوع</td>
                                    <td>{{$resource->cost or '-'}}</td>
                                    <td>الخصم</td>
                                    <td>{{$resource->discount or '-'}}</td>
                                  </tr>
                                  <tr>
                                    <td>المتبقي</td>
                                    <td>{{$resource->total - $resource->cost -$resource->discount}}</td>
                                    <td> الإجمالي</td>
                                    <td>{{$resource->total or '-'}}</td>
                                  </tr>
                                  <tr>
                                    <td>مسئول المخزن</td>
                                    <td>{{$resource->receiver->name or '-'}}</td>
                                  </tr>
                                </table>
                              </div>
                              <div class="box-body">
                                <table class="table table-responsive table-bordered">
                                  <tr>
                                    <td>الصنف</td>
                                    <td>المخزن</td>
                                    <td>الكمية</td>
                                    <td>السعر</td>
                                    <td>الخصم</td>
                                    <td>الإجمالي</td>
                                    <td>الحالة</td>
                                    <td>ملاحظات</td>
                                  </tr>
                                  <?php
                                                                    $sub_total = 0;
                                                                    $sub_total_quantity = 0;
                                                                    $sub_total_price = 0;
                                                                    $sub_total_discount = 0;
                                                                    ?>
                                  @if(!empty($resource->orderDetails))
                                  @foreach($resource->orderDetails as $order_detail)
                                  <?php
                                                                            $sub_total_quantity = $sub_total_quantity + $order_detail->quantity;
                                                                            $sub_total_price = $sub_total_price + $order_detail->unite_price;
                                                                            $sub_total_discount = $sub_total_discount + $order_detail->discount;
                                                                            $sub_total = $sub_total + (($order_detail->unite_price * $order_detail->quantity) - $order_detail->discount);
                                                                            if($order_detail->is_oper_supplies == 1){
                                                                                $itemName = DB::table('supplies')->where('id', $order_detail->item_id)->first()->name;
                                                                            } else {
                                                                                $itemName = $order_detail->item->name;
                                                                            }
                                                                            ?>
                                  <tr>
                                    <td>{{$itemName or '-'}}</td>
                                    <td>{{$order_detail->store->name or '-'}}</td>
                                    <td>{{$order_detail->quantity}}</td>
                                    <td>{{$order_detail->unite_price}}</td>
                                    <td>{{$order_detail->discount}}</td>
                                    <td>{{($order_detail->unite_price*$order_detail->quantity)-$order_detail->discount}}
                                    </td>
                                    <td>
                                      @if($order_detail->status == 'accepted')
                                      <span class="text-success">مقبول <i class="fa fa-check"
                                          aria-hidden="true"></i></span>
                                      @endif
                                      @if($order_detail->status == 'pending')
                                      <span class="text-warning">فى الانتظار <i class="fa fa-clock-o"
                                          aria-hidden="true"></i></span>
                                      @endif
                                      @if($order_detail->status == 'refused')
                                      <span class="text-danger">مرفوض <i class="fa fa-ban"
                                          aria-hidden="true"></i></span>
                                      @endif
                                    </td>
                                    <td>{{$order_detail->notes or '-'}}</td>
                                  </tr>
                                  @endforeach
                                  @endif
                                  {{--<tr>--}}
                                    {{--<td colspan="2">الإجمالي</td>--}}
                                    {{--<td>{{$sub_total_quantity}}</td>--}}
                                    {{--<td>{{$sub_total_price}}</td>--}}
                                    {{--<td>{{$sub_total_discount}}</td>--}}
                                    {{--<td>{{$sub_total}}</td>--}}
                                    {{--</tr>--}}
                                </table>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>{{$resource->total}}</td>
                  <td>{{$resource->discount}}</td>
                  <td>{{$resource->cost}}</td>
                  <td>{{$resource->total - $resource->cost -$resource->discount}}</td>
                  <td style="white-space: nowrap">
                  @if($resource->status == 'checked')
                    @if(\App\Models\Order::where('id', $resource->id)->whereHas('orderDetails', function ($query) {
                      $query->where('status', 'pending');
                    })->count() > 0)
                      <span class="label label-warning">فحصت جزئيا <i class="fa fa-check" aria-hidden="true"></i></span>
                    @elseif(\App\Models\Order::where('id', $resource->id)->whereHas('orderDetails', function ($query) {
                      $query->whereIn('status', ['accepted', 'refused']);
                    })->count() > 0)
                    <span class="label label-success">فحصت كاملا <i class="fa fa-check" aria-hidden="true"></i></span>
                    @else
                    <span class="label label-danger">تم مسح بنود الفاتورة <i class="fa fa-ban" aria-hidden="true"></i></span>
                    @endif
                  @endif

                  @if($resource->status == 'pending')
                    @if(\App\Models\Order::where('id', $resource->id)->whereHas('orderDetails', function ($query) {
                      $query->where('status', 'accepted');
                    })->count() > 0)
                    <span class="label label-warning">فحصت جزئيا <i class="fa fa-check" aria-hidden="true"></i></span>
                    @elseif(\App\Models\Order::where('id', $resource->id)->whereHas('orderDetails', function ($query) {
                      $query->whereIn('status', ['pending', 'refused']);
                    })->count() > 0)
                    <span class="label label-warning">قيد الفحص <i class="fa fa-clock-o" aria-hidden="true"></i></span>
                    @else
                    <span class="label label-danger">تم مسح بنود الفاتورة <i class="fa fa-ban" aria-hidden="true"></i></span>
                    @endif
                  @endif
                  </td>
                  <td>
                    @if(Auth::user()->can('show_order'))
                    <a href="{{url('orders_out_store/'.$resource->id)}}" target="_blank" class="btn btn-success" title="إذن صرف من المخزن"><i class="fa fa-print"></i></a>
                    <a href="{{url('orders_out_license/'.$resource->id)}}" class="btn btn-instagram" target="_blank" title="إذن"><i class="fa fa-print"></i></a>
                    <a href="{{url('orders-out/'.$resource->id)}}" class="btn btn-info" target="_blank" title="فاتورة"><i class="fa fa-print"></i></a>
                    @endif

                    @if(Auth::user()->hasRole('admin') || Auth::user()->branch_id == $resource->branch_id)
                      @if(Auth::user()->can('edit_order') && Auth::user()->has_edit_orders == 1)
                        @if(\App\Models\Order::where('id', $resource->id)->whereHas('orderDetails', function ($query) {
                          $query->whereIn('status', ['pending', 'refused']);
                        })->count() > 0)
                          <a href="{{url('orders-out/'.$resource->id.'/edit')}}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                        @endif
                      @endif

                      @if(Auth::user()->can('delete_order'))
                        @if(\App\Models\Order::where('id', $resource->id)->whereHas('orderDetails', function ($query) {
                          $query->where('status', 'pending');
                        })->count() > 0)
                          @if($resource->cost <= 0)
                          <button class="btn btn-danger" data-toggle="modal" data-target="#delete_{{$resource->id}}"><i class="fa fa-trash-o"></i></button>
                          <div id="delete_{{$resource->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                    &times;
                                  </button>
                                  <h4 class="modal-title">تأكيد الحذف</h4>
                                </div>
                                <div class="modal-body">
                                  <p>هل أنت متأكد من الحذف ؟</p>
                                </div>
                                <div class="modal-footer">
                                  {{Form::open(['route'=>['orders-out.destroy',$resource->id],'method'=>'DELETE'])}}
                                  <button type="button" class="btn btn-default" data-dismiss="modal">لا
                                  </button>
                                  <button type="submit" class="btn btn-danger">نعم</button>
                                  {{Form::close()}}
                                </div>
                              </div>
                            </div>
                          </div>
                          @endif
                        @endif
                      @endif
                    @endif
                  </td>
                </tr>
                <?php
                  $counter++
                ?>
                @endforeach
            </tbody>
          </table>
          @if(!request()->status)
          {{$resources->links()}}
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@stop