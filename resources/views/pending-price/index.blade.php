@extends('layout.app')
@section('title','طلبات تخفيض الاسعار')
@section('sub-title','الرئيسية')
@section('content')
<div class="row">
    <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">عرض</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="table-responsive">
                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-bordered']) !!} 
                   </div>
       
            </div>
            </div>

            <!-- /.box-body -->
         
          </div>
          <!-- /.box -->
        </div>

         <!-- Modal -->
         <div class="modal fade" id="showItemsModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
            <div  class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="ModalLabel">  الاسعار المقترحة  </h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-sm-12">
                          <div class="table-responsive">
                              <table width="100%" id="items-table" class="table table-bordered">
                              </table>
                          </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>



</div>   
@stop
@push('scripts')
<script>
    $form = $('.validate')
    $modal = $('#modal')
    $showItemsModal = $('#showItemsModal');
    $showItemsTable = $('#items-table');
    orderId = null;
  $(document).ready(function(){

  $form.validate();

        $showItemsTable.DataTable({
                dom:'Bfrtip',
                paging:false,
                language:{
                url:'{{url('/vendor/datatables/arabic.json')}}'
                },
                processing: true,
                serverSide: true,
                ajax: {
                type:'POST',
                url:'{{route('pending-price.show')}}',
                data:function(data){
                    data.order_id = orderId
                }
                },
                columns: [
                    { data: 'item', name: 'items.name',title:'الاسم' },
                    { data: 'original_price', name: 'items.price',title:'السعر الاساسي' },
                    { data: 'modified_price', name: 'order_details.unite_price',title:'السعر المعدل' },
                    { data: 'discount', name: 'order_details.discount',title:'الخصم' },
                    { data: 'store', name: 'stores.name',title:'المخزن' },
                    { data: 'quantity', name: 'order_details.quantity',title:'الكمية' },
                    { data: 'action', name: 'action',title:'عمليات' },
                ],
                buttons: ['reset','reload']
            });


            $(document).on('click','.show-items-btn',function(){
                $showItemsModal.modal('show');
                orderId = $(this).data('id');
                $('.table').DataTable().clear().draw();
            })


             $(document).on('click','.detect-status',function(){
                route = $(this).data('route');
                $.ajax({
                    url:route,
                    type:'PUT',
                    data:{
                        status:$(this).data('status')
                    },success: function(data) {
                                $('.table').DataTable().clear().draw();
                                iziToast.success({
                                    timeout: 1000,
                                    transitionIn: 'flipInX',
                                    transitionOut: 'flipOutX',
                                    position:'bottomLeft',
                                    rtl:true,
                                    message: 'تم التعديل بنجاح ',
                            });
                    }

                })
            })



   })
 

 

</script>
@endpush
@push('scripts')
<script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
{!! $dataTable->scripts() !!}
@endpush 