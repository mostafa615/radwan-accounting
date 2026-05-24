
@extends('layout.app')
@section('title','المستخدمين')
@section('sub-title','اضافة')
@section('content')
    @if($errors->any())
      <div class="alert alert-danger">
        <div>
          @foreach($errors->all() as $error)
            <span>{{ $error }}</span>
          @endforeach
        </div>
      </div>
    @endif
    <div class="row">
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> اضافة مستخدم جديد </h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <form role="form" class="validate" action="{{route('users.store')}}" method="POST">
              {{csrf_field()}}
              <div class="box-body">                 
                <div class="form-group">
                  <label for="emp_id">الاسم</label>
                  <select class="form-control" name="emp_id">
                        <option selected disabled>اختر من القائمة</option>
                        @foreach($Employees as $emp)
                          <option value="{{$emp->id}}">{{$emp->name}}</option>                            
                        @endforeach
                  </select>
                </div>

           <div class="form-group">
                  <label for="user_name">اسم المستخدم</label>

                  <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#Modal"> 
                    <i class="fa fa-plus"></i>  
                  </button>
                  <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#Modal2"> 
                    <i class="fa fa-list"></i>  
                  </button>

                  <select class="form-control" name="user_name">
                    <option selected disabled>اختر من القائمة</option>
                    @foreach($usernames as $username)
                      <option value="{{$username->name}}">{{$username->name}}</option>                            
                    @endforeach
                  </select>
                </div>


                <div class="form-group">
                  <label for="password">كلمة المرور</label>
                  <input type="password" class="form-control" name="password" id="password">
                </div>

              <div class="form-group">
                  <label for="job_id">الوظيفة</label>
                  <select id="job_id" class="form-control" name="job_id">
                      @foreach ($justRoles as $role)
                        <option value="{{$role->id}}">{{$role->display_name}}</option>                            
                      @endforeach
                  </select>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="branch_id">الفرع</label>
                      <select class="form-control" name="branch_id">
                          <option selected disabled>اختر من القائمة</option>

                          @foreach($branches as $branch)
                              <option value="{{$branch->id}}">{{$branch->name}}</option>                            
                          @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="store_id">المخزن</label>
                      <select class="form-control" name="store_id">
                          <option selected disabled>اختر من القائمة</option>

                          @foreach($stores as $store)
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

    <!-- Modal -->
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="ModalLabel">اضافة مستخدم جديد</h4>
          </div>
          <form action="{{route('usernames.store')}}" method="post">
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>اسم المستخدم</label>
                    <input class="form-control" type="text" name="name" required>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">اغلاق</button>
              <button type="submit" class="btn btn-primary">تأكيد</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="ModalLabel">عمليات على المستخدمين</h4>
          </div>

          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-bordered text-center">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>اسم المستخدم</th>
                    <th>العمليات</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 0 ?>
                  @foreach($usernames as $username)
                  <tr>
                    <td><?php $i++ ?>{{$i}}</td>
                    <td>{{$username->name}}</td>
                    <td>
                      <form action="{{route('usernames.destroy', $username->id)}}" method="post">
                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></button>
                      </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
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


