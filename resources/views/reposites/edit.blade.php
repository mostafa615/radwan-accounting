@extends('layout.app')
@section('title','الخزن')
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
            <form role="form" class="validate" action="{{route('reposites.update',$reposite)}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <div class="box-body">

                <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="name">الاسم</label>
                        <input type="text"  required class="form-control" name="name" id="name" value="{{$reposite->name}}">
                      </div>
      
                    </div>
                  </div>
  
                  <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="balance">الرصيد</label>
                          <input type="text"  required class="form-control" name="balance" id="balance" value="{{$reposite->balance}}" {{auth()->user()->id != 1? 'disabled' : ''}}>
                        </div>
        
                      </div>
                    </div>


                      <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="branch_id"> الفرع  </label>
                          <select  id="branch_id" class="form-control" name="branch_id">
                                @foreach ($branches as $branch)
                                    <option {{$reposite->branch_id == $branch->id?'selected':''}} value="{{$branch->id}}">{{$branch->name}}</option>                            
                                @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="store_id"> المخزن  </label>
                          <select  id="store_id" class="form-control" name="store_id">
                                @foreach ($stores as $store)
                                    <option {{$reposite->store_id == $store->id?'selected':''}} value="{{$store->id}}">{{$store->name}}</option>                            
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
                                  @foreach ($reposite->branch->users as $user )
                                      <option  {{$user->id == $reposite->user_id?'selected':''}} value="{{$user->id}}">{{$user->name}}</opti>
                                  @endforeach
                          </select>
                        </div>
                    </div>
                  </div>

                  <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="priority">الاولوية</label>
                          <input type="number"  required class="form-control" name="priority" id="priority" value="{{$reposite->priority}}" {{auth()->user()->id != 1? 'disabled' : ''}}>
                        </div>
        
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="priority">حالة الخزنة</label>
                            <select  id="mainly" class="form-control" name="mainly">
                                <option {{$reposite->mainly == 0 ?'selected':''}} value="0">متغيرة</option>                            
                                <option {{$reposite->mainly == 1 ?'selected':''}} value="1">ثابتة</option>                            
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
                      method:'unique:reposites,name,{{$reposite->id}}',
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