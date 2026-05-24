@extends('layout.app')
@section('title','السلف')
@section('sub-title','الرئسية')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">السلف</h3>
                <div class="box-btn">
                    <a class="btn btn-success  btn-sm btn-flat" href="{{route('loans.create')}}">
                        اضافة
                    </a>
                    @if(Auth::user()->can('loans_settings'))
                    <a class="btn btn-primary  btn-sm btn-flat" data-toggle="modal"
                       data-target="#loanSettingModal">
                        <i class="fa fa-gears"></i>
                    </a>
                    @endif
                </div>
                <!-- Modal -->
                <div id="loanSettingModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            {{Form::open(['url'=>'update_setting','method'=>'POST'])}}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>
                                        أعلي قيمة للسلفة
                                    </label>
                                    {{Form::number('loan_max_amount',\App\Models\Setting::first() ? \App\Models\Setting::first()->loan_max_amount : 0,['class'=>'form-control'])}}
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>
                                          تاريخ بدء السلف في الشهر
                                    </label>
                                    {{Form::number('loan_start_date',\App\Models\Setting::first() ? \App\Models\Setting::first()->loan_start_date : 0,['class'=>'form-control'])}}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">حفظ</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-bordered']) !!}
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>

<div class="modal modalMadionia" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form  action="{{route('madionia.pay')}}" method="POST" >
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="$('.modalMadionia').hide()" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">تسديد المديونيه</h4>
                </div>
                <div class="modal-body">
                    {{csrf_field()}} 
                    <div class="box-body">

                        {{-- <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">رقم السند</label>
                                    <input type="text"  required class="form-control" name="no" id="no">
                                </div> 
                            </div>
                        </div> --}}

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="date">التاريخ</label>
                                    <input type="text"  required class="form-control date" name="date" id="date">
                                </div> 
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="reposite_id"> الخزنة </label>
                                    <select name="reposite_id"  required id="reposite_id">
                                        @foreach (App\Models\Reposite::all() as $reposite )
                                        <option data-max="{{$reposite->balance}}" value="{{$reposite->id}}">{{$reposite->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> 
                        </div>
 
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="cost">باقى حساب المديونيه </label>
                                    <input type="text"  required class="form-control" name="cost" id="madioniaValue" >
                                </div>
                                
                            </div>
                        </div> 
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">الوصف </label>
                                    <textarea class="form-control" name="notes" id="notes"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="madioniaId" id="madioniaId" >
                    </div>
                    <!-- /.box-body -->
 
                </div>
                <div class="modal-footer"> 
                    <button type="submit" class="btn btn-sm btn-success btn-flat">اضافة</button> 
                </div>
            </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop
@push('scripts')
<script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
<script>
    function showMadioniaModal(id) {
        $.get('{{ url("/") }}/madionia/balance/api/' + id, function(r){
            $('#madioniaId').val(id);
            //var value = $('#loanRemainValue'+id).val();
            $('#madioniaValue').val(r); 
            $('.modalMadionia').show();
        });
    }
</script>


{!! $dataTable->scripts() !!}
@endpush 