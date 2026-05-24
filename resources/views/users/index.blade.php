@extends('layout.app')
@section('title','المستخدمين')
@section('sub-title','الرئسية')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">المستخدمين</h3>
            <div class="box-btn">
                <a class="btn btn-success btn-sm btn-flat" href="{{route('users.create')}}">
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

@push('scripts')
    <script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
    {!! $dataTable->scripts() !!}
@endpush

<script>
    function sendStatus(userId) {
        var selectedOption = userId;
        var select = document.getElementById('option_' + userId);
        var xhr = new XMLHttpRequest();
        if (select.checked) {
            xhr.open('GET', 'user/returns?userId=' + selectedOption + '&status=1', true);
        } else {
            xhr.open('GET', 'user/returns?userId=' + selectedOption + '&status=0', true);
        }
        xhr.onreadystatechange = function () {
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
    
    function sendStatus2(userId) {
        var selectedOption = userId;
        var select = document.getElementById('option2_' + userId);
        var xhr = new XMLHttpRequest();
        if (select.checked) {
            xhr.open('GET', 'user/edit_orders?userId=' + selectedOption + '&status=1', true);
        } else {
            xhr.open('GET', 'user/edit_orders?userId=' + selectedOption + '&status=0', true);
        }
        xhr.onreadystatechange = function () {
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
    
        function sendStatus3(userId) {
            var selectedOption = userId;
            var select = document.getElementById('option3_' + userId);
            var xhr = new XMLHttpRequest();
            if (select.checked) {
                xhr.open('GET', 'user/edit_operation_order?userId=' + selectedOption + '&status=1', true);
            } else {
                xhr.open('GET', 'user/edit_operation_order?userId=' + selectedOption + '&status=0', true);
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
        
        function sendStatus4(userId) {
            var selectedOption = userId;
            var select = document.getElementById('option4_' + userId);
            var xhr = new XMLHttpRequest();
            if (select.checked) {
                xhr.open('GET', 'user/edit_operation_order_out?userId=' + selectedOption + '&status=1', true);
            } else {
                xhr.open('GET', 'user/edit_operation_order_out?userId=' + selectedOption + '&status=0', true);
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
@endsection