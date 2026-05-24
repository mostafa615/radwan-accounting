@extends('layout.app')
@section('title','الوظائف')
@section('sub-title','الرئسية')
@section('content')
<div class="row">
    <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">الوظائف</h3>
              <div class="box-btn">
                    <a class="btn btn-success  btn-sm btn-flat" href="{{route('jobs.create')}}">
                    اضافة
                    </a>   
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