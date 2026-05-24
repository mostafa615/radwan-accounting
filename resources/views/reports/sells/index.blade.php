@extends('layout.app')
@section('title','التقارير')
@section('sub-title','   اجمالي المبيعات')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">

              <li class="active"><a href="#attendance-detailed-tab" data-toggle="tab" aria-expanded="true">تفصيلي</a></li>
              <li class=""><a href="#attendance-abstracted-tab" data-toggle="tab" aria-expanded="false">اجمالي</a></li>
            
            </ul>
         <center><span style='margin-right:50px;'>   من : {{$from }}   الي : {{$to}}     
         <br>الفرع : {{$branch->name ?? 'الكل'}}  </span> </center>
            <div class="tab-content">
              <div class="tab-pane  active" id="attendance-detailed-tab">
                 <div class="table-responsive">
                 <table class="table table table-bordered text-center" id="example_1">
                    <thead>
                        <tr>
                            <td> #</td>
                            <td> التاريخ</td>
                            <td> الفرع</td>
                            <td> العميل</td>
                            <td> الفرع</td>
                            <td>الفاتورة</td>
                        </tr> 
                    </thead>
                    <tbody> 



                        @foreach($orders as $order) 
                        <?php 
                        $client = App\Models\Client::find($order->ownerable_id);              
                        $branch = App\Models\Branch::find($order->branch_id);              
                        
                        ?>
                        <tr>

                            <td><a href="{{url('orders-in/'.$order->id)}}" target="_blank"
                              >{{ $order->id }} </a></td>
                              <td>{{$order->date }}</td>
                              <td>{{ $branch->name ?? '' }}</td>
                              <td>{{ $client->name ?? '' }}</td>
                              <td>{{ $branch->name ?? '' }}</td>
                              <td>{{ $order->final_total }}</td>
                        
                        </tr>
                        @endforeach
                      
                    </tbody>
                  </table>

                </div>
              </div>
              <!-- /.tab-pane -->

                <div class="tab-pane  fade" id="attendance-abstracted-tab">
                 <div class="table-responsive">
                 <h4>مجموع المبيعات : {{ $sumTotal }}</h4>
                 <h4>مجموع المرتجعات : {{ $sumReturns }}</h4>
                 <h4 style = "color:green;"> الصافي : {{ $sumTotal - $sumReturns}}</h4>
                </div>
              </div>
              <!-- /.tab-pane -->

            </div>
            <!-- /.tab-content -->
          </div>

   <!-- Modal -->
                <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalLabel"> تقرير مبيعات خزنة</h4>
                      </div>
                      <form class="validate">
                      <div class="modal-body">
                
                      </div>
                      {{--  .modal-body  --}}
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm btn-flat" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-primary btn-sm btn-flat">موافق </button>
                      </div>
                    </form>
                    </div>
                  </div>
                </div>

            </div>


</div>
@stop
@push('scripts')
{{--  <script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>  --}}

<script>
  $form = $('.validate')
  $modal = $('#modal') 
  $branchId = $('#branch_id') 
  $from = $('#from')
  $to = $('#to')

  branchId = null;
  from = null;
  to = null;
  type = null;

  $(document).ready(function(){

  $form.validate();

   $form.submit(function(e){
     e.preventDefault();
     if($form.valid()){
       $modal.modal('hide');
       branchId = $branchId.val();
       from = $from.val();
       to = $to.val();
       $('.table').each(function(index , item){
         $(item).DataTable().clear().draw()
       })
     }
     })

        $('#attendance-detailed-table').DataTable({
                dom:'Bfrtip',
                paging:false,
                language:{
                url:'{{url('/vendor/datatables/arabic.json')}}'
                },
                processing: true,
                serverSide: true,
                ajax: {
                type:'POST',
                url:'{{route('reports.sells.detailed')}}',
                data:function(data){
                    data.branch_id = branchId;
                    data.from = from;
                    data.to = to;
                }
                },
                columns: [
                  { data: 'date', name: 'date',title:'التاريخ' },
                  { data: 'ownerable_id', name: 'ownerable_id',title:'العميل' },
                  { data: 'branch_id', name: 'branch_id',title:'الفرع' },
                  { data: 'final_total', name: 'final_total',title:'تكلفة الفاتورة' },
                  
                ],
                buttons: ['excel', 'print']
            });

            $('#attendance-abstracted-table').DataTable({
                dom:'Bfrtip',
                paging:false,
                language:{
                url:'{{url('/vendor/datatables/arabic.json')}}'
                },
                processing: true,
                serverSide: true,
                ajax: {
                type:'POST',
                url:'{{route('reports.sells.abstracted')}}',
                data:function(data){
                  data.branch_id = branchId;
                    data.from = from;
                    data.to = to;
                }
                },
                columns: [
                  { data: 'total_finals', name: 'total_finals',title:'مجموع المبيعات' },
                  { data: 'total_returns', name: 'total_returns',title:'مجموع المرتجعات' },
                  { data: 'difference', name: 'difference',title:'الصافي' },
                  
                ],
                buttons: ['excel', 'print']
            })



   })


 

</script>
@endpush