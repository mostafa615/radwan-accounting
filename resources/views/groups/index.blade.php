@extends('layout.app')
@section('title','المجموعات')
@section('sub-title','الرئسية')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">المجموعات</h3>
        <div class="box-btn">
            <a class="btn btn-success  btn-sm btn-flat" href="{{route('group.create')}}">اضافة </a>   
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
  function sendStatus1(groupId) {
    var selectedOption = groupId;
    var select = document.getElementById("option1_" + groupId);
    var xhr = new XMLHttpRequest();
    if (select.checked) {
      xhr.open("GET", "group/is-edit-permission_s?groupId=" + selectedOption + "&status=1", true);
    } else {
      xhr.open("GET", "group/is-edit-permission_s?groupId=" + selectedOption + "&status=0", true);
    }

    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          console.log("Status sent successfully");
          // Handle response
          var response = JSON.parse(xhr.responseText);
          $("#datatable").DataTable().ajax.reload();
        } else {
          console.error("Failed to send status");
        }
      }
    };
    xhr.send();
  }

  function sendStatus2(groupId) {
    var selectedOption = groupId;
    var select = document.getElementById("option2_" + groupId);
    var xhr = new XMLHttpRequest();
    if (select.checked) {
      xhr.open("GET", "group/is-edit-permission_q?groupId=" + selectedOption + "&status=1", true);
    } else {
      xhr.open("GET", "group/is-edit-permission_q?groupId=" + selectedOption + "&status=0", true);
    }

    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          console.log("Status sent successfully");
          // Handle response
          var response = JSON.parse(xhr.responseText);
          $("#datatable").DataTable().ajax.reload();
        } else {
          console.error("Failed to send status");
        }
      }
    };
    xhr.send();
  }

  function sendStatus3(groupId) {
    var selectedOption = groupId;
    var select = document.getElementById("option3_" + groupId);
    var xhr = new XMLHttpRequest();
    if (select.checked) {
      xhr.open("GET", "group/is-edit-permission_o?groupId=" + selectedOption + "&status=1", true);
    } else {
      xhr.open("GET", "group/is-edit-permission_o?groupId=" + selectedOption + "&status=0", true);
    }

    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          console.log("Status sent successfully");
          // Handle response
          var response = JSON.parse(xhr.responseText);
          $("#datatable").DataTable().ajax.reload();
        } else {
          console.error("Failed to send status");
        }
      }
    };
    xhr.send();
  }
</script>
@endsection