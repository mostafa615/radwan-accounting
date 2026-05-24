@extends('layout.app')
@section('title','النقلات')
@section('sub-title','اضافة')
@section('content')
    <div class="row">
        <div class="col-md-12">
        @if($employees->count())
            <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"> اضافة </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" class="validate" action="{{route('transports.store')}}" method="POST"
                          enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="box-body">
                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                            <input type="hidden" name="branch_id" value="{{auth()->user()->branch_id}}">


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cost">التكلفة</label>
                                        <input type="text" required class="form-control number" name="cost" id="cost">
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="percent">النسية</label>
                                        <input type="text" required class="form-control number" name="percent"
                                               id="percent">
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="rate">مبلغ السواق</label>
                                        <input type="text" required class="form-control number" name="rate" id="rate"
                                               disabled>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="date">التاريخ</label>
                                        <input type="text" required class="form-control date" name="date" id="date">
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="employee_id"> الموظف </label>
                                        <select id="employee_id" class="form-control" name="employee_id">
                                            @foreach ($employees as $employee)
                                                <option value="{{$employee->id}}">{{$employee->name}}</option>
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
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-sm btn-success btn-flat">اضافة</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->

        </div>
        @else
            <div class="alert alert-danger">
                لايوجد موظفين
            </div>

        @endif
    </div>
@stop
@push('scripts')
    <script>
        $(document).ready(function () {
            $('.validate').validate();
            $('#cost').on('change',function () {
                var percent=$('#percent').val();
                var cost=$('#cost').val();
                    var rate=parseFloat(parseFloat(percent) * parseFloat(cost))/100;
                    $('#rate').val(rate);
            });

            $('#percent').on('change',function () {
                var percent=$('#percent').val();
                var cost=$('#cost').val();
                var rate=parseFloat(parseFloat(percent) * parseFloat(cost))/100;
                $('#rate').val(rate);
            });

        })
    </script>
@endpush