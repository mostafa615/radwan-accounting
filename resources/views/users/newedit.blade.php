@extends('layout.app')
@section('title','المستخدمين')
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
            <form role="form" class="validate" action="{{route('users.update',$user)}}" method="POST">
            {{csrf_field()}}
            {{method_field('PUT')}}            
              <div class="box-body">

                <div class="form-group">
                  <label for="emp_id"> الاسم  </label>
                  <select  class="form-control" name="emp_id">
                        <option value="">غير محدد</option>
                        @foreach ($Employees as $emp)
                            <option {{$user->emp_id  == $emp->id ?'selected':''}} value="{{$emp->id}}">{{$emp->name}}</option>                            
                        @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label for="user_name"> اسم المستخدم  </label>
                  <select  class="form-control" name="user_name">
                        <option value="">غير محدد</option>
                        @foreach ($usernames as $username)
                            <option  value="{{$username->name}}" {{$user->user_name  == $username->name ?'selected':''}}>{{$username->name}}</option>                            
                        @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label for="password">كلمة المرور</label>
                  <input type="password" class="form-control" name="password" id="password">
                </div>


                <div class="form-group">

                  <label for="job_id"> الوظيفة  </label>

                  <select id="job_id" class="form-control" name="job_id">

                        @foreach ($justRoles as $role)

                            <option value="{{$role->id}}"  {{optional($user->type)->id  === $role->id ?'selected':''}}>{{$role->display_name}}</option>                            

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
                              <option value="{{$branch->id}}"  {{$user->branch_id == $branch->id?'selected':''}}>{{$branch->name}}</option>
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
                              <option value="{{$store->id}}"  {{$user->store_id == $store->id?'selected':''}}>{{$store->name}}</option>
                          @endforeach
                    </select>
                  </div>
                </div>
              </div>
              

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="role_id"> مستوي الصلاحيات </label>
                    <select class="form-control" name="role_id">
                          @foreach ($roles as $role)
                              <option value="{{$role->id}}" {{optional($user->roles()->first())->id == $role->id?'selected':''}}>{{$role->display_name}}</option>
                          @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="user_type">نوع المستخدم</label>
                    <select class="form-control" name="user_type"  id="type">
                        <option value="system_user" {{$user->user_type == 'system_user'? 'selected':''}}>مستخدم سستم</option>                            
                        <option value="factory_user" {{$user->user_type == 'factory_user'? 'selected':''}}>مستخدم مصنع</option>                            
                    </select>
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
                        method:'unique:users,user_name,{{$user->id}}',
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