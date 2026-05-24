@extends('layout.app')
@section('title','الخزن')
@section('sub-title','اضافة')
@section('content')
    <div class="row">
    @if($branches->count())
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> اضافة </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" class="validate" action="{{route('reposites.store')}}" method="POST" enctype="multipart/form-data">
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
                        <label for="balance">الرصيد</label>
                        <input type="text"  required class="form-control" name="balance" id="balance">
                      </div>
      
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="branch_id"> الفرع  </label>
                          <select  id="branch_id" class="form-control" name="branch_id">
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
                                @foreach ($stores as $store)
                                    <option value="{{$store->id}}">{{$store->name}}</option>                            
                                @endforeach
                          </select>
                        </div>
                    </div>
                  </div>


                    <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                          <label for="user_id"> الموظف  </label>
                          <select  id="user_id" class="form-control" name="user_id">

                          </select>
                        </div>
                    </div>
                  </div>

                

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="priority">الاولوية</label>
                        <input type="number"  required class="form-control" name="priority" id="priority">
                      </div>
      
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="priority">حالة الخزنة</label>
                        <select  id="mainly" class="form-control" name="mainly">
                            <option value="0">متغيرة</option>                            
                            <option value="1">ثابتة</option>                            
                          </select>
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
         @else
          <div class="col-md-12">
              <div class="alert alert-danger">
                لايوجد فروع لديها مستخدمين
              </div>          
          </div>
         @endif
        </div>

    </div>
@stop
@push('scripts')
<script>
    $(document).ready(function(){

      $branchId = $('#branch_id')
      $userId = $('#user_id')


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
                      method:'unique:reposites,name',
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


        getUsersInBranch()
        
        
        
        function getUsersInBranch()
        {
          $.ajax({
            url:'{{route("api.get-users-in-branch")}}',
            type:'POST',
            data:{
              branch_id:$branchId.find(':selected').val()
            },
            success:function(data){
              options =``;
              $(data.users).each(function(index , item){
                options+=`<option value="${item.id}">${item.name}</option>`
              });
              $userId.html(options);
            }
          })
        }

        $branchId.change(getUsersInBranch);


    })

</script>
@endpush