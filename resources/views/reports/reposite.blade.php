@extends('layout.app')
@section('title','التقارير')
@section('sub-title','كشف حساب خزنة')
@section('content')
<div class="row">
    <div class="col-md-12">
        
        @if($reposites->count())
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#clients-accounts-in-tab" data-toggle="tab" aria-expanded="true">وارد العملاء</a></li>
              <li class=""><a href="#clients-accounts-out-tab" data-toggle="tab" aria-expanded="false">صادر العملاء</a></li>
              <li class=""><a href="#suppliers-accounts-in-tab" data-toggle="tab" aria-expanded="false">وارد الموردين</a></li>
              <li class=""><a href="#suppliers-accounts-out-tab" data-toggle="tab" aria-expanded="false">صادر الموردين</a></li>
              <li class=""><a href="#daily-in-tab" data-toggle="tab" aria-expanded="false">ايرادات الخزنة</a></li>
              <li class=""><a href="#daily-out-tab" data-toggle="tab" aria-expanded="false">صادرات الخزنة</a></li>
              <li class=""><a href="#orders-in-rest-tab" data-toggle="tab" aria-expanded="false">اجل الخزنة</a></li>
              {{-- <li class=""><a href="#salaries-tab" data-toggle="tab" aria-expanded="false">الرواتب</a></li> --}}
              <li class=""><a href="#loans-tab" data-toggle="tab" aria-expanded="false">السلف</a></li>
              <li class="pull-left">
                  <button  data-toggle="modal" data-target="#modal" class="btn btn-success btn-flat btn-sm">
                      <i class="fa fa-cog fa-spin"></i>
                    </button>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane  active" id="clients-accounts-in-tab">
                 <div class="table-responsive">
                     <table width="100%" id="clients-accounts-in-table" class="table table-bordered"></table> 
                </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane fade" id="clients-accounts-out-tab">
               <div class="table-responsive">
                     <table width="100%" id="clients-accounts-out-table" class="table table-bordered"></table> 
                </div>
              </div>
              <!-- /.tab-pane -->

               <div class="tab-pane fade" id="suppliers-accounts-in-tab">
               <div class="table-responsive">
                     <table width="100%" id="suppliers-accounts-in-table" class="table table-bordered"></table> 
                </div>
              </div>
              <!-- /.tab-pane -->

               <div class="tab-pane fade" id="suppliers-accounts-out-tab">
               <div class="table-responsive">
                     <table  width="100%" id="suppliers-accounts-out-table" class="table table-bordered"></table> 
                </div>
              </div>
              <!-- /.tab-pane -->
{{--    --}}
               <div class="tab-pane fade" id="daily-in-tab">
               <div class="table-responsive">
                     <table  width="100%" id="daily-in-table" class="table table-bordered"></table> 
                </div>
              </div>
              <!-- /.tab-pane -->


               <div class="tab-pane fade" id="daily-out-tab">
               <div class="table-responsive">
                     <table  width="100%" id="daily-out-table" class="table table-bordered"></table> 
                </div>
              </div>
              <!-- /.tab-pane -->
 

               {{-- <div class="tab-pane fade" id="salaries-tab">
               <div class="table-responsive">
                     <table  width="100%" id="salaries-table" class="table table-bordered"></table> 
                </div>
              </div> --}}
              <!-- /.tab-pane -->


               <div class="tab-pane fade" id="loans-tab">
               <div class="table-responsive">
                     <table  width="100%" id="loans-table" class="table table-bordered"></table> 
                </div>
              </div>


              <div class="tab-pane fade" id="orders-in-rest-tab">
                  <div class="table-responsive">
                        <table  width="100%" id="orders-in-rest-table" class="table table-bordered"></table> 
                   </div>
                 </div>

              <!-- /.tab-pane -->
              {{--    --}}

            </div>
            <!-- /.tab-content -->
          </div>

   <!-- Modal -->
                <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modalLabel">كشف حساب خزنة</h4>
                      </div>
                      <form class="validate">
                      <div class="modal-body">
                      <div class="row">
                      
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="from">من</label>
                                <input required type="text" class="form-control date" name="from" id="from">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="to">الي</label>
                                <input  required type="text" class="form-control date" name="to" id="to">
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="reposite_id">الخزنة</label>
                                <select name="reposite_id" id="reposite_id">
                                    @foreach ($reposites as $reposite)
                                        <option value="{{$reposite->id}}">{{$reposite->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                      
                    </div> 
                    {{--  .row  --}}
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
                @else

                  <div class="alert alert-danger">
                    لايوجد خزن
                  </div>

                @endif
                
    </div>
</div>
@stop
@push('scripts')
{{--  <script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>  --}}

<script>
  $form = $('.validate')
  $modal = $('#modal')
  $repositeId = $('#reposite_id')
  $from = $('#from')
  $to = $('#to')

  repositeId = null;
  from = null;
  to = null;
  $(document).ready(function(){

  $form.validate();

   $form.submit(function(e){
     e.preventDefault();
     if($form.valid()){
       $modal.modal('hide');
       repositeId = $repositeId.val();
       from = $from.val();
       to = $to.val();

       console.log(to , from ,repositeId);
       $('.table').each(function(index , item){
         $(item).DataTable().clear().draw()
         console.log(index);
       })
     }
     })
   })


 



    $('#clients-accounts-in-table').DataTable({
        dom:'Bfrtip',
        language:{
          url:'{{url('/vendor/datatables/arabic.json')}}'
        },
        processing: true,
        serverSide: true,
        ajax: {
          type:'POST',
          url:'{{route('reports.reposite.clients-accounts-in')}}',
          data:function(data){
            data.reposite_id = repositeId;
            data.from = from;
            data.to = to;
          }
        },
        columns: [
            { data: 'id', name: 'accounts.id',title:'رقم السند' },
            { data: 'date', name: 'date',title:'التاريخ' },
            { data: 'name', name: 'reposites.name',title:'الخزنة' },
            { data: 'cost', name: 'accounts.cost',title:'المدفوع' },
            { data: 'order', name: 'orders.id',title:'رقم الفاتورة' },
        ],
         buttons: ['excel', 'print']
    });



    $('#clients-accounts-out-table').DataTable({
        dom:'Bfrtip',
        language:{
          url:'{{url('/vendor/datatables/arabic.json')}}'
        },
        processing: true,
        serverSide: true,
        ajax: {
          type:'POST',
          url:'{{route('reports.reposite.clients-accounts-out')}}',
          data:function(data){
            data.reposite_id = repositeId;
            data.from = from;
            data.to = to;
          }
        },
        columns: [
            { data: 'id', name: 'accounts.id',title:'رقم السند' },
            { data: 'date', name: 'date',title:'التاريخ' },
            { data: 'name', name: 'reposites.name',title:'الخزنة' },
            { data: 'cost', name: 'accounts.cost',title:'المدفوع' },
            { data: 'order', name: 'orders.id',title:'رقم الفاتورة' },
        ],
         buttons: ['excel', 'print']
    });


      $('#suppliers-accounts-in-table').DataTable({
        dom:'Bfrtip',
        language:{
          url:'{{url('/vendor/datatables/arabic.json')}}'
        },
        processing: true,
        serverSide: true,
        ajax: {
          type:'POST',
          url:'{{route('reports.reposite.suppliers-accounts-in')}}',
          data:function(data){
            data.reposite_id = repositeId;
            data.from = from;
            data.to = to;
          }
        },
        columns: [
            { data: 'id', name: 'accounts.id',title:'رقم السند' },
            { data: 'date', name: 'date',title:'التاريخ' },
            { data: 'name', name: 'reposites.name',title:'الخزنة' },
            { data: 'cost', name: 'accounts.cost',title:'المدفوع' },
            { data: 'order', name: 'orders.id',title:'رقم الفاتورة' },
        ],
         buttons: ['excel', 'print']
    });



    $('#suppliers-accounts-out-table').DataTable({
        dom:'Bfrtip',
        language:{
          url:'{{url('/vendor/datatables/arabic.json')}}'
        },
        processing: true,
        serverSide: true,
        ajax: {
          type:'POST',
          url:'{{route('reports.reposite.suppliers-accounts-out')}}',
          data:function(data){
            data.reposite_id = repositeId;
            data.from = from;
            data.to = to;
          }
        },
        columns: [
            { data: 'id', name: 'accounts.id',title:'رقم السند' },
            { data: 'date', name: 'date',title:'التاريخ' },
            { data: 'name', name: 'reposites.name',title:'الخزنة' },
            { data: 'cost', name: 'accounts.cost',title:'المدفوع' },
            { data: 'order', name: 'orders.id',title:'رقم الفاتورة' },
        ],
         buttons: ['excel', 'print']
    });


////////
     $('#daily-in-table').DataTable({
        dom:'Bfrtip',
        language:{
          url:'{{url('/vendor/datatables/arabic.json')}}'
        },
        processing: true,
        serverSide: true,
        ajax: {
          type:'POST',
          url:'{{route('reports.reposite.daily-in')}}',
          data:function(data){
            data.reposite_id = repositeId;
            data.from = from;
            data.to = to;
          }
        },
        columns: [
            { data: 'no', name: 'dailies.no',title:'رقم السند' },
            { data: 'date', name: 'dailies.date',title:'التاريخ' },
            { data: 'name', name: 'reposites.name',title:'الخزنة' },
            { data: 'cost', name: 'dailies.cost',title:'المدفوع' },
            { data: 'text', name: 'trees.text',title:'النوع' },
            { data: 'notes', name: 'dailies.notes',title:'ملاحظات' },

        ],
         buttons: ['excel', 'print']
    });


    $('#daily-out-table').DataTable({
        dom:'Bfrtip',
        language:{
          url:'{{url('/vendor/datatables/arabic.json')}}'
        },
        processing: true,
        serverSide: true,
        ajax: {
          type:'POST',
          url:'{{route('reports.reposite.daily-out')}}',
          data:function(data){
            data.reposite_id = repositeId;
            data.from = from;
            data.to = to;
          }
        },
        columns: [
            { data: 'no', name: 'dailies.no',title:'رقم السند' },
            { data: 'date', name: 'dailies.date',title:'التاريخ' },
            { data: 'name', name: 'reposites.name',title:'الخزنة' },
            { data: 'cost', name: 'dailies.cost',title:'المدفوع' },
            { data: 'text', name: 'trees.text',title:'النوع' },
            { data: 'notes', name: 'dailies.notes',title:'ملاحظات' },

        ],
         buttons: ['excel', 'print']
    });


     $('#salaries-table').DataTable({
        dom:'Bfrtip',
        language:{
          url:'{{url('/vendor/datatables/arabic.json')}}'
        },
        processing: true,
        serverSide: true,
        ajax: {
          type:'POST',
          url:'{{route('reports.reposite.salaries')}}',
          data:function(data){
            data.reposite_id = repositeId;
            data.from = from;
            data.to = to;
          }
        },
        columns: [
            { data: 'created_at', name: 'salaries.created_at',title:'التاريخ' },
            { data: 'employee', name: 'employees.name',title:'الخزنة' },
            { data: 'reposite', name: 'reposites.name',title:'الخزنة' },
            { data: 'net', name: 'net',title:'الصافي' },
            { data: 'notes', name: 'salaries.notes',title:'ملاحظات' },
        ],
         buttons: ['excel', 'print']
    });



      $('#loans-table').DataTable({
        dom:'Bfrtip',
        language:{
          url:'{{url('/vendor/datatables/arabic.json')}}'
        },
        processing: true,
        serverSide: true,
        ajax: {
          type:'POST',
          url:'{{route('reports.reposite.loans')}}',
          data:function(data){
            data.reposite_id = repositeId;
            data.from = from;
            data.to = to;
          }
        },
        columns: [
            { data: 'date', name: 'loans.date',title:'التاريخ' },
            { data: 'employee', name: 'employees.name',title:'الموظف' },
            { data: 'reposite', name: 'reposites.name',title:'الخزنة' },
            { data: 'cost', name: 'cost',title:'الصافي' },
            { data: 'notes', name: 'loans.notes',title:'ملاحظات' },

        ],
         buttons: ['excel', 'print']
    });





    $('#orders-in-rest-table').DataTable({
        dom:'Bfrtip',
        language:{
          url:'{{url('/vendor/datatables/arabic.json')}}'
        },
        processing: true,
        serverSide: true,
        ajax: {
          type:'POST',
          url:'{{route('reports.reposite.orders-in-rest')}}',
          data:function(data){
            data.reposite_id = repositeId;
            data.from = from;
            data.to = to;
          }
        },
        columns: [
            { data: 'id', name: 'orders.id',title:'الكود' },
            { data: 'date', name: 'orders.date',title:'التاريخ' },
            { data: 'buyer', name: 'buyer',title:'المشتري' },
            { data: 'reposite', name: 'reposites.name',title:'الخزنة' },
            { data: 'final_total', name: 'orders.final_total',title:'الاجمالي' },
            { data: 'cost', name: 'orders.cost',title:'المدفوع' },
            { data: 'rest', name: 'orders.rest',title:'الاجل' },
            { data: 'notes', name: 'loans.notes',title:'ملاحظات' },

        ],
         buttons: ['excel', 'print']
    });







</script>
@endpush