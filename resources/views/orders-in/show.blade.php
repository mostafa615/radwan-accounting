<html>
<header>
  <title>فاتورة رقم ({{$resource->id}})</title>
  @include('partials.styles')
</header>
<body onload="window.print()">
  <div class="header">
    <div class="row">
      <div class="col-xs-4">
        <h2 class=" text-center">
          @if($type=='invoice')
          فاتورة بيع
          @else
          أذن صرف - بيع
          @endif
        </h2>
      </div>
      <div class="col-xs-4"></div>
      <div class="col-xs-4">
        <img src="{{url('logo.jpeg')}}" style="width: 80px;height: 60px">
      </div>
    </div>
  </div>
  <div class="">
  </div>
  <div class="content">
    <div class="box">
      <div class="box-header">
        <div class="row">
          <label class="col-md-2">العميل</label>
          <label class="col-md-4">{{{$resource->ownerable->name or '-'}}}</label>
          <label class="col-md-2">هاتف العميل</label>
          <label class="col-md-4">{{{$resource->ownerable->phone_1 or '-'}}}</label>
          <label class="col-md-2">رقم الفاتورة</label>
          <label class="col-md-4">{{{$resource->id}}}</label>
          <label class="col-md-2">التاريخ</label>
          <label class="col-md-4">{{{$resource->date}}}</label>
        </div>
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

          @if(!empty($resource->orderDetails))
          @foreach($resource->orderDetails()->whereIn('status', ['pending', 'accepted'])->get() as $order_detail)
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

        </table>
        <div class="clearfix"></div>
        <br>
        <div class="row">
          <div class="col-xs-6">
            @if($type=='invoice')
            <table style="background-color: grey !important;width: 100%;">
              <tr>
                <td>السائق</td>
                <td>{{optional($resource->driver)->name }}</td>
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
                <td>{{{round($resource->total-$resource->cost -$resource->discount, 2)}}}</td>
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
    </div>
  </div>
          
 
  <footer style="position: fixed;
    bottom: -5px;
    width: 100%;">
    <div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="width: 100%!important; border-bottom: 2px solid #000; padding-bottom: 10px;margin-bottom:10px">
    <div class="text-center">
        <b>
            الرجاء التأكد من بنود وسلامه الأصناف عند التحميل 
            <br>
            لان خامه الاستانلس غير قابله للاسترجاع او التبديل بعد خروجها من مخازنا
        </b>
    </div>
</div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center" style="width: 33%!important">
        <b>السبتية / 01025009288</b>
        <br>

      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center" style="width: 33%!important">
        <b>قليوب / 01095797888</b>
        <br>

      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center" style="width: 33%!important">
        <b>٦ أكتوبر / 01007180405</b>
      </div>
    </div>
    <!--
    <div class="row"  >
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
    -->
    <br>

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