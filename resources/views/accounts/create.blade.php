@extends('layout.app')
@section('title','الحسابات')
@section('sub-title','اضافة')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> اضافة </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" class="validate" action="{{route('accounts.store')}}" method="POST"
                      enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="accountable_id" value="{{request()->id}}">
                    <input type="hidden" name="accountable_type" value="{{request()->owner}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="date">التاريخ </label>
                                    @if(auth()->user()->hasRole('admin'))
                                    <input type="text" class="form-control date" name="date" id="date" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" autocomplete="off" required>
                                    @else
                                    <input type="text" class="form-control" name="date" id="date" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" autocomplete="off" required readonly>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="reposite_id"> الخزنة </label>
                                    <select name="reposite_id" id="reposite_id">
                                        @foreach ($reposites as $reposite )
                                            <option data-max="{{$reposite->balance}}"
                                                    value="{{$reposite->id}}">{{$reposite->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="type"> الدفع </label>
                                    <select id="type" required class="form-control  check" name="type">
                                        <option value="">اختر</option>
                                        <option value="in">{{$names['inName']}}</option>
                                        <option value="out">{{$names['outName']}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="cost">القيمة </label>
                                    <input id="cost" type="text" class="form-control" min="0" name="cost" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>ملاحظات </label>
                                    {{Form::textarea('notes',null,['class'=>'form-control','placeholder'=>'ملاحظات '])}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-sm btn-success btn-flat">اضافة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script>
        $(document).ready(function () {

            $check = $('.check');
            $type = $('#type');
            $cost = $('#cost');
            $repositeId = $('#reposite_id')
            $orderId = $('#order_id')
            clss = '{{request()->owner}}'
            $('.validate').validate({
                rules: {
                    no: {
                        remote: {
                            type: 'post',
                            url: '{{route('validate')}}',
                            data: {
                                field: "no",
                                value: function () {
                                    return $('[name=no]').val();
                                },
                                method: 'unique:accounts,no',
                            }
                        }
                    }
                },
                messages: {
                    no: {
                        remote: 'هذه القيمة موجودة مسبقا'
                    }
                }
            })


            $check.change(function () {
                type = $type.find(':selected').val();
                if (type == 'out') {
                    $cost.prop('max', max)
                } else { //out
                    $cost.prop('max', null)
                }
            })


        })

    </script>
@endpush