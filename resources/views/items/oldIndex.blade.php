@extends('layout.app')
@section('title','الاصناف')
@section('sub-title','الرئسية')
@section('content')
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
                        <table class="table table-bordered" id="example1">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>الكود</th>
                                <th>المجموعة</th>
                                <th>الاسم</th>
                                <th>الطول</th>
                                <th>العرض</th>
                                <th>الوزن</th>
                                <th>الكمية</th>
                                <th>كمية بداية المدة</th>
                                <th>السعر</th>
                                <th>تغيير السعر</th>
                                <th>الاعدادات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($resources as $resource)
                                <tr class="{{ $resource->active==0? 'bg-danger' : '' }}" >
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$resource->code}}</td>
                                    <td>{{$resource->group->name or ''}}</td>
                                    <td>{{$resource->name}}</td>
                                    <td><input type="number" name="length" id="length_{{$resource->id}}"
                                               class="form-control text-center" value="{{$resource->length}}" style="width: 80px">
                                    </td>
                                    <td><input type="number" name="width" id="width_{{$resource->id}}"
                                               class="form-control text-center" value="{{$resource->width}}" style="width: 80px">
                                    </td>
                                    <td><input type="number" name="weight" id="weight_{{$resource->id}}"
                                               class="form-control text-center" value="{{$resource->weight}}" style="width: 80px">
                                    </td>
                                    <td><input type="number" name="quantity" id="quantity_{{$resource->id}}"
                                               class="form-control text-center" value="{{$resource->quantity}}" style="width: 80px">
                                    </td>
                                    <td><input type="number" name="first_qnt" id="first_qnt_{{$resource->id}}"
                                               class="form-control text-center" value="{{$resource->first_qnt}}" style="width: 80px">
                                    </td>
                                    
                                    <td><input type="number" name="price" id="price_{{$resource->id}}"
                                               class="form-control text-center" value="{{$resource->price}}" style="width: 80px">
                                    </td>
                                    <td>
                                        <button type="button" data-id="{{$resource->id}}"
                                                class="btn btn-warning btn-sm change_price"><i class="fa fa-check"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <a href="{{route('items.show',$resource->id)}}"
                                           class="btn  btn-sm btn-info  btn-flat">عرض</a>
                                        <a href="{{route('items.edit',$resource->id)}}"
                                           class="btn btn-sm  btn-warning  btn-flat">تعديل</a>
                                        <form action="{{route('items.destroy',$resource->id)}}" class="inline"
                                              method="POST">
                                            {{csrf_field()}}
                                            {{method_field('DELETE')}}
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