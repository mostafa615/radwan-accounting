@extends('layout.app')
@section('title','الاصناف')
@section('sub-title','الرئسية')
@section('content')
<style>
    input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}

input[type=number] {
    -moz-appearance:textfield; /* Firefox */
}
</style>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">الاصناف</h3>
                    <div class="box-btn">
                        <a class="btn btn-success  btn-sm btn-flat" href="{{route('items.create')}}">
                            اضافة
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <form method="POST" role="form" action="{{route('items.updateItemsData')}}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field('post') }}
                             <table class="table table-bordered" id="example1">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الكود</th>
                                    <th>المجموعة</th>
                                    <th>الاسم</th>
                                    <th>طول الوحدة</th>
                                    <th>عرض الوحدة</th>
                                    <th>السمك</th>
                                    <th>الوزن الكلي</th>
                                    <th>وزن استاندرد</th>
                                    <th>الكمية الكلية</th>
                                    <th>وزن الوحده</th>
                                    <!--<th>كمية بداية المدة</th>-->
                                    <!--<th>السعر</th>-->
                                    <!--<th>تغيير السعر</th>-->
                                    <th>الاعدادات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($resources as $resource)
                                    <div style="display:none"><?php $allQuantity = 0; $itemLength = 0; $itemWidth = 0;  $allWeight = 0;
                                                                $itemQuantities = $resource->quantities->where('ownerable_type','App\Models\Store');
                                                                 foreach($itemQuantities as $itemQuantity) {
                                                                     $allQuantity += $itemQuantity->quantity;
                                                                     $itemLength = $resource->length;
                                                                     $itemWidth = $resource->width;
                                                                     $itemWeight = $resource->weight_one;
                                                                 }
                                                   $allWeight = $allQuantity * $itemWeight;

                                    ?></div>
                                    <tr class="{{ $resource->active==0? 'bg-danger' : '' }}" >
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$resource->code}}</td>
                                        <td>{{$resource->group->name or ''}}</td>
                                        <td>{{$resource->name}}</td>
                                        <input type="hidden" name="resource[{{$resource->id}}][itemId]" value="{{$resource->id}}">
                                        
                                        <td><input type="number" step="0.01" name="resource[{{$resource->id}}][length]" id="length_{{$resource->id}}"
                                                   class="form-control text-center" value="{{$resource->length}}" style="width: 85px">
                                        </td>
                                        <td><input type="number" step="0.01" name="resource[{{$resource->id}}][width]" id="width_{{$resource->id}}"
                                                   class="form-control text-center" value="{{$resource->width}}" style="width: 85px">
                                        </td>
                                        <td><input type="number" step="0.01" name="resource[{{$resource->id}}][thickness]" id="thickness_{{$resource->id}}"
                                                   class="form-control text-center" value="{{$resource->thickness}}" style="width: 85px">
                                        </td>
                                        <td><input type="number" step="0.01" name="resource[{{$resource->id}}][weight]" id="weight_{{$resource->id}}"
                                                   class="form-control text-center weight{{$loop->iteration}}" value="{{$allWeight}}" style="width: 85px" onkeyup='$(".weight_one{{$loop->iteration}}").val(Math.round($(".weight{{$loop->iteration}}").val() / $(".quantity{{$loop->iteration}}").val() * 100)/100)'>
                                        </td>
                                        <td><input type="number" step="0.01" name="resource[{{$resource->id}}][standard_weight]" id="standard_weight_{{$resource->id}}"
                                            class="form-control text-center" value="{{$resource->standard_weight}}" style="width: 85px">
                                        </td>
                                        <td><input readonly type="number" name="resource[{{$resource->id}}][quantity]" id="quantity_{{$resource->id}}"
                                                   class="form-control text-center quantity{{$loop->iteration}}" value="{{$allQuantity}}" style="width: 85px">
                                        </td>
                                         <td><input readonly type="number" name="resource[{{$resource->id}}][weight_one]" id="weight_one_{{$resource->id}}"
                                                   class="form-control text-center weight_one{{$loop->iteration}}" value="{{ $resource->weight_one }}" style="width: 85px">
                                        </td>
                                        <!--<td><input type="number" name="resource[{{$resource->id}}][first_qnt]" id="first_qnt_{{$resource->id}}"-->
                                        <!--           class="form-control text-center" value="{{$resource->first_qnt}}" style="width: 85px">-->
                                        <!--</td>-->
                                        
                                        <!--<td><input type="number" name="price" id="price_{{$resource->id}}"-->
                                        <!--           class="form-control text-center" value="{{$resource->price}}" style="width: 85px">-->
                                        <!--</td>-->
                                        <!--<td>-->
                                        <!--    <button type="button" data-id="{{$resource->id}}"-->
                                        <!--            class="btn btn-warning btn-sm change_price"><i class="fa fa-check"></i>-->
                                        <!--    </button>-->
                                        <!--</td>-->
                                        <td>
                                            <a href="{{route('items.show',$resource->id)}}"
                                               class="btn  btn-sm btn-info  btn-flat">عرض</a>
                                            <a href="{{route('items.edit',$resource->id)}}"
                                               class="btn btn-sm  btn-warning  btn-flat">تعديل</a>
                                             <form action="{{route('items.destroy',$resource->id)}}" class="inline" method="POST">
                                                <!--{{csrf_field()}}-->
                                                <!--{{method_field('DELETE')}}-->
                                                <button user="submit" class="btn btn-sm confirm btn-danger  btn-flat">
                                                    حذف
                                                </button>
                                            </form> 
                                            @if ($resource->active == 0)
                                            <a href="{{ url('/item/active') }}/{{ $resource->id }}?active=1" class="btn btn-sm  btn-success  btn-flat">تفعيل</a>
                                            @else
                                            <a href="{{ url('/item/active') }}/{{ $resource->id }}?active=0" class="btn btn-sm  btn-danger  btn-flat">الغاء التفعيل</a>
                                            @endif
    
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-success"><i class="fa fa-plus"></i> حفظ </button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
        <script>
        $("#example1").DataTable({
            dom: 'Blfrtip',
            lengthMenu : [[10, 25, 50, 100, 250], [10, 25, 50, 100, 250]],
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
        });
        $(document).on('click', '.change_price', function () {
            var route = "{{url('change_price')}}";
            $.ajax({
                url: route,
                type: 'get',
                dataType: 'json',
                data: {id: $(this).data('id'), price: $('#price_' + $(this).data('id')).val()},
                success: function (data) {
                    if (data.status == 1) {
                        iziToast.success({
                            timeout: 1000,
                            transitionIn: 'flipInX',
                            transitionOut: 'flipOutX',
                            position: 'bottomLeft',
                            rtl: true,
                            message: data.msg,
                        });
                    }
                }
            });
        });
    </script>
@endpush 