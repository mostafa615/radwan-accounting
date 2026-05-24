@extends('layout.app')
@section('title','النقلات')
@section('sub-title','تعديل')
@section('content')
    <div class="row">
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> تعديل </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" class="validate" action="{{route('transports.update',$transport)}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            {{method_field('PUT')}}
              <div class="box-body">

              

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="cost">التكلفة</label>
                    <input type="text"  required class="form-control" name="cost" id="cost" value="{{$transport->cost}}">
                    </div>
                  </div>
                </div>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label for="percent">النسبة</label>
                              <input type="text" required class="form-control number" value="{{$transport->percent}}" name="percent"
                                     id="percent">
                          </div>

                      </div>
                  </div>
                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="rate">مبلغ السواق</label>
                        <input type="text"  required class="form-control number" name="rate" id="rate" value="{{$transport->rate}}">
                      </div>
      
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="date">التاريخ</label>
                      <input type="text"  required class="form-control date" name="date" id="date" value="{{optional($transport->date)->toDateString()}}">
                      </div>
                    </div>
                  </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                        <label for="employee_id"> الموظف  </label>
                        <select  id="employee_id" class="form-control" name="employee_id">
                            @foreach ($employees as $employee)
                                <option {{$transport->employee_id == $employee->id?'selected':''}} value="{{$employee->id}}">{{$employee->name}}</option>   
                            @endforeach
                        </select>
                      </div>
                  </div>
                </div>
              
             
           

                          
             
                      
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="notes">ملاحظات </label>
                            <textarea class="form-control" name="notes" id="notes">{{$transport->notes}}</textarea>
                          </div>
                    </div>
                  </div>

                
                    
        </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-sm btn-success btn-flat">تعديل</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
         
        </div>
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