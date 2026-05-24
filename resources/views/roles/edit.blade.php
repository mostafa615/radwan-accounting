@extends('layout.app')
@section('title','مستوي الصلاحيات')
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
            <form role="form" class="validate" action="{{route('roles.update',$role)}}" method="POST">
            {{csrf_field()}}
            {{method_field('PUT')}}
              <div class="box-body">

                <div class="form-group">
                  <label for="name">الاسم</label>
                  <input type="text" class="form-control" name="name" id="name" value="{{$role->name}}">
                </div>

               

           

            


                <div class="form-group">
                  <label for="permissions">الصلاحيات</label>
                    <select class="form-control" name="permissions[]" id="permissions" multiple>
                        @foreach ($permissions as  $permission)
                            <option value="{{$permission->id}}" {{$role->perms->contains($permission)?'selected':''}}>{{$permission->display_name}}</option>                               
                        @endforeach
                    </select>
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
                        method:'unique:roles,name,{{$role->id}}',
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