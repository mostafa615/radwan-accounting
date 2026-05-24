@extends('layout.app')
@section('title','المجموعات')
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
            <form role="form" class="validate" action="{{route('group.update',$group)}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <div class="box-body">

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="name">الاسم</label>
                          <input type="text"  required class="form-control" name="name" id="name" value="{{$group->name}}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="price">السعر</label>
                          <input type="number" step="0.01" min="0" class="form-control" name="price" id="price" value="{{$group->price}}">
                        </div>
                      </div>
                    </div>
                    <!--<div class="row">-->
                    <!--  <div class="col-md-12">-->
                    <!--    <input type="hidden" name="edit_by_permission" value="0">-->

                    <!--        <div class="form-group">-->
                    <!--            <label>تفعيل التعديل بصلاحية</label>-->
                    <!--            <div class="custom-control custom-switch material-switch">-->
                    <!--                    <input type="checkbox" name="edit_by_permission" class="custom-control-input" id="groupSwitch{{$group->id}}"-->
                    <!--                    onchange="this.checked? this.value = 1 : this.value = 0" {{ $group->edit_by_permission == 1? 'checked' : ''}} >-->
                    <!--                    <label class="custom-control-label" for="groupSwitch{{$group->id}}"></label>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--</div>-->
                    <!--</div>-->
                                            
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes">ملاحظات </label>
                                <textarea class="form-control" name="notes" id="notes">{{$group->notes}}</textarea>
                              </div>
                        </div>
                      </div>
    
                    
                        
            </div>
                  <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" onclick="submitForm(this);" class="btn btn-sm btn-success btn-flat">تعديل</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
         
        </div>
    </div>
@stop
@push('scripts')
<script>
    function submitForm(btn) {
        // disable the button
        btn.disabled = true;
        // submit the form
        btn.form.submit();
    }
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
                      method:'unique:groups,name,{{$group->id}}',
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