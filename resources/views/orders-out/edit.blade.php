@extends('layout.app')
@section('title','تعديل بيانات فاتورة')
@section('sub-title','الرئسية')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">تعديل فاتورة شراء </h3>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table table-bordered">
            <tr>
              <th>رقم الفاتورة</th>
              <th>العميل</th>
              <th>هاتف العميل</th>
              <th>الاجمالي</th>
              <th>الخصم</th>
              <th>المدفوع</th>
              <th>المتبقي</th>
            </tr>
            <tr>
              <td>{{$resource->id or '-'}}</td>
              <td>{{$resource->ownerable->name or '-'}}</td>
              <td>{{$resource->ownerable->phone_1 or '-'}}</td>
              <td>{{$resource->total}}</td>
              <td>{{$resource->discount}}</td>
              <td>{{$resource->cost}}</td>
              <td>{{$resource->total-$resource->cost}}</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="box-body">
        {{Form::open(['route'=>['orders-out.update',$resource->id],'method'=>'PUT'])}}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>ملاحظات</label>
              <textarea name="resource_note" class="form-control" rows="4">{{ $resource->notes }}</textarea>
            </div>
          </div>
        </div>
        <table class="table table-responsive table-bordered">
          <tr>
            <td>حذف</td>
            <td>الصنف</td>
            <td>المخزن</td>
            <td>الكمية</td>
            <td>السعر</td>
            <td>الخصم</td>
            <td>الإجمالي</td>
          </tr>
          <?php
            $sub_total = 0;
            $sub_total_quantity = 0;
            $sub_total_price = 0;
            $sub_total_discount = 0;  
          ?>
          @if(!empty($resource->orderDetails))
          @foreach($resource->orderDetails()->get() as $order_detail)
          <?php
            $sub_total_quantity = $sub_total_quantity + $order_detail->quantity;
            $sub_total_price = $sub_total_price + $order_detail->unite_price;
            $sub_total_discount = $sub_total_discount + $order_detail->discount;
            $sub_total = $sub_total + (($order_detail->unite_price * $order_detail->quantity) - $order_detail->discount);
            if($order_detail->is_oper_supplies == 1){
              $itemName = DB::table('supplies')->where('id', $order_detail->item_id)->first()->name;
            }else{
              $itemName = $order_detail->item->name;
            }
          ?>
          <tr>
            <input type="hidden" name="detail_id[]" value="{{$order_detail->id}}">
            <td><input type="checkbox" name="selected_items[]" value="{{$order_detail->id}}" @if($order_detail->status == 'accepted') disabled @endif></td>
            <td>{{$itemName or '-'}}</td>
            <td>{{$order_detail->store->name or '-'}}</td>
            <td><input type="text" name="quantity[]" class="form-control" value="{{$order_detail->quantity}}" @if($order_detail->status == 'accepted') readonly @endif></td>
            <td><input type="text" name="price[]" @if(Auth::user()->can('can_edit_price') == 0) readonly @endif class="form-control" value="{{$order_detail->unite_price}}" @if($order_detail->status == 'accepted') readonly @endif></td>
            <td><input type="text" name="discount[]" @if(Auth::user()->can('can_edit_price') == 0) readonly @endif class="form-control" value="{{$order_detail->discount}}" @if($order_detail->status == 'accepted') readonly @endif></td>
            <td>{{($order_detail->unite_price*$order_detail->quantity)-$order_detail->discount}}</td>
          </tr>
          @endforeach
          @endif
          <tr>
            <td colspan="3">الإجمالي</td>
            <td>{{$sub_total_quantity}}</td>
            <td>{{$sub_total_price}}</td>
            <td>{{$sub_total_discount}}</td>
            <td>{{$sub_total}}</td>
          </tr>
          <tr>
            <td colspan="7">
              <button type="submit" class="btn btn-warning col-md-12"><i class="fa fa-save"></i>
              </button>
            </td>
          </tr>
        </table>
        {{Form::close()}}
      </div>
    </div>
  </div>
</div>
@stop