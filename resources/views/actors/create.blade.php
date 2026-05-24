@extends('layout.app')
@section('title','ممثلين الموردين')
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
            <form role="form" class="validate" action="{{route('actors.store')}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
              <div class="box-body">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name">الاسم</label>
                      <input type="text" class="form-control" name="name" id="name">
                    </div>
    
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="email">  البريد الالكتروني</label>
                          <input type="email" class="form-control" name="email" id="email">
                        </div>  
                  </div>                  
                </div>
              
             
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address">العنوان</label>
                            <input type="text" class="form-control" name="address" id="address">
                          </div>
                        </div>

                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whatsapp">واتس</label>
                                    <input type="text" class="form-control" name="whatsapp" id="whatsapp">
                                  </div>
                                </div>
                            
                  </div>

           
                  <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_1"> تليفون  1</label>
                                    <input type="text" class="form-control" name="phone_1" id="phone_1">
                                  </div>  
                            </div>       
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="phone_2"> تليفون 2</label>
                              <input type="text" class="form-control" name="phone_2" id="phone_2">
                          </div>
                      </div>
                                
                    </div>

                          
                  <div class="row">
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="phone_3"> تليفون  3</label>
                                    <input type="text" class="form-control" name="phone_3" id="phone_3">
                                  </div>
                            </div>     
                      </div>
    
                      
                <div class="row">
                    <div class="col-md-12">
                      
                        <div class="form-group">
                            <label for="notes">ملاحظات </label>
                            <textarea class="form-control" name="notes" id="notes"></textarea>
                          </div>

                    </div>
                  </div>


                  <div class="row">
                        <div class="col-md-12">
                          
                                <div class="form-group">
                                        <label for="country_id"> المحافظة</label>
                                        <select class="form-control" name="country_id" id="country_id">
                                            @foreach ($countries as $country )
                                                <option value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
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
         
        </div>
    </div>
@stop
@push('scripts')
<script>
    $(document).ready(function(){

        $('.validate').validate({
          rules:{
            email:{
              remote:{
                  type:'post',
                  url:'{{route('validate')}}',
                  data:{
                      field:"email",
                      value:function()
                      {
                          return $('[name=email]').val();
                      },
                      method:'unique:actors,email',
                  }
                  }
          },
            phone_1:{
              remote:{
                  type:'post',
                  url:'{{route('validate')}}',
                  data:{
                      field:"phone_1",
                      value:function()
                      {
                          return $('[name=phone_1]').val();
                      },
                      method:'unique:actors,phone_1|unique:actors,phone_2|unique:actors,phone_3',
                  }
                  }
          },
            phone_2:{
              remote:{
                  type:'post',
                  url:'{{route('validate')}}',
                  data:{
                      field:"phone_2",
                      value:function()
                      {
                          return $('[name=phone_2]').val();
                      },
                      method:'unique:actors,phone_1|unique:actors,phone_2|unique:actors,phone_3',
                  }
                  }
          },
          phone_3:{
            remote:{
                type:'post',
                url:'{{route('validate')}}',
                data:{
                    field:"phone_3",
                    value:function()
                    {
                        return $('[name=phone_3]').val();
                    },
                    method:'unique:actors,phone_1|unique:actors,phone_2|unique:actors,phone_3',
                }
                }
        }
            },
            messages:{
              email:{
                remote:'هذه القيمة موجودة مسبقا'
              },
              phone_1:{
                    remote:'هذه القيمة موجودة مسبقا'
                },
                phone_2:{
                  remote:'هذه القيمة موجودة مسبقا'
              },
              phone_3:{
                remote:'هذه القيمة موجودة مسبقا'
            }
            }
        })


    })

</script>
@endpush