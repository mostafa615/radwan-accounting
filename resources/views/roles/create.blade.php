@extends('layout.app')
@section('title','مستوي الصلاحيات')
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
            <form role="form" class="validate" action="{{route('roles.store')}}" method="POST">
            {{csrf_field()}}
              <div class="box-body">

                <div class="form-group">
                  <label for="name">الاسم</label>
                  <input type="text" class="form-control" name="name" id="name">
                </div>
                
                <div class="form-group">
                  <label for="display_name">اسم العرض</label>
                  <input type="text" class="form-control" name="display_name" id="display_name">
                </div>


                <div class="form-group">
                  <label for="permissions">الصلاحيات</label>
                    <select class="form-control" name="permissions[]" id="permissions" multiple>
                        @foreach ($permissions as  $permission)
                            <option value="{{$permission->id}}">{{$permission->display_name}}</option>                               
                        @endforeach
                    </select>
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
                required:true,
                remote:{
                    type:'post',
                    url:'{{route('validate')}}',
                    data:{
                        field:"name",
                        value:function()
                        {
                            return $('[name=name]').val();
                        },
                        method:'unique:roles',
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