@extends('layout.app')
@section('title','الحسابات')
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
            <form role="form" class="validate" action="{{route('accounts.update',$account)}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <input type="hidden" name="accountable_id" value="{{request()->id}}">
            <input type="hidden" name="accountable_type" value="{{request()->owner}}">
              <div class="box-body">
                    <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="date">التاريخ </label>
                                    @if(auth()->user()->hasRole('admin'))
                                    <input type="text" class="form-control date" name="date" id="date" required value="{{optional($account->date)->toDateString()}}">
                                    @else
                                    <input type="text" class="form-control" name="date" id="date" required value="{{optional($account->date)->toDateString()}}" readonly>
                                    @endif
                                </div>
                            </div>


                                    
                                <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="reposite_id"> الخزنة </label>
                                            <select name="reposite_id" id="reposite_id">
                                                @foreach ($reposites as $reposite )
                                                        <option data-max="{{$reposite->balance}}" value="{{$reposite->id}}" {{$account->reposite_id ==  $reposite->id?'selected':''}}>{{$reposite->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                </div>

                                <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="type"> الدفع  </label>
                                            <select id="type" required class="form-control  check">
                                                <option value="">اختر</option>
                                                <option value="in" {{$account->type == 'in'?'selected':''}}>{{$names['inName']}}</option>
                                              <option value="out"  {{$account->type == 'out'?'selected':''}}>{{$names['outName']}} </option>
                                            </select>
                                        </div>
                                </div>



                                <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="cost">القيمة  </label>
                                            <input id="cost" type="text" class="form-control" min="0" name="cost" required  value="{{$account->cost}}">
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

        $check = $('.check');
        $type = $('#type');
        $cost = $('#cost');
        $repositeId = $('#reposite_id')
        $orderId = $('#order_id')
        $('.validate').validate({
          rules:{
            no:{
              remote:{
                  type:'post',
                  url:'{{route('validate')}}',
                  data:{
                      field:"no",
                      value:function()
                      {
                          return $('[name=no]').val();
                      },
                      method:'unique:accounts,no,{{$account->id}}',
                  }
                  }
          }
            },
            messages:{
              no:{
                remote:'هذه القيمة موجودة مسبقا'
              }
            }
        })


       $check.change(function(){
            type = $type.find(':selected').val();
            if(type == 'out'){
                $cost.prop('max',max)
            } else { //out
               $cost.prop('max',null)
            }
        })

        $type.change(function(){
            $.ajax({
                url:"{{route('api.get-orders')}}",
                type:'POST',
                data:{
                    type: $type.find(':selected').val(),
                    id:'{{$account->accountable_id}}',
                    class:'{{$owners[get_class($account->accountable)]}}'
                    
                },
                success:function(data){
                    $orderId.html('');
                    options = `
                    <option value="">لايوجد</option>
                    `
                    $(data.orders).each(function(index , item){
                        options += `<option data-max="${item.final_total}" value="${item.id}">${item.no}</option>`
                    })
                    $orderId.html(options);
                },
                error:function(err){
                    console.log(err);
                    iziToast.error({
                        timeout: 0,
                        transitionIn: 'flipInX',
                        transitionOut: 'flipOutX',
                        position:'bottomLeft',
                        rtl:true,
                        message: 'خطأ بالسيرفر ',
                      });
                }
            })
        })


    })

</script>
@endpush