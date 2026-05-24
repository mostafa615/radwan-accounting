@extends('layout.app')
@section('title','المخازن')
@section('sub-title','عرض')
@section('content')
<div class="row">
    <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">بيانات المخزن</h3>
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
        <div class="table-responsive">
            <table class="table table-responsive table-bordered">
                <tbody>
                        <tr>
                                <td>الاسم</td>
                                <td>{{$store->name}}</td>                        
                        </tr>
                        <tr>
                                        <td>1 تليفون</td>
                                        <td>{{$store->phone_1}}</td>                        
                        </tr>
                        <tr>
                                <td>2 تليفون</td>
                                <td>{{$store->phone_2}}</td>                        
                        </tr>

                        <tr>
                                <td>تليفون 3</td>
                                <td>{{$store->phone_3}}</td>                        
                        </tr>

                

                        <tr>
                                        <td>العنوان</td>
                                        <td>{{$store->address}}</td>                        
                        </tr>

                        <tr>
                                        <td>المحافظة</td>
                                        <td>{{optional($store->country)->name}}</td>                        
                        </tr>

                        
                        <tr>
                                        <td>الموظف</td>
                                        <td>{{optional($store->user)->name}}</td>                        
                        </tr>

                        <tr>
                                <td>النوع</td>
                                <td>{{$store->type}}</td>                        
                        </tr>
                </tbody>
            </table>
              </div>
            </div>
            <!-- /.box-body -->
         
          </div>
          <!-- /.box -->
        </div>


         <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">الكميات من الاصناف</h3>
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="table-responsive">
              <form class="validate">
                  {!! $dataTable->table(['class' => 'table table-bordered']) !!} 
              </form>
            
            </div>
            </div>

            <!-- /.box-body -->
         
          </div>
          <!-- /.box -->
        </div>


</div>

@stop
@push('scripts')
<script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
{!! $dataTable->scripts() !!}
@endpush 

@push('scripts')
<script>
  $(document).ready(function(){
    $('.validate').validate();

    $(document).on('click','.set-item-quantity',function(e){
      e.preventDefault();
      id =  $(this).data('id');
      item = `#item-${id}`
      if($(item).valid()){
        $.ajax({
          type:'POST',
          url:'{{route('api.store.set-quantitity-of-item')}}',
          data:{
            id:id,
            quantity:$(item).val()
          },
          success:function(data){
            iziToast.success({
                    timeout: 1000,
                    transitionIn: 'flipInX',
                    transitionOut: 'flipOutX',
                    position:'bottomLeft',
                    rtl:true,
                    message: 'تم التعديل بنجاح ',
            });

          },
          error:function(err){
            console.log(err)
          }
        })


      }
    })
  })
</script>
@endpush 
