@extends('layout.app')
@section('title','تحويل خامات')
@section('sub-title','اضافة')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> اضافة تحويل </h3>
                </div>
                <div class="box-body">
                    <div id="quantity_errors" hidden>
                        <div class="alert alert-danger">
                            اختر كمية أقل من الكمية المتاحة في المخزن
                        </div>
                    </div>
                    <div id="errors" hidden>
                        <div class="alert alert-danger">
                            من فضلك ادخل كل البيانات للصنف
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="group_id">المجموعة</label>
                                <select required id="group_id">
                                    <option value="0"> من فضلك اختر المجموعة</option>
                                    @foreach ($groups as $group )
                                        <option value="{{$group->id}}">{{$group->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="item_id">الصنف</label>
                                <select required id="item_id">
                                    <option value="0"> من فضلك اختر الصنف</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantity">الكمية</label>
                                <input type="text" required id="quantity"
                                       class="form-control order-form">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-block btn-flat btn-sm btn-info"
                                    id="add">
                                <i class="fa  fa-chevron-down"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <form role="form" class="validate" action="{{route('load.store')}}" method="POST"
              enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" id="from_store_id" name="from_store_id" value="{{$store->id}}">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <table class="table text-center " id="table_items">
                            <thead>
                            <tr>
                                <th>المجموعة</th>
                                <th>الصنف</th>
                                <th>الكمية</th>
                                <th>حذف</th>
                            </tr>
                            </thead>
                            <tbody class="to-append">
                            </tbody>
                        </table>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">تاريخ التحويل</label>
                                    @if(auth()->user()->hasRole('admin'))
                                    {{Form::text('date',\Carbon\Carbon::now()->format('Y-m-d'),['class'=>'form-control date','id'=>'date'])}}
                                    @else
                                    {{Form::text('date',\Carbon\Carbon::now()->format('Y-m-d'),['class'=>'form-control','id'=>'date','readonly' => true])}}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="to_store_id">المخزن </label>
                                    <select name="to_store_id" id="to_store_id">
                                        @foreach ($to as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">ملاحظات </label>
                                    <textarea class="form-control" name="notes" id="notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-sm btn-success btn-flat" disabled>حفظ</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
@push('scripts')
    <script>
        var final_total = 0;
        var store_quantity = 0;
        $('#group_id').on('change', function () {
            $.ajax({
                url: '{{url('store_group_items')}}',
                type: 'GET',
                data: {store_id: $('#from_store_id').val(), group_id: $('#group_id').val()},
                success: function (data) {
                    console.log(data);
                    $('#item_id').children().remove();
                    $('#item_id').append('<option value="0">  من فضلك اختر الصنف    </option>');
                    $.each(data.data, function (e) {
                        $('#item_id').append('<option value="' + data.data[e].id + '">' + data.data[e].name + '</option>');
                    });
                }
            })

        });


        $('#item_id').on('change', function () {
            $.ajax({
                url: '{{url('item_store_info')}}' + '?item_id=' + $('#item_id').val() + '&&store_id=' + $('#from_store_id').val(),
                type: 'GET',
                success: function (data) {
                    store_quantity = data.data.quantity;
                    $('#quantity').attr('placeholder', 'الكمية المتاحه بالمخزن ' + data.data.quantity);
                }
            });
        });
        $('#add').on('click', function () {
            var group_id = $('#group_id').val(),
                group_name = $('#group_id option:selected').text(),
                item_id = $('#item_id').val(),
                item_name = $('#item_id option:selected').text(),
                quantity = $('#quantity').val();

            if (group_id == 0 || group_id == null || group_id == '') {
                $('#errors').show();
                $('#errors').delay(1500).fadeOut(350);
                return false;
            }
            if (item_id == 0 || item_id == null || item_id == '') {
                $('#errors').show();
                $('#errors').delay(1500).fadeOut(350);
                return false;
            }
            if (quantity <= 0 || quantity == null || quantity == '') {
                $('#errors').show();
                $('#errors').delay(1500).fadeOut(350);
                return false;
            }
            if (parseFloat(store_quantity) < parseFloat(quantity)) {
                $('#quantity_errors').show();
                $('#quantity_errors').delay(1500).fadeOut(350);
                $('#quantity').val('');
                return false;
            }
            $('<tr>').html(
                '<td><input type="text" readonly class="form-control text-center" value="' + group_name + '"><input type="hidden" name="group_id[]" value="' + group_id + '"></td>' +
                '<td><input type="text" readonly class="form-control text-center" value="' + item_name + '"><input type="hidden" name="item_id[]" value="' + item_id + '"></td>' +
                '<td><input type="text"  class="form-control text-center " data-id="' + item_id + '_' + group_id + '" onchange="update_quantity($(this).data(\'id\'))" name="quantity[]" value="' + quantity + '" id="' + item_id + '_' + group_id + '_quantity' + '"></td>' +
                '<td ><button  onclick="$(this).closest(\'tr\').remove()" data-id="' + item_id + '_' + group_id + '"  class="btn btn-block btn-danger">حذف</button></td>').appendTo('#table_items');

            $('#quantity').val('');
            store_quantity = 0;
        });


    </script>
@endpush