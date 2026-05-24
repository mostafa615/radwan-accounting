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
          فاتورة شراء
          @elseif($type == "store")
          أذن صرف مخزن - شراء
          @else
          أذن صرف - شراء
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
          <label class="col-md-2">المورد</label>
          <label class="col-md-4">{{{$resource->ownerable->name or '-'}}}</label>
          <label class="col-md-2">هاتف المورد</label>
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
            <td>الرصيد قبل الصرف</td>
            <td>الرصيد بعد الصرف</td>
            <td>الحالة</td>
          </tr>
          @if(!empty($resource->orderDetails))
          @foreach($resource->orderDetails()->whereIn('status', ['pending', 'accepted'])->get() as $order_detail)
          <?php
            if($order_detail->is_oper_supplies == 1) {
              $itemName = DB::table('supplies')->where('id', $order_detail->item_id)->first()->name;
            } else {
              $itemName = $order_detail->item->name;
            }
          ?>
          <tr>
            <td>{{$itemName or '-'}}</td>
            <td>{{$order_detail->store->name or '-'}}</td>
            <td>{{$order_detail->quantity}}</td>
            @if($order_detail->status == 'accepted')
            <td>
              <?php  
                try {
                  if($order_detail->is_oper_supplies == 1) {
                    echo DB::table('supplies')->where('id', $order_detail->item_id)->first()->quantity - $order_detail->quantity;
                  } else {
                    echo App\Models\Quantity::with('ownerable')->where('ownerable_id', $order_detail->store->id)->where('ownerable_type','App\Models\Store')->where('item_id',$order_detail->item->id)->where('quantity','>=',1)->first()->quantity - $order_detail->quantity;
                  }
                } catch(\Exception $e) {
                  echo 0;
                }
              ?>
            </td>
            <td>
              <?php  
                try{
                  if($order_detail->is_oper_supplies == 1) {
                    echo DB::table('supplies')->where('id', $order_detail->item_id)->first()->quantity;
                  } else {
                    echo App\Models\Quantity::with('ownerable')->where('ownerable_id', $order_detail->store->id)->where('ownerable_type','App\Models\Store')->where('item_id',$order_detail->item->id)->where('quantity','>=',1)->first()->quantity; 
                  }
                } catch(\Exception $e) {
                  echo 0;
                }
              ?>
            </td>
            @else
            <td>
              <?php  
                try {
                  if($order_detail->is_oper_supplies == 1) {
                    echo DB::table('supplies')->where('id', $order_detail->item_id)->first()->quantity;
                  } else {
                    echo App\Models\Quantity::with('ownerable')->where('ownerable_id', $order_detail->store->id)->where('ownerable_type','App\Models\Store')->where('item_id',$order_detail->item->id)->where('quantity','>=',1)->first()->quantity; 
                  }
                } catch(\Exception $e) {
                  echo 0;
                }
              ?>
            </td>
            <td>
              <?php  
                try {
                  if($order_detail->is_oper_supplies == 1) {
                    echo DB::table('supplies')->where('id', $order_detail->item_id)->first()->quantity + $order_detail->quantity;
                  } else {
                    echo App\Models\Quantity::with('ownerable')->where('ownerable_id', $order_detail->store->id)->where('ownerable_type','App\Models\Store')->where('item_id',$order_detail->item->id)->where('quantity','>=',1)->first()->quantity + $order_detail->quantity;
                  }
                } catch(\Exception $e) {
                  echo 0;
                }
              ?>
            </td>
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
          <div class="col-xs-12">
            {!! $resource->notes2 !!}
          </div>
        </div>
      </div>

      <div class="box-body">
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
      </div>
    </div>
  </div>

  <footer style="position: fixed;
    bottom: 0;
    width: 100%;">
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center" style="width: 50%!important">
        <b>السبتية / 01025009288</b>
        <br>

      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center" style="width: 50%!important">
        <b>قليوب / 01095797888</b>
        <br>
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