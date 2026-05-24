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

        </div>
        <div class="box-body">
            <table class="table table-responsive table-bordered">
                <tr>
                    <td>الصنف</td>
                    <td>الكمية</td>
                </tr>
                @if(!empty($resource->loadDetails))
                    @foreach($resource->loadDetails as $item)
                        <tr>
                            <td>{{$item->item->name or '-'}}</td>
                            <td>{{$item->quantity or '-'}}</td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>
</div>
@include('partials.scripts')
</body>
</html>