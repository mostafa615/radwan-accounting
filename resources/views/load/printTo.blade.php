<html>
<header>
  <title>تحويل رقم ({{$resource->id}})</title>
  @include('partials.styles')
</header>

<body onload="window.print()">
  <div class="content">
    <h2 class="center-block text-center">تحويل رقم ({{$resource->id}})</h2>
    <div class="box">
      <div class="box-header">
        <label class="col-md-3">التاريخ</label>
        <label class="col-md-3">{{{$resource->date}}}</label>
        <label class="col-md-3">من مخزن</label>
        <label class="col-md-3">{{{$resource->from->name or '-'}}}</label>
        <label class="col-md-3">الي مخزن</label>
        <label class="col-md-3">{{{$resource->to->name or '-'}}}</label>
        <label class="col-md-3">ملاحظات</label>
        <label class="col-md-3">{{{$resource->notes or '-'}}}</label>
        <label class="col-md-3">بيانات مخزن</label>
        <label class="col-md-3">{{{$resource->to->name or '-'}}}</label>
      </div>

      <div class="box-body">
        <table class="table table-responsive table-bordered">
          <tr>
            <td>الصنف</td>
            <td>الكمية</td>
            <td>الكمية بعد التحويل</td>
          </tr>
          @if(!empty($resource->loadDetails))
          @foreach($resource->loadDetails as $item)
          <tr>
            <td>{{$item->item->name or '-'}}</td>
            <td>{{$item->quantity or '-'}}</td>
            <td>
              <?php  
                try {
                  echo App\Models\Quantity::with('ownerable')->where('ownerable_id', $resource->to->id)->where('ownerable_type','App\Models\Store')->where('item_id',$item->item->id)->where('quantity','>=',1)->first()->quantity; 
                } catch(\Exception $e){
                  echo 0;
                }
              ?>
            </td>
          </tr>
          @endforeach
          @endif
        </table>
      </div>

      <div class="box-body">
        <table class="table table-bordered table-striped text-center">
          <thead>
            <tr>
              <th>اسماء الموظفين القائمين على التحميل</th>
            </tr>
          </thead>
          <tbody>
            @forelse($resource->receiveEmployees as $employee)
            <tr>
              <td>{{$employee->name}}</td>
            </tr>
            @empty
            <tr>
              <td colspan="1" class="text-danger">لا يوجد موظفين لهذا التحميل</td>
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
  @include('partials.scripts')
</body>
</html>