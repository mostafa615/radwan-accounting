@extends('layout.app')
@section('title','المستخدمين')
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
            <form role="form" class="validate" action="{{route('users.store')}}" method="POST">
            {{csrf_field()}}
              <div class="box-body">

                <div class="form-group">
                  <label for="name">الاسم</label>
                  <input type="text" required class="form-control" name="name" id="name">
                </div>

                <div class="form-group">
                  <label for="user_name">اسم المستخدم</label>
                  <input type="text" class="form-control" name="user_name" id="user_name">
                </div>

                <div class="form-group">
                  <label for="password">كلمة المرور</label>
                  <input type="password" class="form-control" name="password" id="password">
                </div>

             <div class="form-group">

                  <label for="job_id"> الوظيفة  </label>

                  <select id="job_id" class="form-control" name="job_id">

                        @foreach ($justRoles as $role)

                            <option value="{{$role->id}}">{{$role->display_name}}</option>                            

                        @endforeach

                  </select>

                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="branch_id"> الفرع  </label>
                      <select  id="branch_id" class="form-control" name="branch_id">
                            <option value="">غير محدد</option>
                            @foreach ($branches as $branch)
                                <option value="{{$branch->id}}">{{$branch->name}}</option>                            
                            @endforeach
                      </select>
                  </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="store_id"> المخزن  </label>
                      <select  id="store_id" class="form-control" name="store_id">
                            <option value="">غير محدد</option>
                            @foreach ($stores as $store)
                                <option value="{{$store->id}}">{{$store->name}}</option>                            
                            @endforeach
                      </select>
                  </div>
                  </div>
                </div>
              



                <div class="form-group">

                  <label for="role_id"> مستوي الصلاحيات </label>

                  <select class="form-control" name="role_id"  id="role_id">

                        @foreach ($roles as $role)

                            <option value="{{$role->id}}">{{$role->display_name}}</option>                            

                        @endforeach

                  </select>

                </div>
                <div class="form-group">
                  <label for="user_type">نوع المستخدم</label>
                  <select class="form-control" name="user_type"  id="type">
                      <option value="system_user">مستخدم سستم</option>                            
                      <option value="factory_user">مستخدم مصنع</option>                            
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

  var $brachId = $('#branch_id');
  var $jobId = $('#job_id');
    $(document).ready(function(){
        $('.validate').validate({
          rules:{
            user_name:{
                required:true,
                remote:{
                    type:'post',
                    url:'{{route('validate')}}',
                    data:{
                        field:"user_name",
                        value:function()
                        {
                            return $('[name=user_name]').val();
                        },
                        method:'unique:users',
                    }
                    }
            }
            },
            messages:{
              user_name:{
                    remote:'هذه القيمة موجودة مسبقا'
                }
            }
        })



        function disableBranches() {

            if($jobId.find(':selected').val() == 1) {
              $brachId.find(':selected').prop('selected',false);
              $brachId.find('option').each(function(index , item){
                if(index != 0){
                  $(item).prop('disabled',true)
                }
              })
            } else {
               $brachId.find('option').prop('disabled',false)
            }
             $brachId.select2('destroy')
            setTimeout(function(){  $brachId.select2({width:'100%',dir:'rtl'})  }, 10);

        }

        disableBranches();
        $jobId.change(disableBranches)


    })

</script>
@endpush