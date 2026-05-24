@extends('layout.app')
@section('title','تحميل')
@section('sub-title','عرض')
@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">الموظفين القائمين على الارسال</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-center">
            <tbody>
              @forelse($load->sendEmployees as $employee)
              <tr>
                <td>{{$employee->name}}</td>
              </tr>
              @empty
              <tr>
                <td colspan="1" class="text-danger">لم يتم العثور على بيانات</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <label class="col-md-3">مسؤول مخزن</label>
        <label class="col-md-3">{{{$load->sender->name or '- - - - -'}}}</label>
      </div>
    </div>
    <!-- /.box -->
  </div>

  <div class="col-md-6">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">الموظفين القائمين على الاستقبال</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-center">
            <tbody>
              @forelse($load->receiveEmployees as $employee)
              <tr>
                <td>{{$employee->name}}</td>
              </tr>
              @empty
              <tr>
                <td colspan="1" class="text-danger">لم يتم العثور على بيانات</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <label class="col-md-3">مسؤول مخزن</label>
        <label class="col-md-3">{{{$load->receiver->name or '- - - - -'}}}</label>
      </div>
    </div>
    <!-- /.box -->
  </div>

  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">بيانات التحميل</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-responsive table-bordered">
            <tbody>
              <tr>
                <td>الكود</td>
                <td>{{$load->id}}</td>
              </tr>
              <tr>
                <td>التاريخ</td>
                <td>{{optional($load->date)->toDateString()}}</td>
              </tr>
              <tr>
                <td>من</td>
                <td>{{optional($load->from)->name}}</td>
              </tr>

              <tr>
                <td>الي</td>
                <td>{{optional($load->to)->name}}</td>
              </tr>

              <tr>
                <td>ملاحظات</td>
                <td>{{$load->notes}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>

  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">{{$load->no}}</h3>
        {{--
        <div class="box-btn">
          <a class="btn btn-success btn-sm btn-flat" href="{{ route('orders-in.create') }}">
            اضافة
          </a>
        </div>
        --}}
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
<script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
{!! $dataTable->scripts() !!} @endpush