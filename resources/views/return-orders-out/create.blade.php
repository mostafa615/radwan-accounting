@extends('layout.app')
@section('title','مرتجع الي المورد')
@section('sub-title','اضافة')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> اضافة </h3>
                </div>
                <div class="box-body">
                    <div id="errors" hidden>
                        <div class="alert alert-danger">
                            من فضلك ادخل كل البيانات للصنف
                        </div>
                    </div>
                    <div id="duplicate_errors" hidden>
                        <div class="alert alert-danger">
                            يرجي ملاحظة انه لا يمكن تكرار نفس الصنف
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
                                <label for="store_id">المخزن</label>
                                <select required id="store_id">
                                    <option value="0"> من فضلك اختر المخزن</option>
                                    @foreach ($stores as $store)
                                        <option value="{{$store->id}}">{{$store->name}}</option>
                                    @endforeach
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
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unite_price">سعر الوحدة</label>
                                <input type="text" required id="unite_price"
                                       @if(Auth::user()->can('can_edit_price') == 0) readonly
                                       @endif class="form-control order-form">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="item_discount">الخصم</label>
                                <input type="text" @if(Auth::user()->can('can_edit_price') == 0) readonly
                                       @endif required id="item_discount"
                                       value="0" class="form-control order-form">
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
        <!--
            route('return-orders-out.store')
        -->
        <form role="form" class="validate" action="{{ url('/return-orders-out/store') }}" method="POST"
              enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <table class="table text-center " id="table_items">
                            <thead>
                            <tr>
                                <th>المجموعة</th>
                                <th>الصنف</th>
                                <th>المخزن</th>
                                <th>الكمية</th>
                                <th>سعر الوحدة</th>
                                <th>الخصم</th>
                                <th>الاجمالي</th>
                                <th>حذف</th>
                            </tr>
                            </thead>
                            <tbody class="to-append">
                            </tbody>
                        </table>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="date">تاريخ البيع</label>
                                    @if(auth()->user()->hasRole('admin'))
                                    {{Form::text('date',\Carbon\Carbon::now()->format('Y-m-d'),['class'=>'form-control date','id'=>'date'])}}
                                    @else
                                    {{Form::text('date',\Carbon\Carbon::now()->format('Y-m-d'),['class'=>'form-control','id'=>'date','readonly' => true])}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="supplier_id"> المورد</label>
                                    <select class="form-control" name="supplier_id" id="supplier_id">
                                        @foreach ($suppliers as $supplier )
                                            <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vat">ضريبة القيمة المضافة</label>
                                    <input type="text" value="0" required class="form-control observe"
                                           name="vat" id="vat" onchange="update_final_final_total()">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount">الخصم</label>
                                    <input type="text" value="0" required class="form-control observe"
                                           name="discount" id="discount" onchange="update_final_final_total()">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total">الاجمالي</label>
                                    <input type="text" readonly required class="form-control" name="total"
                                           id="final_total">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="final_total">الاجمالي بعد الضريبة والخصم</label>
                                    <input type="text" readonly required class="form-control" name="final_total"
                                           id="final_final_total">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="reposite_id">الخزنة </label>
                                    <select name="reposite_id" id="reposite_id">
                                        @foreach ($reposites as $reposite )
                                            <option value="{{$reposite->id}}">{{$reposite->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cost">المدفوع</label>
                                    <input type="text" value="0" required class="form-control" name="cost"
                                           id="cost" onchange="update_final_final_total()">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="rest">الباقي</label>
                                    <input readonly type="text" value="0" required class="form-control"
                                           name="rest" id="rest">
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
        $('#group_id').on('change', function () {
            $.ajax({
                url: '{{url('group_items_buy')}}' + '/' + $('#group_id').val(),
                type: 'GET',
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
        $('#add').on('click', function () {
            var group_id = $('#group_id').val(),
                group_name = $('#group_id option:selected').text(),
                item_id = $('#item_id').val(),
                item_name = $('#item_id option:selected').text(),
                store_id = $('#store_id').val(),
                store_name = $('#store_id option:selected').text(),
                quantity = $('#quantity').val(),
                price = $('#unite_price').val(),
                item_discount = $('#item_discount').val();

            if (group_id == 0 || group_id == null) {
                $('#errors').show();
                $('#errors').delay(1500).fadeOut(350);
                return false;
            }
            if (item_id == 0 || item_id == null) {
                $('#errors').show();
                $('#errors').delay(1500).fadeOut(350);
                return false;
            }
            if (store_id == 0 || store_id == null) {
                $('#errors').show();
                $('#errors').delay(1500).fadeOut(350);
                return false;
            }
            if (quantity <= 0 || quantity == null) {
                $('#errors').show();
                $('#errors').delay(1500).fadeOut(350);
                return false;
            }
            if (price <= 0 || price == null) {
                $('#errors').show();
                $('#errors').delay(1500).fadeOut(350);
                return false;
            }
            
            var isDuplicate = false;
            $('#table_items tbody tr').each(function () {
                var existingItemId = $(this).find('input[name="item_id[]"]').val();
                var existingGroupId = $(this).find('input[name="group_id[]"]').val();
                if(existingItemId == item_id && existingGroupId == group_id) {
                    isDuplicate = true;
                    return false;
                }
            });

            if(isDuplicate) {
                $('#duplicate_errors').show().delay(1500).fadeOut(350);
                return false;
            }
            
            var total = (parseFloat(quantity) * parseFloat(price)) - parseFloat(item_discount);
            $('<tr>').html(
                '<td><input type="text" readonly class="form-control text-center" value="' + group_name + '"><input type="hidden" name="group_id[]" value="' + group_id + '"></td>' +
                '<td><input type="text" readonly class="form-control text-center" value="' + item_name + '"><input type="hidden" name="item_id[]" value="' + item_id + '"></td>' +
                '<td><input type="text" readonly  class="form-control text-center" value="' + store_name + '"><input type="hidden" name="store_id[]" value="' + store_id + '"></td>' +
                '<td><input type="text"  class="form-control text-center " data-id="' + item_id + '_' + store_id + '_' + group_id + '" onchange="update_quantity($(this).data(\'id\'))" name="quantity[]" value="' + quantity + '" id="' + item_id + '_' + store_id + '_' + group_id + '_quantity' + '"></td>' +
                '<td><input type="text"  class="form-control text-center" data-id="' + item_id + '_' + store_id + '_' + group_id + '" @if(Auth::user()->can('can_edit_price') == 0) readonly @endif onchange="update_quantity($(this).data(\'id\'))" name="price[]" value="' + price + '" id="' + item_id + '_' + store_id + '_' + group_id + '_price' + '"></td>' +
                '<td><input type="text" class="form-control text-center" name="item_discount[]"  @if(Auth::user()->can('can_edit_price') == 0) readonly   @endif data-id="' + item_id + '_' + store_id + '_' + group_id + '" onchange="update_quantity($(this).data(\'id\'))" value="' + item_discount + '" id="' + item_id + '_' + store_id + '_' + group_id + '_item_discount' + '"></td>' +
                '<td><input type="text" class="form-control" readonly value="' + total + '" id="' + item_id + '_' + store_id + '_' + group_id + '_total' + '"></td>' +
                '<td ><button  onclick="remove_row($(this).data(\'id\'));$(this).closest(\'tr\').remove()" data-id="' + item_id + '_' + store_id + '_' + group_id + '"  class="btn btn-block btn-danger">حذف</button></td>').appendTo('#table_items');
            final_total = parseFloat(final_total) + parseFloat(total);
            $('#final_total').val(final_total);
            update_final_final_total();
            $('#store_id').val(''),
                $('#quantity').val(0),
                $('#unite_price').val(0),
                $('#item_discount').val(0);
        });

        function update_quantity(id) {
            var current_quantity = $('#' + id + '_quantity').val(),
                current_price = $('#' + id + '_price').val(),
                current_item_discount = $('#' + id + '_item_discount').val(),
                current_total = $('#' + id + '_total').val();
            var new_total = (parseFloat(current_quantity) * parseFloat(current_price)) - parseFloat(current_item_discount);
            final_total = parseFloat(parseFloat(final_total) - parseFloat(current_total)) + parseFloat(new_total);
            $('#final_total').val(final_total);
            $('#' + id + '_total').val(new_total);
            update_final_final_total();
        }

        function remove_row(id) {
            current_total = $('#' + id + '_total').val();
            final_total = parseFloat(final_total) - parseFloat(current_total);
            $('#final_total').val(final_total);
            update_final_final_total();
        }

        function update_final_final_total() {
            final_final_total = (parseFloat($('#final_total').val()) + parseFloat(parseFloat($('#vat').val()) * parseFloat($('#final_total').val())) / 100) - parseFloat($('#discount').val());
            $('#final_final_total').val(final_final_total);
            $('#rest').val(parseFloat(final_final_total) - parseFloat($('#cost').val()));
        }

        $('#ownerable_type').on('change', function () {
            $.ajax({
                url: '{{url('get_ownerable')}}' + '/' + $('#ownerable_type').val(),
                type: 'GET',
                success: function (data) {
                    console.log(data);
                    $('#ownerable_id').children().remove();
                    if (data.type === 'Supplier') {
                        $('#ownerable_id').append('<option value="0"> من فضلك اختر  المورد  </option>');
                    } else {
                        $('#ownerable_id').append('<option value="0">  من فضلك اختر العميل      </option>');
                    }
                    $.each(data.data, function (e) {
                        $('#ownerable_id').append('<option value="' + data.data[e].id + '">' + data.data[e].name+'-'+data.data[e].phone_1 + '</option>');
                    });
                }
            })

        });
    </script>
@endpush