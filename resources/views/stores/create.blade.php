@extends('layout.app')
@section('title','المخازن')
@section('sub-title','اضافة')
@section('content')
    <div class="row">
        <div class="col-md-12">
            @if($users->count())
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> اضافة </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" class="validate" action="{{route('stores.store')}}" method="POST" enctype="multipart/form-data">
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
                            <label for="address">العنوان</label>
                            <input type="text" class="form-control" name="address" id="address">
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
                       
                 
                      <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_3"> تليفون  3</label>
                                <input type="text" class="form-control" name="phone_3" id="phone_3">
                              </div>
                            </div>  


                            <div class="col-md-6">
                          
                                    <div class="form-group">
                                            <label for="type"> نوع المخزن</label>
                                            <select class="form-control" name="type" id="type">
                                                <option>رئيسي</option>
                                                <option>فرعي</option>

                                            </select>
                                          </div>
                            </div>

                    </div>

                      
                  <div class="row">
                        <div class="col-md-6">
                          
                                <div class="form-group">
                                        <label for="country_id"> المحافظة</label>
                                        <select class="form-control" name="country_id" id="country_id">
                                            @foreach ($countries as $country )
                                                <option value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                      </div>
                        </div>

                        <div class="col-md-6">
                          
                            <div class="form-group">
                                    <label for="user_id"> المستخدم</label>
                                    <select class="form-control" name="user_id" id="user_id">
                                        {{--  <option value="">لايوجد</option>  --}}
                                        @foreach ($users as $user )
                                            <option value="{{$user->id}}">{{$user->name}}</option>
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
         @else 
            @unless($users->count())
            <div class="alert alert-danger">
                لايوجد مستخدمين
            </div>

            @endunless
         @endif
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
                        method:'unique:stores,name',
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
                      method:'unique:stores,phone_1|unique:stores,phone_2|unique:stores,phone_3',
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
                      method:'unique:stores,phone_1|unique:stores,phone_2|unique:stores,phone_3',
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
                    method:'unique:stores,phone_1|unique:stores,phone_2|unique:stores,phone_3',
                }
                }
        },
        id_image_1:{
          extension:'jpg|jpeg|png'
        },
        id_image_2:{
          extension:'jpg|jpeg|png'
        }
            },
            messages:{
                name:{
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