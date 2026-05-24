@extends('layout.app')
@section('title','التحويل النقدي')
@section('sub-title','اضافة')
@section('content')
    <div class="row">

        <div class="col-md-12">
        @if($reposite && $reposites ||  Auth()->user()->id == 1)
            <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"> اضافة </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" class="validate" action="{{route('transactions.store')}}" method="POST"
                          enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="box-body">

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="date"> التاريخ</label>
                                        @if(auth()->user()->hasRole('admin'))
                                        <input type="text" required class="form-control date" name="date" id="date" value="{{\Carbon\Carbon::now()}}">
                                        @else
                                        <input type="text" required class="form-control" name="date" id="date" value="{{\Carbon\Carbon::now()}}" readonly>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="date"> القيمة </label>
                                        <input type="text" required class="form-control"
                                               @if(!empty(auth()->user()->reposite)) max="{{auth()->user()->reposite->balance}}"
                                               placeholder="الكمية المتاحة {{auth()->user()->reposite->balance}}"
                                               @endif  name="cost" id="cost">
                                    </div>
                                </div>
                                @if(Auth()->user()->id == 1)
                                    @inject('reposite_object','App\Models\Reposite')
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="from_id"> من </label>
                                            <select class="form-control select-store" name="from_id" id="from_id">
                                                @foreach ($reposites as $reposite )
                                                    <option data-id="{{$reposite->id}}" data-balance="{{$reposite->balance}}"
                                                            value="{{$reposite->id}}">{{$reposite->name}}</option>
                                                @endforeach
                                            </select>
                                            <!--{{Form::select('from_id',$reposite_object->pluck('name','id'),null,['class'=>'form-control'])}}-->
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="to_id">الي</label>
                                            {{Form::select('to_id',$reposite_object->pluck('name','id'),null,['class'=>'form-control'])}}
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="from_id" value="{{Auth()->user()->reposite->id}}">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="to_id"> الي</label>
                                            <select class="form-control select-store" name="to_id" id="to_id">
                                                @foreach ($reposites as $reposite )
                                                    <option data-id="{{$reposite->id}}"
                                                            value="{{$reposite->id}}">{{$reposite->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
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
                            <button type="submit" class="btn btn-sm btn-success btn-flat">اضافة</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            @else
                @unless(auth()->user()->reposite()->count() && Auth()->user()->id != 1)
                    <div class="alert alert-danger">
                        لاتمتلك الصلاحية
                    </div>
                @endunless
                @unless($reposite && Auth()->user()->id != 1)
                    <div class="alert alert-danger">
                        لايوجد رصيد في الخزنة الخاصة بك
                    </div>
                @endunless
                @unless($reposites && Auth()->user()->id != 1)
                    <div class="alert alert-danger">
                        لايوجد خزن للتحويل اليها
                    </div>
                @endunless
            @endif
        </div>
    </div>
@stop
@push('scripts')
    <script>
        $(document).ready(function () {
            $('.validate').validate({
                rules: {
                    name: {
                        remote: {
                            type: 'post',
                            url: '{{route('validate')}}',
                            data: {
                                field: "name",
                                value: function () {
                                    return $('[name=name]').val();
                                },
                                method: 'unique:groups,name',
                            }
                        }
                    }
                },
                messages: {
                    name: {
                        remote: 'هذه القيمة موجودة مسبقا'
                    }
                }
            })
            
            $('#cost').attr('placeholder','الكمية المتاحة '+ $(this).find(':selected').data('balance'));
        });
        $('#from_id').on('change', function() {
            $('#cost').attr('placeholder','الكمية المتاحة '+ $(this).find(':selected').data('balance'));
        });
    </script>
@endpush