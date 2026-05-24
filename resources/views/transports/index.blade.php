@extends('layout.app')
@section('title','النقلات')
@section('sub-title','الرئسية')
@section('content')
<div class="row">
    <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">النقلات</h3>
              <div class="box-btn">
                    {{-- <a class="btn btn-success  btn-sm btn-flat" href="{{route('transports.create')}}">
                    اضافة
                    </a>   --}}
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
                        {{Form::open(['url'=>'update_transport_setting','method'=>'POST'])}}
                        <div class="modal-body">
                            <div class="form-group">
                                <label>
                                    نسبة السائق في النقله
                                </label>
                                {{Form::number('transport_percent',\App\Models\Setting::first() ? \App\Models\Setting::first()->transport_percent : 0,['class'=>'form-control'])}}
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
@stop
 @push('scripts')
<script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
{!! $dataTable->scripts() !!}
@endpush 