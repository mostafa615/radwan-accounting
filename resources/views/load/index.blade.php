@extends('layout.app')
@section('title','تحميل')
@section('sub-title','الرئسية')
@section('content')
    @inject('store','App\Models\Store')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">تحميل</h3>
                    <div class="box-btn">
                    @if(Auth::user()->can('add_load'))
                        @if(Auth()->user()->id == 1)
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">
                                إضافة
                            </button>
                            <div id="myModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">اختر المخزن</h4>
                                        </div>
                                        {{Form::open(['route'=>'load.create','method'=>'POST'])}}
                                        {{csrf_field()}}
                                        <div class="modal-body">
                                            <label>اختر المخزن </label>
                                            {{Form::select('store_id',$store->whereHas('quantities',function($query){$query->where('quantity','>','0');})->pluck('name','id'),['class'=>'form-control'])}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">إضافة</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal"> إغلاق
                                            </button>
                                        </div>
                                        {{Form::close()}}
                                    </div>

                                </div>
                            </div>
                        @else
                            {{Form::open(['route'=>'load.create','method'=>'POST'])}}
                            {{csrf_field()}}
                        <input type="hidden" name="store_id" value="{{$store->where('user_id',Auth()->user()->id)->first()->id}}">
                            <button type="submit" class="btn btn-success">إضافة</button>
                            {{Form::close()}}
                        @endif
                    @endif
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-bordered']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
    {!! $dataTable->scripts() !!}
@endpush 