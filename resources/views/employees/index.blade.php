@extends('layout.app')
@section('title','الموظفين')
@section('sub-title','الرئسية')
@section('content')
<div class="row">
    <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">الموظفين</h3>
              <div class="box-btn">
                    <a class="btn btn-success  btn-sm btn-flat" href="{{route('employees.create')}}">
                    اضافة
                    </a>   
              </div>
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="table-responsive">
             {!! $dataTable->table(['class' => 'table table-bordered']) !!} 
            
            </div>
            </div>

            <!-- /.box-body -->
         
          </div>
          <!-- /.box -->
        </div>

</div>
@stop
 @push('scripts')
 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
<script>
    
    /**
     * deactive api for employee from system
     * and active api for employee in system
     * 
     * @param String url
     * @return
     */
    function toggleActive(url) {
        swal({
          title: "هل انت متاكد", 
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) { 
            $.get(url, function(r){
                if (r.status == 1) {
                    swal({ 
                      text: r.msg,
                      icon: "success",
                      button: "ok",
                    });
                    
                    // reload page after send url
                    window.location.reload();
                } else {
                    swal({ 
                      text: r.msg,
                      icon: "error",
                      button: "ok",
                    });
                } 
            });
          } 
        });
    }
    
</script>
<script>
        function sendStatus(emp_id) {
            var selectedOption = emp_id;
            var select = document.getElementById('option_' + emp_id);
            var xhr = new XMLHttpRequest();
            if (select.checked) {
                xhr.open('GET', 'employee/summer_holiday_permission?emp_id=' + selectedOption + '&status=1', true);
            } else {
                xhr.open('GET', 'employee/summer_holiday_permission?emp_id=' + selectedOption + '&status=0', true);
            }
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log('Status sent successfully');
                        // Handle response
                        var response = JSON.parse(xhr.responseText);
                        $('#datatable').DataTable().ajax.reload();
                    } else {
                        console.error('Failed to send status');
                    }
                }
            };
            xhr.send();
        }
    </script>

{!! $dataTable->scripts() !!}
@endpush 