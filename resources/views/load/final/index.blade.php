@extends('layout.app')
@section('title', 'طلبات التحميل')
@section('sub-title', 'الرئيسية')
@section('content')
<style>
  /* Custom modal styles */
  .custom-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 99999;
    display: none;
  }
  
  .custom-modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    z-index: 100000;
    width: 500px;
    max-width: 90%;
    display: none;
  }
  
  .custom-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
  }
  
  .custom-modal-title {
    font-size: 18px;
    font-weight: bold;
  }
  
  .custom-modal-close {
    cursor: pointer;
    font-size: 20px;
  }
  
  .custom-modal-body {
    margin-bottom: 15px;
  }
  
  .custom-modal-footer {
    text-align: right;
    border-top: 1px solid #ddd;
    padding-top: 10px;
  }
  /* Add these styles to fix the modal z-index issue */
  .modal {
    z-index: 1050 !important;
  }
  
  .modal-backdrop {
    z-index: 1040 !important;
  }
  
  /* Make sure the modal appears on top of everything */
  #bootstrapAcceptModal, #bootstrapRefuseModal {
    z-index: 1060 !important;
  }
  
  /* Ensure the modal content is visible */
  .modal-content {
    position: relative;
    z-index: 1070 !important;
  }
  
  /* Fix for any other elements that might be causing issues */
  .wrapper, .content-wrapper, .main-footer {
    z-index: auto !important;
  }
  
  /* Ensure the modal dialog is positioned correctly */
  .modal-dialog {
    margin: 30px auto;
    z-index: 1080 !important;
  }
</style>

<!-- Bootstrap Accept Modal -->
<div class="modal fade" id="bootstrapAcceptModal" tabindex="-1" role="dialog" aria-labelledby="acceptModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="acceptModalLabel">قبول الأصناف المحددة</h4>
      </div>
      <div class="modal-body">
        <h4 class="text-danger">انت الان على وشك قبول الأصناف المحددة..</h4>
        <div class="selected-items-list">
          <h5>الأصناف المحددة:</h5>
          <ul id="bootstrapSelectedItemsList"></ul>
        </div>
        <form action="{{route('loads.pending.accept')}}" method="post" id="bootstrapBulkAcceptForm">
          {{ csrf_field() }}
          <input type="hidden" name="id" id="bootstrapBulkAcceptIds">
          <div class="form-group">
            <label for="bootstrapAcceptNotes">ملاحظات</label>
            <input type="text" id="bootstrapAcceptNotes" name="notes" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-success">قبول</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Refuse Modal -->
<div class="modal fade" id="bootstrapRefuseModal" tabindex="-1" role="dialog" aria-labelledby="refuseModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="refuseModalLabel">رفض الأصناف المحددة</h4>
      </div>
      <div class="modal-body">
        <h4 class="text-danger">انت الان على وشك رفض الأصناف المحددة..</h4>
        <div class="selected-items-list">
          <h5>الأصناف المحددة:</h5>
          <ul id="bootstrapSelectedItemsRefuseList"></ul>
        </div>
        <form action="{{route('loads.pending.refuse')}}" method="post" id="bootstrapBulkRefuseForm">
          {{ csrf_field() }}
          <input type="hidden" name="id" id="bootstrapBulkRefuseIds">
          <div class="form-group">
            <label for="bootstrapRefuseNotes">ملاحظات</label>
            <input type="text" id="bootstrapRefuseNotes" name="notes" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-danger">رفض</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="customModalOverlay" class="custom-modal-overlay"></div>
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body row">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#loads" data-toggle="tab" aria-expanded="false">طلبات التحميل بين المخازن</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="loads">
                <!-- Bulk Accept Modal -->
                <div class="modal fade" id="bulkAcceptModal" tabindex="-1" role="dialog" aria-labelledby="bulkAcceptModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="bulkAcceptModalLabel">قبول الأصناف المحددة</h4>
                      </div>
                      <div class="modal-body">
                        <div class="selected-items-list"></div>
                        <form action="{{ route('loads.pending.accept') }}" method="post">
                          {{ csrf_field() }}
                          <input type="hidden" name="id" id="bulkAcceptIds">
                          <div class="form-group">
                            <label for="bulkAcceptNotes">ملاحظات</label>
                            <textarea name="notes" id="bulkAcceptNotes" class="form-control" rows="3"></textarea>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-success">قبول</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Bulk Reject Modal -->
                <div class="modal fade" id="bulkRejectModal" tabindex="-1" role="dialog" aria-labelledby="bulkRejectModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="bulkRejectModalLabel">رفض الأصناف المحددة</h4>
                      </div>
                      <div class="modal-body">
                        <div class="selected-items-list"></div>
                        <form action="{{ route('loads.pending.refuse') }}" method="post">
                          {{ csrf_field() }}
                          <input type="hidden" name="id" id="bulkRejectIds">
                          <div class="form-group">
                            <label for="bulkRejectNotes">ملاحظات</label>
                            <textarea name="notes" id="bulkRejectNotes" class="form-control" rows="3"></textarea>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-danger">رفض</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr class="bg-primary">
                        <td>الكود</td>
                        <td>المستخدم</td>
                        <td>تحويل من</td>
                        <td>تحويل الى</td>
                        <td>التاريخ</td>
                        <td>الملاحظات</td>
                        <td>عمليات</td>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($loads as $load)
                      <tr>
                        <td>{{$load->id}}</td>
                        <td>{{optional($load->user)->name}}</td>
                        <td>{{optional($load->from)->name}}</td>
                        <td>{{optional($load->to)->name}}</td>
                        <td>{{$load->date}}</td>
                        <td>{{$load->notes}}</td>
                        <td>
                          <button class="btn btn-success" data-toggle="modal" data-target="#showLoadItemsModal{{$load->id}}">
                            <i class="fa fa-bars"></i>
                          </button>

                          <div class="modal fade momaher" id="showLoadItemsModal{{$load->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                    &times;
                                  </button>
                                  <h4 class="modal-title" id="firstModalLabel">عمليات على الاصناف</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <div class="table-responsive">
                                        <table class="table table-bordered">
                                          <thead>
                                            <tr>
                                              <th>
                                                <input type="checkbox" id="selectAll{{$load->id}}" class="icheckbox_square-blue">
                                                <label for="selectAll{{$load->id}}">تحديد الكل</label>
                                              </th>
                                              <td>الصنف</td>
                                              <td>الكمية</td>
                                              <td>الحالة</td>
                                              <td>العمليات</td>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            @foreach($load->loadDetails as $detail)
                                            <tr>
                                              <td>
                                                <input type="checkbox" class="item-checkbox-{{$load->id}}" id="item-{{$detail->id}}">
                                              </td>
                                              <td>{{optional($detail->item)->name}}</td>
                                              <td>{{$detail->quantity}}</td>
                                              <td>
                                                @if($detail->status == 'accepted')
                                                  <span class="text-success">تم القبول <i class="fa fa-check" aria-hidden="true"></i></span>
                                                @endif

                                                @if($detail->status == 'pending')
                                                  <span class="text-warning">فى الانتظار <i class="fa fa-clock-o" aria-hidden="true"></i></span>
                                                @endif

                                                @if($detail->status == 'refused')
                                                  <span class="text-danger">تم الرفض <i class="fa fa-ban" aria-hidden="true"></i></span>
                                                @endif
                                              </td>
                                              <td>
                                              </td>
                                            </tr>
                                            @endforeach
                                          </tbody>
                                        </table>

                                        <script>
                                          document.addEventListener('DOMContentLoaded', function() {
                                            // Get the select all checkbox
                                            var selectAll = document.getElementById('selectAll{{$load->id}}');
                                            
                                            // Get all item checkboxes
                                            var itemCheckboxes = document.querySelectorAll('.item-checkbox-{{$load->id}}');
                                            
                                            // Add event listener to select all checkbox
                                            selectAll.addEventListener('click', function() {
                                              // Check if select all is checked
                                              var isChecked = selectAll.checked;
                                              
                                              // Set all item checkboxes to the same state
                                              itemCheckboxes.forEach(function(checkbox) {
                                                checkbox.checked = isChecked;
                                              });
                                            });
                                            
                                            // Add event listeners to all item checkboxes
                                            itemCheckboxes.forEach(function(checkbox) {
                                              checkbox.addEventListener('click', function() {
                                                // Check if all item checkboxes are checked
                                                var allChecked = true;
                                                itemCheckboxes.forEach(function(cb) {
                                                  if (!cb.checked) {
                                                    allChecked = false;
                                                  }
                                                });
                                                
                                                // Update select all checkbox
                                                selectAll.checked = allChecked;
                                              });
                                            });
                                          });
                                        </script>

                                        <div class="row mt-3">
                                          <div class="col-md-12">
                                            <button type="button" class="btn btn-success bulk-accept-btn" data-load-id="{{$load->id}}">
                                              <i class="fa fa-check"></i> قبول المحدد
                                            </button>
                                            <button type="button" class="btn btn-danger bulk-refuse-btn" data-load-id="{{$load->id}}">
                                              <i class="fa fa-ban"></i> رفض المحدد
                                            </button>
                                          </div>
                                        </div>

                                        <script>
                                          $(document).ready(function() {
                                            // Handle bulk accept button
                                            $('#bulkAcceptBtn{{$load->id}}').on('click', function() {
                                              var selectedIds = [];
                                              $('.item-checkbox-{{$load->id}}:checked').each(function() {
                                                selectedIds.push($(this).attr('id').replace('item-', ''));
                                              });
                                              
                                              if (selectedIds.length > 0) {
                                                $('#bulkAcceptIds').val(selectedIds.join(','));
                                                $('#bulkAcceptModal').modal('show');
                                              } else {
                                                alert('الرجاء تحديد عنصر واحد على الأقل');
                                              }
                                            });

                                            // Handle bulk refuse button
                                            $('#bulkRefuseBtn{{$load->id}}').on('click', function() {
                                              var selectedIds = [];
                                              $('.item-checkbox-{{$load->id}}:checked').each(function() {
                                                selectedIds.push($(this).attr('id').replace('item-', ''));
                                              });
                                              
                                              if (selectedIds.length > 0) {
                                                $('#bulkRefuseIds').val(selectedIds.join(','));
                                                $('#bulkRefuseModal').modal('show');
                                              } else {
                                                alert('الرجاء تحديد عنصر واحد على الأقل');
                                              }
                                            });
                                          });
                                        </script>

                                        @if($load->receiveEmployees->isEmpty() && !auth()->user()->hasRole('admin'))
                                          <form action="{{route('loads.pending.setEmployees')}}" method="post">
                                            <input type="text" name="id" value="{{$load->id}}" hidden>
                                            
                                            <div class="row">
                                              <div class="col-md-8">
                                                <div class="form-inline">
                                                  <label>اختيار الموظفين القائمين على التحميل</label>
                                                  <select name="employee_id[]" class="form-control mr-2" multiple required>
                                                    @foreach(\App\Models\Employee::where('branch_id', Auth::user()->branch_id)->get() as $employee)
                                                      <option value="{{$employee->id}}">{{$employee->name}}</option>
                                                    @endforeach
                                                  </select>
                                                  <button type="submit" class="btn btn-primary">تأكيد</button>
                                                </div>
                                              </div>
                                            </div>
                                          </form>
                                        @endif
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="7" class="text-danger text-center">لم يتم العثور على بيانات</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                  {{$loads->links()}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  $('#acceptModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var name = button.data('name')
    var modal = $(this)
    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #name').val(name);
  });

  $('#refuseModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var name = button.data('name')
    var modal = $(this)
    modal.find('.modal-body #id').val(id);
    modal.find('.modal-body #name').val(name);
  });

  $('#acceptForm').submit(function() {
    $('#acceptButton').prop('disabled', true);
  });
  
  $('#refuseForm').submit(function() {
    $('#refuseButton').prop('disabled', true);
  });
</script>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    // Bulk Accept button click handler
    $('.bulk-accept-btn').on('click', function() {
      var model = document.querySelector('.momaher')
      model.style.display='none';
      model.classList.remove('show');
      var loadId = $(this).data('load-id');
      var selectedIds = [];
      var selectedNames = [];
      
      // Get all checked checkboxes
      $('.item-checkbox-' + loadId + ':checked').each(function() {
        var id = $(this).attr('id').replace('item-', '');
        var name = $(this).closest('tr').find('td:nth-child(2)').text().trim();
        selectedIds.push(id);
        selectedNames.push(name);
      });
      
      if (selectedIds.length === 0) {
        alert('الرجاء تحديد عنصر واحد على الأقل');
        return;
      }
      
      // Set the ID in the hidden input
      $('#bulkAcceptIds').val(selectedIds.join(','));
      
      // Update modal title and content to show selected items
      var modalTitle = 'قبول ' + selectedIds.length + ' صنف';
      var modalContent = '<p>أنت على وشك قبول الأصناف التالية:</p><ul>';
      
      selectedNames.forEach(function(name) {
        modalContent += '<li>' + name + '</li>';
      });
      
      modalContent += '</ul>';
      
      $('#bulkAcceptModal').find('.modal-title').text(modalTitle);
      $('.selected-items-list').html(modalContent);
      
      // Show the modal
      $('#bulkAcceptModal').modal('show');
    });
    
    // Bulk Reject button click handler
    $('.bulk-refuse-btn').on('click', function() {
      var model = document.querySelector('.momaher')
      model.style.display='none';
      model.classList.remove('show');
      var loadId = $(this).data('load-id');
      var selectedIds = [];
      var selectedNames = [];
      
      // Get all checked checkboxes
      $('.item-checkbox-' + loadId + ':checked').each(function() {
        var id = $(this).attr('id').replace('item-', '');
        var name = $(this).closest('tr').find('td:nth-child(2)').text().trim();
        selectedIds.push(id);
        selectedNames.push(name);
      });
      
      if (selectedIds.length === 0) {
        alert('الرجاء تحديد عنصر واحد على الأقل');
        return;
      }
      
      // Set the ID in the hidden input
      $('#bulkRejectIds').val(selectedIds.join(','));
      
      // Update modal title and content to show selected items
      var modalTitle = 'رفض ' + selectedIds.length + ' صنف';
      var modalContent = '<p>أنت على وشك رفض الأصناف التالية:</p><ul>';
      
      selectedNames.forEach(function(name) {
        modalContent += '<li>' + name + '</li>';
      });
      
      modalContent += '</ul>';
      
      $('#bulkRejectModal').find('.modal-title').text(modalTitle);
      $('.selected-items-list').html(modalContent);
      
      // Show the modal
      $('#bulkRejectModal').modal('show');
    });
  });
</script>
@endpush
