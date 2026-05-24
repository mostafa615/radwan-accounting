<html>
<header>
  <title>فاتورة مرتجع رقم ({{$resource->id}})</title>
  @include('partials.styles')
</header>
<body onload="window.print()">
  <div class="header">
    <div class="row">
      <div class="col-xs-4">
        <h2 class=" text-center">
          @if($type=='invoice')
          فاتورة مرتجع بيع
          @else
          أذن صرف - مرتجع بيع
          @endif
        </h2>
      </div>
      <div class="col-xs-4"></div>
      <div class="col-xs-4">
        <img src="{{url('logo.jpeg')}}" style="width: 80px;height: 60px">
      </div>
    </div>
  </div>
  <div class="content">
    <div class="box">
      <div class="box-header">
        <label class="col-md-3">التاريخ</label>
        <label class="col-md-3">{{{$resource->date}}}</label>
        <label class="col-md-3">العميل</label>
        <label class="col-md-3">{{{$resource->ownerable->name or '-'}}}</label>
        <label class="col-md-3">هاتف العميل</label>
        <label class="col-md-3">{{{$resource->ownerable->phone_1 or '-'}}}</label>
        <label class="col-md-3">الإجمالي </label>
        <label class="col-md-3">{{{$resource->total or '-'}}}</label>
        <label class="col-md-3">الخصم </label>
        <label class="col-md-3">{{{$resource->discount or '-'}}}</label>
        <label class="col-md-3">المدفوع </label>
        <label class="col-md-3">{{{$resource->cost or '-'}}}</label>
        <label class="col-md-3">المتبقي </label>
        <label class="col-md-3">{{{$resource->total-$resource->cost -$resource->discount}}}</label>
      </div>
      <div class="box-body">
        <table class="table table-responsive table-bordered">
          <tr>
            <td>الصنف</td>
            <td>المخزن</td>
            <td>الكمية</td>
            @if($type=='invoice')
            <td>السعر</td>
            <td>الخصم</td>
            <td>الإجمالي</td>
            @endif
            <td>الحالة</td>
          </tr>
          <?php
            $sub_total = 0;
            $sub_total_quantity = 0;
            $sub_total_price = 0;
            $sub_total_discount = 0;
          ?>
          @if(!empty($resource->orderDetails))
          @foreach($resource->orderDetails()->whereIn('status', ['pending', 'accepted'])->get() as $order_detail)
          <?php
            $sub_total_quantity = $sub_total_quantity + $order_detail->quantity;
            $sub_total_price = $sub_total_price + $order_detail->unite_price;
            $sub_total_discount = $sub_total_discount + $order_detail->discount;
            $sub_total = $sub_total + (($order_detail->unite_price * $order_detail->quantity) - $order_detail->discount);
          ?>
          <tr>
            <td>{{$order_detail->item->name or '-'}}</td>
            <td>{{$order_detail->store->name or '-'}}</td>
            <td>{{$order_detail->quantity}}</td>
            @if($type=='invoice')
            <td>{{$order_detail->unite_price}}</td>
            <td>{{$order_detail->discount}}</td>
            <td>{{($order_detail->unite_price*$order_detail->quantity)-$order_detail->discount}}</td>
            @endif
            <td>
              @if($order_detail->status == 'accepted')
              <span class="text-success">مصروف <i class="fa fa-check" aria-hidden="true"></i></span>
              @endif
              @if($order_detail->status == 'pending')
              <span class="text-warning">قيد الصرف <i class="fa fa-clock-o" aria-hidden="true"></i></span>
              @endif
            </td>
          </tr>
          @endforeach
          @endif
          @if($type=='invoice')
          <tr>
            <td colspan="2">الإجمالي</td>
            <td>{{$sub_total_quantity}}</td>
            <td>{{$sub_total_price}}</td>
            <td>{{$sub_total_discount}}</td>
            <td>{{$sub_total}}</td>
          </tr>
          @endif
        </table>
        <div class="clearfix"></div>
        <br>
        <div class="row">
          <div class="col-xs-6">
            @if($type=='invoice')
            <table style="background-color: grey !important;width: 100%;">
              <tr>
                <td>السائق</td>
                <td>{{optional($resource->driver)->name}}</td>
              </tr>
              <tr>
                <td>النقل</td>
                <td>{{round($resource->driving_cost, 2)}}</td>
              </tr>
              <tr>
                <td>الإجمالي</td>
                <td>{{round($resource->total, 2)}}</td>
              </tr>
              <tr>
                <td>الخصم</td>
                <td>{{{$resource->discount or '-'}}}</td>
              </tr>
              <tr>
                <td>المدفوع</td>
                <td>{{{$resource->cost or '-'}}}</td>
              </tr>
              <tr>
                <td>الخزنة</td>
                <td>{{{$resource->reposite->name or '-'}}}</td>
              </tr>
              <tr>
                <td>المتبقي</td>
                <td>{{{round($resource->total-$resource->cost - $resource->discount, 2)}}}</td>
              </tr>
            </table>
            @endif
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-12">
            {!! $resource->notes !!}
          </div>
        </div>
      </div>

      {{-- <div class="box-body">
        <table class="table table-bordered table-striped text-center">
          <thead>
            <tr>
              <th>اسماء الموظفين القائمين على الفاتورة</th>
            </tr>
          </thead>
          <tbody>
            @forelse($resource->employee as $employee)
            <tr>
              <td>{{$employee->name}}</td>
            </tr>
            @empty
            <tr>
              <td colspan="1" class="text-danger">لا يوجد موظفين لهذه الفاتورة</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="box-footer">
        <label class="col-md-3">مسؤول مخزن</label>
        <label class="col-md-3">{{{$resource->receiver->name or '-'}}}</label>
      </div> --}}
    </div>
  </div>
  <footer style="position: fixed;
    bottom: 0;
    width: 100%;">
    <div class="row">
      <div class="col-xs-8">
        السبتية : 1 شارع الرز - سوق العصر - القاهرة 01025009288
      </div>
      <div class="col-xs-4">
        قليوب الطريق البطئ : 01095797888
      </div>
    </div>
    <div class="row text-center">
      <div class="col-xs-2"></div>
      <div class="col-xs-8 text-center">

      </div>
      <div class="col-xs-2"></div>
    </div>

    <div class="row text-center">
      <div class="col-xs-2"></div>
      <div class="col-xs-8 text-center">
        www.elradwansteel.com E-mail:radwan304@yahoo.com
      </div>
      <div class="col-xs-2"></div>
    </div>
  </footer>
  @include('partials.scripts')
</body>

</html>