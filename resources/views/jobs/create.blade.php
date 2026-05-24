@extends('layout.app')
@section('title','الوظائف')
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
            <form role="form" class="validate" action="{{route('jobs.store')}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
              <div class="box-body">

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="name">الاسم</label>
                      <input type="text"  required class="form-control" name="name" id="name">
                    </div>
    
                  </div>
                </div>
              
             
           

                          
             
                      
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">الوصف </label>
                            <textarea class="form-control" name="description" id="description"></textarea>
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
    </div>
@stop
@push('scripts')
<script>
    $(document).ready(function(){

        $('.validate').validate({
          rules:{
            name:{
              remote:{
                  type:'post',
                  url:'{{route('validate')}}',
                  data:{
                      field:"name",
                      value:function()
                      {
                          return $('[name=name]').val();
                      },
                      method:'unique:jobs,name',
                  }
                  }
          }
        },
            messages:{
              name:{
                remote:'هذه القيمة موجودة مسبقا'
              }
            }
        })


    })

</script>
@endpush