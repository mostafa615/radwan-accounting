@extends('layout.app')
@section('title','طلبات الفواتير')
@section('sub-title','الرئيسية')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body row">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#order-in" data-toggle="tab" aria-expanded="false"> فواتير بيع الي عميل</a></li>
              <li><a href="#order-in-return" data-toggle="tab" aria-expanded="false">فواتير مرتجع من عميل</a></li>
              <li><a href="#order-out" data-toggle="tab" aria-expanded="false">فواتير شراء من مورد</a></li>
              <li><a href="#order-out-return" data-toggle="tab" aria-expanded="false">فواتير مرتجع الي مورد</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="order-in">
                <div class="table-responsive">
                  <table class="table table-bordered" width="100%">
                    <thead>
                      <tr class="bg-primary">
                        <td>رقم الفاتورة</td>
                        <td>العميل</td>
                        <td>هاتف العميل</td>
                        <td>الفرع</td>
                        <td>عرض التفاصيل</td>
                        <td>الاجمالي</td>
                        <td>الخصم</td>
                        <td>المدفوع</td>
                        <td>المتبقي</td>
                        <td>ملاحظات</td>
                        <td>العمليات</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $ordersIn = App\Models\Order::whereIn('id', $ordersInIds)->paginate(10) ?>
                      @forelse($ordersIn as $resource)
                      <tr>
                        <td>{{$resource->id or '-'}}</td>
                        <td>{{$resource->ownerable->name or '-'}}</td>
                        <td>{{$resource->ownerable->phone_1 or '-'}}</td>
                        <td>{{$resource->branch->name or '-'}}</td>
                        <td>
                          <button type="button" class="btn btn-info" data-toggle="modal"
                            data-target="#invoice1_{{$resource->id}}">
                            <i class="fa fa-television"></i>
                          </button>

                          <!-- Modal -->
                          <div id="invoice1_{{$resource->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                    &times;
                                  </button>
                                  <h4 class="modal-title">الفاتورة</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="box">
                                    <div class="box-header">
                                      <table class="table table-responsive table-bordered">
                                        <tr>
                                          <td>رقم الفاتورة</td>
                                          <td>{{$resource->id}}</td>
                                          <td>التاريخ</td>
                                          <td>{{$resource->date}}</td>
                                        </tr>
                                        <tr>
                                          <td>العميل</td>
                                          <td>{{$resource->ownerable->name or '-'}}</td>
                                          <td>الموظف</td>
                                          <td>{{$resource->user->name or '-'}}</td>
                                        </tr>
                                        <tr>
                                          <td>الخزنة</td>
                                          <td>{{$resource->reposite->name or '-'}}</td>
                                          <td></td>
                                          <td></td>
                                        </tr>
                                        <tr>
                                          <td>المدفوع</td>
                                          <td>{{$resource->cost or '-'}}</td>
                                          <td>المتبقي</td>
                                          <td>{{$resource->total - $resource->cost -$resource->discount}}</td>
                                        </tr>
                                        <tr>
                                          <td>النقل</td>
                                          <td>{{round($resource->driving_cost, 2)}}</td>
                                          <td>السائق</td>
                                          <td>{{optional($resource->driver)->name }}</td>
                                        </tr>
                                        <tr>
                                          <td>الخصم</td>
                                          <td>{{$resource->discount or '-'}}</td>
                                          <td> الإجمالي</td>
                                          <td>{{$resource->total or '-'}}</td>
                                        </tr>
                                      </table>
                                    </div>
                                  </div>

                                  <div class="box-body">
                                    <table class="table table-responsive table-bordered">
                                      <tr>
                                        <td>الصنف</td>
                                        <td>المخزن</td>
                                        <td>الكمية</td>
                                        <td>السعر</td>
                                        <td>الخصم</td>
                                      </tr>
                                      @if(!empty($resource->orderDetails))
                                      @foreach($resource->orderDetails as $order_detail)
                                      <tr>
                                        <td>{{$order_detail->item->name or '-'}}</td>
                                        <td>{{$order_detail->store->name or '-'}}</td>
                                        <td>{{$order_detail->quantity}}</td>
                                        <td>{{$order_detail->unite_price}}</td>
                                        <td>{{$order_detail->discount}}</td>
                                      </tr>
                                      @endforeach
                                      @endif
                                    </table>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td>{{$resource->total}}</td>
                        <td>{{$resource->discount}}</td>
                        <td>{{$resource->cost}}</td>
                        <td>{{$resource->total - $resource->cost -$resource->discount}}</td>
                        <td>{{$resource->notes}}</td>
                        <td>
                          <button class="btn btn-success" data-toggle="modal"
                            data-target="#showOrderItemsModal1{{$resource->id}}">
                            <i class="fa fa-bars"></i>
                          </button>

                          <div class="modal fade" id="showOrderItemsModal1{{$resource->id}}" tabindex="-1" role="dialog"
                            aria-hidden="true">
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
                                              <td>
                                                <input type="checkbox" id="selectAll{{$resource->id}}">
                                              </td>
                                              <td>الصنف</td>
                                              <td>المخزن</td>
                                              <td>الكمية</td>
                                              <td>السعر</td>
                                              <td>الخصم</td>
                                              <td>الحالة</td>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            @foreach($resource->orderDetails as $detail)
                                            <tr>
                                              <td>
                                                <input type="checkbox" class="item-checkbox item-checkbox-{{$resource->id}}" 
                                                  data-id="{{ $detail->id }}" id="item-{{$detail->id}}">
                                              </td>
                                              <td>{{$detail->item->name or '-'}}</td>
                                              <td>{{$detail->store->name or '-'}}</td>
                                              <td>{{$detail->quantity}}</td>
                                              <td>{{$detail->unite_price}}</td>
                                              <td>{{$detail->discount}}</td>
                                              <td style="white-space: nowrap!important;">
                                                @if($detail->status == 'accepted')
                                                <span class="text-success">تم القبول <i class="fa fa-check"
                                                    aria-hidden="true"></i></span>
                                                @endif
                                                @if($detail->status == 'pending')
                                                <span class="text-warning">فى الانتظار <i class="fa fa-clock-o"
                                                    aria-hidden="true"></i></span>
                                                @endif
                                                @if($detail->status == 'refused')
                                                <span class="text-danger">تم الرفض <i class="fa fa-ban"
                                                    aria-hidden="true"></i></span>
                                                @endif
                                              </td>
                                            </tr>
                                            @endforeach
                                          </tbody>
                                        </table>

                                        <!-- Bulk Action Buttons -->
                                        <div class="row" style="margin-top: 15px;">
                                          <div class="col-md-12">
                                            <button type="button" class="btn btn-success" id="bulkAcceptBtn{{$resource->id}}">
                                              <i class="fa fa-check"></i> قبول المحدد
                                            </button>
                                            <button type="button" class="btn btn-danger" id="bulkRejectBtn{{$resource->id}}">
                                              <i class="fa fa-ban"></i> رفض المحدد
                                            </button>
                                          </div>
                                        </div>

                                        <!-- Bulk Accept Modal -->
                                        <div class="modal fade" id="bulkAcceptModal{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="bulkAcceptModalLabel{{$resource->id}}" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="bulkAcceptModalLabel{{$resource->id}}">قبول الأصناف المحددة</h4>
                                              </div>
                                              <div class="modal-body">
                                                <div class="selected-items-list{{$resource->id}}"></div>
                                                <form action="{{ route('orders.accept') }}" method="post">
                                                  {{ csrf_field() }}
                                                  <input type="hidden" name="id" id="bulkAcceptIds{{$resource->id}}"><div id="employeeInputsAccept{{$resource->id}}"></div>
                                                  <div class="form-group">
                                                    <label for="bulkAcceptNotes{{$resource->id}}">ملاحظات</label>
                                                    <textarea name="notes" id="bulkAcceptNotes{{$resource->id}}" class="form-control" rows="3"></textarea>
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
                                        <div class="modal fade" id="bulkRejectModal{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="bulkRejectModalLabel{{$resource->id}}" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="bulkRejectModalLabel{{$resource->id}}">رفض الأصناف المحددة</h4>
                                              </div>
                                              <div class="modal-body">
                                                <div class="selected-items-list{{$resource->id}}"></div>
                                                <form action="{{ route('orders.refuse') }}" method="post">
                                                  {{ csrf_field() }}
                                                  <input type="hidden" name="id" id="bulkRejectIds{{$resource->id}}"><div id="employeeInputsReject{{$resource->id}}"></div>
                                                  <div class="form-group">
                                                    <label for="bulkRejectNotes{{$resource->id}}">ملاحظات</label>
                                                    <textarea name="notes" id="bulkRejectNotes{{$resource->id}}" class="form-control" rows="3"></textarea>
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

                                        <script>
                                          document.addEventListener('DOMContentLoaded', function() {
                                            // Get the select all checkbox
                                            var selectAll = document.getElementById('selectAll{{$resource->id}}');
                                            
                                            // Get all item checkboxes
                                            var itemCheckboxes = document.querySelectorAll('.item-checkbox-{{$resource->id}}');
                                            
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
                                            
                                            // Bulk Accept button click handler
                                            $('#bulkAcceptBtn{{$resource->id}}').on('click', function() {
                                              var selectedIds = [];
                                              var selectedNames = [];
                                              
                                              // Get all checked checkboxes
                                              itemCheckboxes.forEach(function(checkbox) {
                                                if (checkbox.checked) {
                                                  var id = checkbox.getAttribute('data-id');
                                                  var name = $(checkbox).closest('tr').find('td:nth-child(2)').text().trim();
                                                  selectedIds.push(id);
                                                  selectedNames.push(name);
                                                }
                                              });
                                              
                                              if (selectedIds.length === 0) {
                                                alert('الرجاء تحديد عنصر واحد على الأقل');
                                                return;
                                              }
                                              
                                              // Set the ID in the hidden input
                                              $('#bulkAcceptIds{{$resource->id}}').val(selectedIds.join(','));
                                              
                                              // Update modal title and content to show selected items
                                              var modalTitle = 'قبول ' + selectedIds.length + ' صنف';
                                              var modalContent = '<p>أنت على وشك قبول الأصناف التالية:</p><ul>';
                                              
                                              selectedNames.forEach(function(name) {
                                                modalContent += '<li>' + name + '</li>';
                                              });
                                              
                                              modalContent += '</ul>';
                                              
                                              $('#bulkAcceptModal{{$resource->id}}').find('.modal-title').text(modalTitle);
                                              $('.selected-items-list{{$resource->id}}').html(modalContent);

                                              // Transfer selected employees
                                              var selectedEmployees = $('#employeeSelect{{$resource->id}}').val();
                                              var employeeInputs = '';
                                              if (selectedEmployees) {
                                                selectedEmployees.forEach(function(empId) {
                                                  employeeInputs += '<input type="hidden" name="employee_id[]" value="' + empId + '">';
                                                });
                                              }
                                              $('#employeeInputsAccept{{$resource->id}}').html(employeeInputs);
                                              
                                              // Show the modal
                                              $('#bulkAcceptModal{{$resource->id}}').modal('show');
                                            });
                                            
                                            // Bulk Reject button click handler
                                            $('#bulkRejectBtn{{$resource->id}}').on('click', function() {
                                              var selectedIds = [];
                                              var selectedNames = [];
                                              
                                              // Get all checked checkboxes
                                              itemCheckboxes.forEach(function(checkbox) {
                                                if (checkbox.checked) {
                                                  var id = checkbox.getAttribute('data-id');
                                                  var name = $(checkbox).closest('tr').find('td:nth-child(2)').text().trim();
                                                  selectedIds.push(id);
                                                  selectedNames.push(name);
                                                }
                                              });
                                              
                                              if (selectedIds.length === 0) {
                                                alert('الرجاء تحديد عنصر واحد على الأقل');
                                                return;
                                              }
                                              
                                              // Set the ID in the hidden input
                                              $('#bulkRejectIds{{$resource->id}}').val(selectedIds.join(','));
                                              
                                              // Update modal title and content to show selected items
                                              var modalTitle = 'رفض ' + selectedIds.length + ' صنف';
                                              var modalContent = '<p>أنت على وشك رفض الأصناف التالية:</p><ul>';
                                              
                                              selectedNames.forEach(function(name) {
                                                modalContent += '<li>' + name + '</li>';
                                              });
                                              
                                              modalContent += '</ul>';
                                              
                                              $('#bulkRejectModal{{$resource->id}}').find('.modal-title').text(modalTitle);
                                              $('.selected-items-list{{$resource->id}}').html(modalContent);

                                              // Transfer selected employees
                                              var selectedEmployees = $('#employeeSelect{{$resource->id}}').val();
                                              var employeeInputs = '';
                                              if (selectedEmployees) {
                                                selectedEmployees.forEach(function(empId) {
                                                  employeeInputs += '<input type="hidden" name="employee_id[]" value="' + empId + '">';
                                                });
                                              }
                                              $('#employeeInputsReject{{$resource->id}}').html(employeeInputs);
                                              
                                              // Show the modal
                                              $('#bulkRejectModal{{$resource->id}}').modal('show');
                                            });
                                          });
                                        </script>

                                        <div class="row">
                                            <div class="col-md-12">
                                              <div class="form-group">
                                                <label>الموظفين</label>
                                                <select id="employeeSelect{{$resource->id}}" class="form-control" multiple>
                                                  @foreach(\App\Models\Employee::where('job_id', 2)->where('branch_id', Auth::user()->branch_id)->get() as $employee)
                                                  <option value="{{$employee->id}}">{{$employee->name}}</option>
                                                  @endforeach
                                                </select>
                                              </div>
                                            </div>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <a href="{{url('orders_in_store/'.$resource->id)}}" target="_blank" class="btn btn-success" title="إذن صرف من المخزن"><i class="fa fa-print"></i></a>
                          <a href="{{url('orders_in_license/'.$resource->id)}}" target="_blank" class="btn btn-instagram" title="إذن الصرف"><i class="fa fa-print"></i></a>
                          <a href="{{url('orders-in/'.$resource->id)}}" target="_blank" class="btn btn-info" title="فاتورة"><i class="fa fa-print"></i></a>
                        </td>
                      </tr>
                      @empty
                      <tr>
                        <td class="text-danger text-center" colspan="10">لم يتم العثور على بيانات</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                  {{$ordersIn->links()}}
                </div>
              </div>

              <!-- For order-in-return tab -->
              <div class="tab-pane fade" id="order-in-return">
                <div class="table-responsive">
                  <table class="table table-bordered" width="100%">
                    <thead>
                      <tr class="bg-primary">
                        <td>رقم الفاتورة</td>
                        <td>العميل</td>
                        <td>هاتف العميل</td>
                        <td>الفرع</td>
                        <td>عرض التفاصيل</td>
                        <td>الاجمالي</td>
                        <td>الخصم</td>
                        <td>المدفوع</td>
                        <td>المتبقي</td>
                        <td>ملاحظات</td>
                        <td>العمليات</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $ordersReturnIn = App\Models\Order::whereIn('id', $ordersReturnInIds)->paginate(10) ?>
                      @forelse($ordersReturnIn as $resource)
                      <tr>
                        <td>{{$resource->id or '-'}}</td>
                        <td>{{$resource->ownerable->name or '-'}}</td>
                        <td>{{$resource->ownerable->phone_1 or '-'}}</td>
                        <td>{{$resource->branch->name or '-'}}</td>
                        <td>
                          <button type="button" class="btn btn-info" data-toggle="modal"
                            data-target="#invoice2_{{$resource->id}}">
                            <i class="fa fa-television"></i>
                          </button>

                          <!-- Modal -->
                          <div id="invoice2_{{$resource->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                    &times;
                                  </button>
                                  <h4 class="modal-title">الفاتورة</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="box">
                                    <div class="box-header">
                                      <table class="table table-responsive table-bordered">
                                        <tr>
                                          <td>رقم الفاتورة</td>
                                          <td>{{$resource->id}}</td>
                                          <td>التاريخ</td>
                                          <td>{{$resource->date}}</td>
                                        </tr>
                                        <tr>
                                          <td>العميل</td>
                                          <td>{{$resource->ownerable->name or '-'}}</td>
                                          <td>الموظف</td>
                                          <td>{{$resource->user->name or '-'}}</td>
                                        </tr>
                                        <tr>
                                          <td>الخزنة</td>
                                          <td>{{$resource->reposite->name or '-'}}</td>
                                          <td></td>
                                          <td></td>
                                        </tr>
                                        <tr>
                                          <td>المدفوع</td>
                                          <td>{{$resource->cost or '-'}}</td>
                                          <td>المتبقي</td>
                                          <td>{{$resource->total - $resource->cost -$resource->discount}}</td>
                                        </tr>
                                        <tr>
                                          <td>النقل</td>
                                          <td>{{round($resource->driving_cost, 2)}}</td>
                                          <td>السائق</td>
                                          <td>{{optional($resource->driver)->name }}</td>
                                        </tr>
                                        <tr>
                                          <td>الخصم</td>
                                          <td>{{$resource->discount or '-'}}</td>
                                          <td> الإجمالي</td>
                                          <td>{{$resource->total or '-'}}</td>
                                        </tr>
                                      </table>
                                    </div>
                                    <div class="box-body">
                                      <table class="table table-responsive table-bordered">
                                        <tr>
                                          <td>الصنف</td>
                                          <td>المخزن</td>
                                          <td>الكمية</td>
                                          <td>السعر</td>
                                          <td>الخصم</td>
                                        </tr>

                                        @if(!empty($resource->orderDetails))
                                        @foreach($resource->orderDetails as $order_detail)
                                        <tr>
                                          <td>{{$order_detail->item->name or '-'}}</td>
                                          <td>{{$order_detail->store->name or '-'}}</td>
                                          <td>{{$order_detail->quantity}}</td>
                                          <td>{{$order_detail->unite_price}}</td>
                                          <td>{{$order_detail->discount}}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                      </table>
                                    </div>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td>{{$resource->total}}</td>
                        <td>{{$resource->discount}}</td>
                        <td>{{$resource->cost}}</td>
                        <td>{{$resource->total - $resource->cost -$resource->discount}}</td>
                        <td>{{$resource->notes}}</td>
                        <td>
                          <button class="btn btn-success" data-toggle="modal"
                            data-target="#showOrderItemsModal2{{$resource->id}}">
                            <i class="fa fa-bars"></i>
                          </button>

                          <div class="modal fade" id="showOrderItemsModal2{{$resource->id}}" tabindex="-1" role="dialog"
                            aria-hidden="true">
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
                                            <tr style="white-space: nowrap!important;">
                                              <td>
                                                <input type="checkbox" id="selectAll{{$resource->id}}_return_in">
                                              </td>
                                              <td>الصنف</td>
                                              <td>المخزن</td>
                                              <td>الكمية</td>
                                              <td>السعر</td>
                                              <td>الخصم</td>
                                              <td>الحالة</td>
                                              <td>العمليات</td>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            @foreach($resource->orderDetails as $detail)
                                            <tr>
                                              <td>
                                                @if($detail->status == 'pending')
                                                <input type="checkbox" class="item-checkbox item-checkbox-{{$resource->id}}_return_in" 
                                                  data-id="{{ $detail->id }}" id="item-{{$detail->id}}">
                                                @endif
                                              </td>
                                              <td>{{$detail->item->name or '-'}}</td>
                                              <td>{{$detail->store->name or '-'}}</td>
                                              <td>{{$detail->quantity}}</td>
                                              <td>{{$detail->unite_price}}</td>
                                              <td>{{$detail->discount}}</td>
                                              <td style="white-space: nowrap!important;">
                                                @if($detail->status == 'accepted')
                                                <span class="text-success">تم القبول <i class="fa fa-check"
                                                    aria-hidden="true"></i></span>
                                                @endif
                                                @if($detail->status == 'pending')
                                                <span class="text-warning">فى الانتظار <i class="fa fa-clock-o"
                                                    aria-hidden="true"></i></span>
                                                @endif
                                                @if($detail->status == 'refused')
                                                <span class="text-danger">تم الرفض <i class="fa fa-ban"
                                                    aria-hidden="true"></i></span>
                                                @endif
                                              </td>
                                              <td style="white-space: nowrap!important;">
                                                @if($detail->status == 'pending')
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                  data-target="#acceptModal" data-id="{{ $detail->id }}"
                                                  data-name="{{ $detail->item->name }}">
                                                  <i class="fa fa-check"></i>
                                                </button>

                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                  data-target="#refuseModal" data-id="{{ $detail->id }}"
                                                  data-name="{{ $detail->item->name }}">
                                                  <i class="fa fa-ban"></i>
                                                </button>
                                                @endif
                                              </td>
                                            </tr>
                                            @endforeach
                                          </tbody>
                                        </table>

                                        <!-- Bulk Action Buttons -->
                                        <div class="row" style="margin-top: 15px;">
                                          <div class="col-md-12">
                                            <button type="button" class="btn btn-success" id="bulkAcceptBtn{{$resource->id}}_return_in">
                                              <i class="fa fa-check"></i> قبول المحدد
                                            </button>
                                            <button type="button" class="btn btn-danger" id="bulkRejectBtn{{$resource->id}}_return_in">
                                              <i class="fa fa-ban"></i> رفض المحدد
                                            </button>
                                          </div>
                                        </div>

                                        <!-- Bulk Accept Modal -->
                                        <div class="modal fade" id="bulkAcceptModal{{$resource->id}}_return_in" tabindex="-1" role="dialog" aria-labelledby="bulkAcceptModalLabel{{$resource->id}}_return_in" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="bulkAcceptModalLabel{{$resource->id}}_return_in">قبول الأصناف المحددة</h4>
                                              </div>
                                              <div class="modal-body">
                                                <div class="selected-items-list{{$resource->id}}_return_in"></div>
                                                <form action="{{ route('orders.accept') }}" method="post">
                                                  {{ csrf_field() }}
                                                  <input type="hidden" name="id" id="bulkAcceptIds{{$resource->id}}_return_in">
                                                  <div class="form-group">
                                                    <label for="bulkAcceptNotes{{$resource->id}}_return_in">ملاحظات</label>
                                                    <textarea name="notes" id="bulkAcceptNotes{{$resource->id}}_return_in" class="form-control" rows="3"></textarea>
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
                                        <div class="modal fade" id="bulkRejectModal{{$resource->id}}_return_in" tabindex="-1" role="dialog" aria-labelledby="bulkRejectModalLabel{{$resource->id}}_return_in" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="bulkRejectModalLabel{{$resource->id}}_return_in">رفض الأصناف المحددة</h4>
                                              </div>
                                              <div class="modal-body">
                                                <div class="selected-items-list{{$resource->id}}_return_in"></div>
                                                <form action="{{ route('orders.refuse') }}" method="post">
                                                  {{ csrf_field() }}
                                                  <input type="hidden" name="id" id="bulkRejectIds{{$resource->id}}_return_in">
                                                  <div class="form-group">
                                                    <label for="bulkRejectNotes{{$resource->id}}_return_in">ملاحظات</label>
                                                    <textarea name="notes" id="bulkRejectNotes{{$resource->id}}_return_in" class="form-control" rows="3"></textarea>
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

                                        <script>
                                          document.addEventListener('DOMContentLoaded', function() {
                                            // Get the select all checkbox
                                            var selectAll = document.getElementById('selectAll{{$resource->id}}_return_in');
                                            
                                            // Get all item checkboxes
                                            var itemCheckboxes = document.querySelectorAll('.item-checkbox-{{$resource->id}}_return_in');
                                            
                                            // Add event listener to select all checkbox
                                            if (selectAll) {
                                              selectAll.addEventListener('click', function() {
                                                // Check if select all is checked
                                                var isChecked = selectAll.checked;
                                                
                                                // Set all item checkboxes to the same state
                                                itemCheckboxes.forEach(function(checkbox) {
                                                  checkbox.checked = isChecked;
                                                });
                                              });
                                            }
                                            
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
                                                if (selectAll) {
                                                  selectAll.checked = allChecked;
                                                }
                                              });
                                            });
                                            
                                            // Hide "Select All" checkbox if there are no pending items
                                            if (itemCheckboxes.length === 0 && selectAll) {
                                              selectAll.style.display = 'none';
                                            }
                                            
                                            // Bulk Accept button click handler
                                            $('#bulkAcceptBtn{{$resource->id}}_return_in').on('click', function() {
                                              var selectedIds = [];
                                              var selectedNames = [];
                                              
                                              // Get all checked checkboxes
                                              itemCheckboxes.forEach(function(checkbox) {
                                                if (checkbox.checked) {
                                                  var id = checkbox.getAttribute('data-id');
                                                  var name = $(checkbox).closest('tr').find('td:nth-child(2)').text().trim();
                                                  selectedIds.push(id);
                                                  selectedNames.push(name);
                                                }
                                              });
                                              
                                              if (selectedIds.length === 0) {
                                                alert('الرجاء تحديد عنصر واحد على الأقل');
                                                return;
                                              }
                                              
                                              // Set the ID in the hidden input
                                              $('#bulkAcceptIds{{$resource->id}}_return_in').val(selectedIds.join(','));
                                              
                                              // Update modal title and content to show selected items
                                              var modalTitle = 'قبول ' + selectedIds.length + ' صنف';
                                              var modalContent = '<p>أنت على وشك قبول الأصناف التالية:</p><ul>';
                                              
                                              selectedNames.forEach(function(name) {
                                                modalContent += '<li>' + name + '</li>';
                                              });
                                              
                                              modalContent += '</ul>';
                                              
                                              $('#bulkAcceptModal{{$resource->id}}_return_in').find('.modal-title').text(modalTitle);
                                              $('.selected-items-list{{$resource->id}}_return_in').html(modalContent);
                                              
                                              // Show the modal
                                              $('#bulkAcceptModal{{$resource->id}}_return_in').modal('show');
                                            });
                                            
                                            // Bulk Reject button click handler
                                            $('#bulkRejectBtn{{$resource->id}}_return_in').on('click', function() {
                                              var selectedIds = [];
                                              var selectedNames = [];
                                              
                                              // Get all checked checkboxes
                                              itemCheckboxes.forEach(function(checkbox) {
                                                if (checkbox.checked) {
                                                  var id = checkbox.getAttribute('data-id');
                                                  var name = $(checkbox).closest('tr').find('td:nth-child(2)').text().trim();
                                                  selectedIds.push(id);
                                                  selectedNames.push(name);
                                                }
                                              });
                                              
                                              if (selectedIds.length === 0) {
                                                alert('الرجاء تحديد عنصر واحد على الأقل');
                                                return;
                                              }
                                              
                                              // Set the ID in the hidden input
                                              $('#bulkRejectIds{{$resource->id}}_return_in').val(selectedIds.join(','));
                                              
                                              // Update modal title and content to show selected items
                                              var modalTitle = 'رفض ' + selectedIds.length + ' صنف';
                                              var modalContent = '<p>أنت على وشك رفض الأصناف التالية:</p><ul>';
                                              
                                              selectedNames.forEach(function(name) {
                                                modalContent += '<li>' + name + '</li>';
                                              });
                                              
                                              modalContent += '</ul>';
                                              
                                              $('#bulkRejectModal{{$resource->id}}_return_in').find('.modal-title').text(modalTitle);
                                              $('.selected-items-list{{$resource->id}}_return_in').html(modalContent);
                                              
                                              // Show the modal
                                              $('#bulkRejectModal{{$resource->id}}_return_in').modal('show');
                                            });
                                            
                                            // Hide bulk action buttons if there are no pending items
                                            if (itemCheckboxes.length === 0) {
                                              $('#bulkAcceptBtn{{$resource->id}}_return_in').hide();
                                              $('#bulkRejectBtn{{$resource->id}}_return_in').hide();
                                            }
                                          });
                                        </script>

                                        @if($resource->employee->isEmpty())
                                        <form action="{{route('orders.setEmployees')}}" method="post">
                                          <input type="text" name="id" value="{{$resource->id}}" hidden>

                                          <div class="row">
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                <label>الموظفين</label>
                                                <select name="employee_id[]" class="form-control" multiple required>
                                                  @foreach(\App\Models\Employee::where('job_id', 2)->where('branch_id', Auth::user()->branch_id)->get() as $employee)
                                                  <option value="{{$employee->id}}">{{$employee->name}}</option>
                                                  @endforeach
                                                </select>
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                <label>ملاحظة</label>
                                                <input type="text" name="notes" class="form-control">
                                              </div>
                                            </div>
                                            <div class="col-md-12">
                                              <button type="submit" class="btn btn-primary">تأكيد</button>
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

                          <a href="{{url('return-orders-in-store-license/'.$resource->id)}}" target="_blank" class="btn btn-success" title="إذن صرف من المخزن"><i class="fa fa-print"></i></a>
                          <a href="{{url('return-orders-in-license/'.$resource->id)}}" class="btn btn-instagram" target="_blank" title="إذن"><i class="fa fa-print"></i></a>
                          <a href="{{url('return-orders-in/'.$resource->id)}}" class="btn btn-info" target="_blank"><i class="fa fa-print"></i></a>
                        </td>
                      </tr>
                      @empty
                      <tr>
                        <td class="text-danger text-center" colspan="10">لم يتم العثور على بيانات</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                  {{$ordersReturnIn->links()}}
                </div>
              </div>

              <!-- Order Out Tab -->
              <div class="tab-pane" id="order-out">
                <div class="table-responsive">
                  <table class="table table-bordered" width="100%">
                    <thead>
                      <tr class="bg-primary">
                        <td>رقم الفاتورة</td>
                        <td>المورد</td>
                        <td>هاتف المورد</td>
                        <td>الفرع</td>
                        <td>عرض التفاصيل</td>
                        <td>الاجمالي</td>
                        <td>الخصم</td>
                        <td>المدفوع</td>
                        <td>المتبقي</td>
                        <td>ملاحظات</td>
                        <td>العمليات</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $ordersOut = App\Models\Order::whereIn('id', $ordersOutIds)->paginate(10) ?>
                      @forelse($ordersOut as $resource)
                      <tr>
                        <td>{{$resource->id or '-'}}</td>
                        <td>{{$resource->ownerable->name or '-'}}</td>
                        <td>{{$resource->ownerable->phone_1 or '-'}}</td>
                        <td>{{$resource->branch->name or '-'}}</td>
                        <td>
                          <button type="button" class="btn btn-info" data-toggle="modal"
                            data-target="#invoice3_{{$resource->id}}">
                            <i class="fa fa-television"></i>
                          </button>

                          <!-- Modal -->
                          <div id="invoice3_{{$resource->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                    &times;
                                  </button>
                                  <h4 class="modal-title">الفاتورة</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="box">
                                    <div class="box-header">
                                      <table class="table table-responsive table-bordered">
                                        <tr>
                                          <td>رقم الفاتورة</td>
                                          <td>{{$resource->id}}</td>
                                          <td>التاريخ</td>
                                          <td>{{$resource->date}}</td>
                                        </tr>
                                        <tr>
                                          <td>المورد</td>
                                          <td>{{$resource->ownerable->name or '-'}}</td>
                                          <td>الموظف</td>
                                          <td>{{$resource->user->name or '-'}}</td>
                                        </tr>
                                        <tr>
                                          <td>الخزنة</td>
                                          <td>{{$resource->reposite->name or '-'}}</td>
                                          <td></td>
                                          <td></td>
                                        </tr>
                                        <tr>
                                          <td>المدفوع</td>
                                          <td>{{$resource->cost or '-'}}</td>
                                          <td>المتبقي</td>
                                          <td>{{$resource->total - $resource->cost -$resource->discount}}</td>
                                        </tr>
                                        <tr>
                                          <td>الخصم</td>
                                          <td>{{$resource->discount or '-'}}</td>
                                          <td> الإجمالي</td>
                                          <td>{{$resource->total or '-'}}</td>
                                        </tr>
                                      </table>
                                    </div>
                                  </div>

                                  <div class="box-body">
                                    <table class="table table-responsive table-bordered">
                                      <tr>
                                        <td>الصنف</td>
                                        <td>المخزن</td>
                                        <td>الكمية</td>
                                        <td>السعر</td>
                                        <td>الخصم</td>
                                      </tr>
                                  <?php
                                                                    $sub_total = 0;
                                                                    $sub_total_quantity = 0;
                                                                    $sub_total_price = 0;
                                                                    $sub_total_discount = 0;
                                                                    ?>
                                  @if(!empty($resource->orderDetails))
                                  @foreach($resource->orderDetails as $order_detail)
                                  <?php
                                                                            $sub_total_quantity = $sub_total_quantity + $order_detail->quantity;
                                                                            $sub_total_price = $sub_total_price + $order_detail->unite_price;
                                                                            $sub_total_discount = $sub_total_discount + $order_detail->discount;
                                                                            $sub_total = $sub_total + (($order_detail->unite_price * $order_detail->quantity) - $order_detail->discount);
                                                                            if($order_detail->is_oper_supplies == 1){
                                                                                $itemName = DB::table('supplies')->where('id', $order_detail->item_id)->first()->name;
                                                                            } else {
                                                                                $itemName = $order_detail->item->name;
                                                                            }
                                                                            ?>
                                  <tr>
                                    <td>{{$itemName or '-'}}</td>
                                    <td>{{$order_detail->store->name or '-'}}</td>
                                    <td>{{$order_detail->quantity}}</td>
                                    <td>{{$order_detail->unite_price}}</td>
                                    <td>{{$order_detail->discount}}</td>
                                    <td>{{($order_detail->unite_price*$order_detail->quantity)-$order_detail->discount}}
                                    </td>
                                    <td>
                                      @if($order_detail->status == 'accepted')
                                      <span class="text-success">مقبول <i class="fa fa-check"
                                          aria-hidden="true"></i></span>
                                      @endif
                                      @if($order_detail->status == 'pending')
                                      <span class="text-warning">فى الانتظار <i class="fa fa-clock-o"
                                          aria-hidden="true"></i></span>
                                      @endif
                                      @if($order_detail->status == 'refused')
                                      <span class="text-danger">مرفوض <i class="fa fa-ban"
                                          aria-hidden="true"></i></span>
                                      @endif
                                    </td>
                                    <td>{{$order_detail->notes or '-'}}</td>
                                  </tr>
                                  @endforeach
                                  @endif
                                    </table>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td>{{$resource->total}}</td>
                        <td>{{$resource->discount}}</td>
                        <td>{{$resource->cost}}</td>
                        <td>{{$resource->total - $resource->cost -$resource->discount}}</td>
                        <td>{{$resource->notes}}</td>
                        <td>
                          <button class="btn btn-success" data-toggle="modal"
                            data-target="#showOrderItemsModal3{{$resource->id}}">
                            <i class="fa fa-bars"></i>
                          </button>

                          <div class="modal fade" id="showOrderItemsModal3{{$resource->id}}" tabindex="-1" role="dialog"
                            aria-hidden="true">
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
                                              <td>
                                                <input type="checkbox" id="selectAll{{$resource->id}}">
                                              </td>
                                              <td>الصنف</td>
                                              <td>المخزن</td>
                                              <td>الكمية</td>
                                              <td>السعر</td>
                                              <td>الخصم</td>
                                              <td>الإجمالي</td>
                                              <td>الحالة</td>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <?php
                                              $sub_total_quantity = 0;
                                              $sub_total_price = 0;
                                              $sub_total_discount = 0;
                                              $sub_total = 0;
                                            ?>
                                            @foreach($resource->orderDetails as $detail)
                                            <?php
                                              $sub_total_quantity = $sub_total_quantity + $detail->quantity;
                                              $sub_total_price = $sub_total_price + $detail->unite_price;
                                              $sub_total_discount = $sub_total_discount + $detail->discount;
                                              $sub_total = $sub_total + (($detail->unite_price * $detail->quantity) - $detail->discount);
                                              if($detail->is_oper_supplies == 1) {
                                                $itemName = DB::table('supplies')->where('id', $detail->item_id)->first()->name;
                                              } else {
                                                $itemName = $detail->item->name;
                                              }
                                            ?>
                                            <tr>
                                              <td>
                                                @if($detail->status == 'pending')
                                                <input type="checkbox" class="item-checkbox item-checkbox-{{$resource->id}}" 
                                                  data-id="{{ $detail->id }}" id="item-{{$detail->id}}">
                                                @endif
                                              </td>
                                              <td>{{$itemName or '-'}}</td>
                                              <td>{{$detail->store->name or '-'}}</td>
                                              <td>{{$detail->quantity}}</td>
                                              <td>{{$detail->unite_price}}</td>
                                              <td>{{$detail->discount}}</td>
                                              <td>
                                                {{($detail->unite_price*$detail->quantity)-$detail->discount}}
                                              </td>
                                              <td style="white-space: nowrap!important;">
                                                @if($detail->status == 'accepted')
                                                <span class="text-success">تم القبول <i class="fa fa-check"
                                                    aria-hidden="true"></i></span>
                                                @endif
                                                @if($detail->status == 'pending')
                                                <span class="text-warning">فى الانتظار <i class="fa fa-clock-o"
                                                    aria-hidden="true"></i></span>
                                                @endif
                                                @if($detail->status == 'refused')
                                                <span class="text-danger">تم الرفض <i class="fa fa-ban"
                                                    aria-hidden="true"></i></span>
                                                @endif
                                              </td>
                                            </tr>
                                            @endforeach
                                          </tbody>
                                        </table>
                                        
                                        <!-- Bulk Action Buttons -->
                                        <div class="row" style="margin-top: 15px;">
                                          <div class="col-md-12">
                                            <button type="button" class="btn btn-success" id="bulkAcceptBtn{{$resource->id}}">
                                              <i class="fa fa-check"></i> قبول المحدد
                                            </button>
                                            <button type="button" class="btn btn-danger" id="bulkRejectBtn{{$resource->id}}">
                                              <i class="fa fa-ban"></i> رفض المحدد
                                            </button>
                                          </div>
                                        </div>

                                        <!-- Bulk Accept Modal -->
                                        <div class="modal fade" id="bulkAcceptModal{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="bulkAcceptModalLabel{{$resource->id}}" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="bulkAcceptModalLabel{{$resource->id}}">قبول الأصناف المحددة</h4>
                                              </div>
                                              <div class="modal-body">
                                                <div class="selected-items-list{{$resource->id}}"></div>
                                                <form action="{{ route('orders.accept') }}" method="post">
                                                  {{ csrf_field() }}
                                                  <input type="hidden" name="id" id="bulkAcceptIds{{$resource->id}}">
                                                  <div class="form-group">
                                                    <label for="bulkAcceptNotes{{$resource->id}}">ملاحظات</label>
                                                    <textarea name="notes" id="bulkAcceptNotes{{$resource->id}}" class="form-control" rows="3"></textarea>
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
                                        <div class="modal fade" id="bulkRejectModal{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="bulkRejectModalLabel{{$resource->id}}" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="bulkRejectModalLabel{{$resource->id}}">رفض الأصناف المحددة</h4>
                                              </div>
                                              <div class="modal-body">
                                                <div class="selected-items-list{{$resource->id}}"></div>
                                                <form action="{{ route('orders.refuse') }}" method="post">
                                                  {{ csrf_field() }}
                                                  <input type="hidden" name="id" id="bulkRejectIds{{$resource->id}}">
                                                  <div class="form-group">
                                                    <label for="bulkRejectNotes{{$resource->id}}">ملاحظات</label>
                                                    <textarea name="notes" id="bulkRejectNotes{{$resource->id}}" class="form-control" rows="3"></textarea>
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

                                        <script>
                                          document.addEventListener('DOMContentLoaded', function() {
                                            // Get the select all checkbox
                                            var selectAll = document.getElementById('selectAll{{$resource->id}}');
                                            
                                            // Get all item checkboxes (only pending items will have checkboxes)
                                            var itemCheckboxes = document.querySelectorAll('.item-checkbox-{{$resource->id}}');
                                            
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
                                            
                                            // Hide "Select All" checkbox if there are no pending items
                                            if (itemCheckboxes.length === 0) {
                                              selectAll.style.display = 'none';
                                            }
                                            
                                            // Bulk Accept button click handler
                                            $('#bulkAcceptBtn{{$resource->id}}').on('click', function() {
                                              var selectedIds = [];
                                              var selectedNames = [];
                                              
                                              // Get all checked checkboxes
                                              itemCheckboxes.forEach(function(checkbox) {
                                                if (checkbox.checked) {
                                                  var id = checkbox.getAttribute('data-id');
                                                  var name = $(checkbox).closest('tr').find('td:nth-child(2)').text().trim();
                                                  selectedIds.push(id);
                                                  selectedNames.push(name);
                                                }
                                              });
                                              
                                              if (selectedIds.length === 0) {
                                                alert('الرجاء تحديد عنصر واحد على الأقل');
                                                return;
                                              }
                                              
                                              // Set the ID in the hidden input
                                              $('#bulkAcceptIds{{$resource->id}}').val(selectedIds.join(','));
                                              
                                              // Update modal title and content to show selected items
                                              var modalTitle = 'قبول ' + selectedIds.length + ' صنف';
                                              var modalContent = '<p>أنت على وشك قبول الأصناف التالية:</p><ul>';
                                              
                                              selectedNames.forEach(function(name) {
                                                modalContent += '<li>' + name + '</li>';
                                              });
                                              
                                              modalContent += '</ul>';
                                              
                                              $('#bulkAcceptModal{{$resource->id}}').find('.modal-title').text(modalTitle);
                                              $('.selected-items-list{{$resource->id}}').html(modalContent);
                                              
                                              // Show the modal
                                              $('#bulkAcceptModal{{$resource->id}}').modal('show');
                                            });
                                            
                                            // Bulk Reject button click handler
                                            $('#bulkRejectBtn{{$resource->id}}').on('click', function() {
                                              var selectedIds = [];
                                              var selectedNames = [];
                                              
                                              // Get all checked checkboxes
                                              itemCheckboxes.forEach(function(checkbox) {
                                                if (checkbox.checked) {
                                                  var id = checkbox.getAttribute('data-id');
                                                  var name = $(checkbox).closest('tr').find('td:nth-child(2)').text().trim();
                                                  selectedIds.push(id);
                                                  selectedNames.push(name);
                                                }
                                              });
                                              
                                              if (selectedIds.length === 0) {
                                                alert('الرجاء تحديد عنصر واحد على الأقل');
                                                return;
                                              }
                                              
                                              // Set the ID in the hidden input
                                              $('#bulkRejectIds{{$resource->id}}').val(selectedIds.join(','));
                                              
                                              // Update modal title and content to show selected items
                                              var modalTitle = 'رفض ' + selectedIds.length + ' صنف';
                                              var modalContent = '<p>أنت على وشك رفض الأصناف التالية:</p><ul>';
                                              
                                              selectedNames.forEach(function(name) {
                                                modalContent += '<li>' + name + '</li>';
                                              });
                                              
                                              modalContent += '</ul>';
                                              
                                              $('#bulkRejectModal{{$resource->id}}').find('.modal-title').text(modalTitle);
                                              $('.selected-items-list{{$resource->id}}').html(modalContent);
                                              
                                              // Show the modal
                                              $('#bulkRejectModal{{$resource->id}}').modal('show');
                                            });
                                            
                                            // Hide bulk action buttons if there are no pending items
                                            if (itemCheckboxes.length === 0) {
                                              $('#bulkAcceptBtn{{$resource->id}}').hide();
                                              $('#bulkRejectBtn{{$resource->id}}').hide();
                                            }
                                          });
                                        </script>
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
                        <td colspan="11" class="text-center">لا يوجد بيانات</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                  <div class="text-center">
                    {{ $ordersOut->links() }}
                  </div>
                </div>
              </div>

              <!-- Order Out Return Tab -->
              <div class="tab-pane" id="order-out-return">
                <div class="table-responsive">
                  <table class="table table-bordered" width="100%">
                    <thead>
                      <tr class="bg-primary">
                        <td>رقم الفاتورة</td>
                        <td>المورد</td>
                        <td>هاتف المورد</td>
                        <td>الفرع</td>
                        <td>عرض التفاصيل</td>
                        <td>الاجمالي</td>
                        <td>الخصم</td>
                        <td>المدفوع</td>
                        <td>المتبقي</td>
                        <td>ملاحظات</td>
                        <td>العمليات</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $ordersReturnOut = App\Models\Order::whereIn('id', $ordersReturnOutIds)->paginate(10) ?>
                      @forelse($ordersReturnOut as $resource)
                      <tr>
                        <td>{{$resource->id or '-'}}</td>
                        <td>{{$resource->ownerable->name or '-'}}</td>
                        <td>{{$resource->ownerable->phone_1 or '-'}}</td>
                        <td>{{$resource->branch->name or '-'}}</td>
                        <td>
                          <button type="button" class="btn btn-info" data-toggle="modal"
                            data-target="#invoice4_{{$resource->id}}"><i class="fa fa-television"></i></button>

                          <!-- Modal -->
                          <div id="invoice4_{{$resource->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                    &times;
                                  </button>
                                  <h4 class="modal-title">الفاتورة</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="box">
                                    <div class="box-header">
                                      <table class="table table-responsive table-bordered">
                                        <tr>
                                          <td>رقم الفاتورة</td>
                                          <td>{{$resource->id}}</td>
                                          <td>التاريخ</td>
                                          <td>{{$resource->date}}</td>
                                        </tr>
                                        <tr>
                                          <td>العميل</td>
                                          <td>{{$resource->ownerable->name or '-'}}</td>
                                          <td>الموظف</td>
                                          <td>{{$resource->user->name or '-'}}</td>
                                        </tr>
                                        <tr>
                                          <td>الخزنة</td>
                                          <td>{{$resource->reposite->name or '-'}}</td>
                                          <td></td>
                                          <td></td>
                                        </tr>
                                        <tr>
                                          <td>المدفوع</td>
                                          <td>{{$resource->cost or '-'}}</td>
                                          <td>المتبقي</td>
                                          <td>{{$resource->total - $resource->cost -$resource->discount}}</td>
                                        </tr>
                                        <tr>
                                          <td>الخصم</td>
                                          <td>{{$resource->discount or '-'}}</td>
                                          <td> الإجمالي</td>
                                          <td>{{$resource->total or '-'}}</td>
                                        </tr>
                                      </table>
                                    </div>
                                    <div class="box-body">
                                      <table class="table table-responsive table-bordered">
                                        <tr>
                                          <td>الصنف</td>
                                          <td>المخزن</td>
                                          <td>الكمية</td>
                                          <td>السعر</td>
                                          <td>الخصم</td>
                                          <td>الإجمالي</td>
                                        </tr>
                                        @if(!empty($resource->orderDetails))
                                        @foreach($resource->orderDetails as $order_detail)
                                        <tr>
                                          <td>{{$order_detail->item->name or '-'}}</td>
                                          <td>{{$order_detail->store->name or '-'}}</td>
                                          <td>{{$order_detail->quantity}}</td>
                                          <td>{{$order_detail->unite_price}}</td>
                                          <td>{{$order_detail->discount}}</td>
                                          <td>
                                            {{($order_detail->unite_price*$order_detail->quantity)-$order_detail->discount}}
                                          </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                      </table>
                                    </div>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td>{{$resource->total}}</td>
                        <td>{{$resource->discount}}</td>
                        <td>{{$resource->cost}}</td>
                        <td>{{$resource->total - $resource->cost -$resource->discount}}</td>
                        <td>{{$resource->notes}}</td>
                        <td>
                          <button class="btn btn-success" data-toggle="modal"
                            data-target="#showOrderItemsModal4{{$resource->id}}">
                            <i class="fa fa-bars"></i>
                          </button>

                          <div class="modal fade" id="showOrderItemsModal4{{$resource->id}}" tabindex="-1" role="dialog"
                            aria-hidden="true">
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
                                        <table class="table table-responsive table-bordered">
                                          <tr>
                                            <td>
                                              <input type="checkbox" id="selectAll4{{$resource->id}}">
                                            </td>
                                            <td>الصنف</td>
                                            <td>المخزن</td>
                                            <td>الكمية</td>
                                            <td>السعر</td>
                                            <td>الخصم</td>
                                            <td>الإجمالي</td>
                                            <td>الحالة</td>
                                            <td>العمليات</td>
                                          </tr>
                                          <?php
                                            $sub_total = 0;
                                            $sub_total_quantity = 0;
                                            $sub_total_price = 0;
                                            $sub_total_discount = 0;
                                          ?>
                                          @foreach($resource->orderDetails as $detail)
                                          <?php
                                            $sub_total_quantity = $sub_total_quantity + $detail->quantity;
                                            $sub_total_price = $sub_total_price + $detail->unite_price;
                                            $sub_total_discount = $sub_total_discount + $detail->discount;
                                            $sub_total = $sub_total + (($detail->unite_price * $detail->quantity) - $detail->discount);
                                            if($detail->is_oper_supplies == 1){
                                              $itemName = DB::table('supplies')->where('id', $detail->item_id)->first()->name;
                                            } else {
                                              $itemName = $detail->item->name;
                                            }
                                          ?>
                                          <tr>
                                            <td>
                                              @if($detail->status == 'pending')
                                              <input type="checkbox" class="item-checkbox-4{{$resource->id}}" data-id="{{ $detail->id }}">
                                              @endif
                                            </td>
                                            <td>{{$itemName or '-'}}</td>
                                            <td>{{$detail->store->name or '-'}}</td>
                                            <td>{{$detail->quantity}}</td>
                                            <td>{{$detail->unite_price}}</td>
                                            <td>{{$detail->discount}}</td>
                                            <td>{{($detail->unite_price*$detail->quantity)-$detail->discount}}</td>
                                            <td style="white-space: nowrap!important;">
                                              @if($detail->status == 'accepted')
                                              <span class="text-success">تم القبول <i class="fa fa-check"
                                                  aria-hidden="true"></i></span>
                                              @endif
                                              @if($detail->status == 'pending')
                                              <span class="text-warning">فى الانتظار <i class="fa fa-clock-o"
                                                  aria-hidden="true"></i></span>
                                              @endif
                                              @if($detail->status == 'refused')
                                              <span class="text-danger">تم الرفض <i class="fa fa-ban"
                                                  aria-hidden="true"></i></span>
                                              @endif
                                            </td>
                                            <td style="white-space: nowrap!important;">
                                              @if($detail->status == 'pending' && !auth()->user()->hasRole('admin'))
                                              <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#acceptModal" data-id="{{ $detail->id }}"
                                                data-name="{{$itemName or '-'}}">
                                                <i class="fa fa-check"></i>
                                              </button>

                                              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#refuseModal" data-id="{{ $detail->id }}"
                                                data-name="{{$itemName or '-'}}">
                                                <i class="fa fa-ban"></i>
                                              </button>
                                              @endif
                                            </td>
                                          </tr>
                                          @endforeach
                                        </table>

                                        <!-- Bulk Action Buttons -->
                                        <div class="row" style="margin-top: 15px;">
                                          <div class="col-md-12">
                                            <button type="button" class="btn btn-success" id="bulkAcceptBtn4{{$resource->id}}">
                                              <i class="fa fa-check"></i> قبول المحدد
                                            </button>
                                            <button type="button" class="btn btn-danger" id="bulkRejectBtn4{{$resource->id}}">
                                              <i class="fa fa-ban"></i> رفض المحدد
                                            </button>
                                          </div>
                                        </div>

                                        <!-- Bulk Accept Modal -->
                                        <div class="modal fade" id="bulkAcceptModal4{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="bulkAcceptModalLabel4{{$resource->id}}" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="bulkAcceptModalLabel4{{$resource->id}}">قبول الأصناف المحددة</h4>
                                              </div>
                                              <div class="modal-body">
                                                <div class="selected-items-list4{{$resource->id}}"></div>
                                                <form action="{{ route('orders.accept') }}" method="post">
                                                  {{ csrf_field() }}
                                                  <input type="hidden" name="id" id="bulkAcceptIds4{{$resource->id}}">
                                                  <div class="form-group">
                                                    <label for="bulkAcceptNotes4{{$resource->id}}">ملاحظات</label>
                                                    <textarea name="notes" id="bulkAcceptNotes4{{$resource->id}}" class="form-control" rows="3"></textarea>
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
                                        <div class="modal fade" id="bulkRejectModal4{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="bulkRejectModalLabel4{{$resource->id}}" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="bulkRejectModalLabel4{{$resource->id}}">رفض الأصناف المحددة</h4>
                                              </div>
                                              <div class="modal-body">
                                                <div class="selected-items-list4{{$resource->id}}"></div>
                                                <form action="{{ route('orders.refuse') }}" method="post">
                                                  {{ csrf_field() }}
                                                  <input type="hidden" name="id" id="bulkRejectIds4{{$resource->id}}">
                                                  <div class="form-group">
                                                    <label for="bulkRejectNotes4{{$resource->id}}">ملاحظات</label>
                                                    <textarea name="notes" id="bulkRejectNotes4{{$resource->id}}" class="form-control" rows="3"></textarea>
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

                                        <script>
                                          document.addEventListener('DOMContentLoaded', function() {
                                            // Get the select all checkbox
                                            var selectAll4 = document.getElementById('selectAll4{{$resource->id}}');
                                            
                                            // Get all item checkboxes (only pending items will have checkboxes)
                                            var itemCheckboxes4 = document.querySelectorAll('.item-checkbox-4{{$resource->id}}');
                                            
                                            // Add event listener to select all checkbox
                                            selectAll4.addEventListener('click', function() {
                                              // Check if select all is checked
                                              var isChecked = selectAll4.checked;
                                              
                                              // Set all item checkboxes to the same state
                                              itemCheckboxes4.forEach(function(checkbox) {
                                                checkbox.checked = isChecked;
                                              });
                                            });
                                            
                                            // Add event listeners to all item checkboxes
                                            itemCheckboxes4.forEach(function(checkbox) {
                                              checkbox.addEventListener('click', function() {
                                                // Check if all item checkboxes are checked
                                                var allChecked = true;
                                                itemCheckboxes4.forEach(function(cb) {
                                                  if (!cb.checked) {
                                                    allChecked = false;
                                                  }
                                                });
                                                
                                                // Update select all checkbox
                                                selectAll4.checked = allChecked;
                                              });
                                            });
                                            
                                            // Hide "Select All" checkbox if there are no pending items
                                            if (itemCheckboxes4.length === 0) {
                                              selectAll4.style.display = 'none';
                                            }
                                            
                                            // Bulk Accept button click handler
                                            $('#bulkAcceptBtn4{{$resource->id}}').on('click', function() {
                                              var selectedIds = [];
                                              var selectedNames = [];
                                              
                                              // Get all checked checkboxes
                                              itemCheckboxes4.forEach(function(checkbox) {
                                                if (checkbox.checked) {
                                                  var id = checkbox.getAttribute('data-id');
                                                  var name = $(checkbox).closest('tr').find('td:nth-child(2)').text().trim();
                                                  selectedIds.push(id);
                                                  selectedNames.push(name);
                                                }
                                              });
                                              
                                              if (selectedIds.length === 0) {
                                                alert('الرجاء تحديد عنصر واحد على الأقل');
                                                return;
                                              }
                                              
                                              // Set the ID in the hidden input
                                              $('#bulkAcceptIds4{{$resource->id}}').val(selectedIds.join(','));
                                              
                                              // Update modal title and content to show selected items
                                              var modalTitle = 'قبول ' + selectedIds.length + ' صنف';
                                              var modalContent = '<p>أنت على وشك قبول الأصناف التالية:</p><ul>';
                                              
                                              selectedNames.forEach(function(name) {
                                                modalContent += '<li>' + name + '</li>';
                                              });
                                              
                                              modalContent += '</ul>';
                                              
                                              $('#bulkAcceptModal4{{$resource->id}}').find('.modal-title').text(modalTitle);
                                              $('.selected-items-list4{{$resource->id}}').html(modalContent);
                                              
                                              // Show the modal
                                              $('#bulkAcceptModal4{{$resource->id}}').modal('show');
                                            });
                                            
                                            // Bulk Reject button click handler
                                            $('#bulkRejectBtn4{{$resource->id}}').on('click', function() {
                                              var selectedIds = [];
                                              var selectedNames = [];
                                              
                                              // Get all checked checkboxes
                                              itemCheckboxes4.forEach(function(checkbox) {
                                                if (checkbox.checked) {
                                                  var id = checkbox.getAttribute('data-id');
                                                  var name = $(checkbox).closest('tr').find('td:nth-child(2)').text().trim();
                                                  selectedIds.push(id);
                                                  selectedNames.push(name);
                                                }
                                              });
                                              
                                              if (selectedIds.length === 0) {
                                                alert('الرجاء تحديد عنصر واحد على الأقل');
                                                return;
                                              }
                                              
                                              // Set the ID in the hidden input
                                              $('#bulkRejectIds4{{$resource->id}}').val(selectedIds.join(','));
                                              
                                              // Update modal title and content to show selected items
                                              var modalTitle = 'رفض ' + selectedIds.length + ' صنف';
                                              var modalContent = '<p>أنت على وشك رفض الأصناف التالية:</p><ul>';
                                              
                                              selectedNames.forEach(function(name) {
                                                modalContent += '<li>' + name + '</li>';
                                              });
                                              
                                              modalContent += '</ul>';
                                              
                                              $('#bulkRejectModal4{{$resource->id}}').find('.modal-title').text(modalTitle);
                                              $('.selected-items-list4{{$resource->id}}').html(modalContent);
                                              
                                              // Show the modal
                                              $('#bulkRejectModal4{{$resource->id}}').modal('show');
                                            });
                                            
                                            // Hide bulk action buttons if there are no pending items
                                            if (itemCheckboxes4.length === 0) {
                                              $('#bulkAcceptBtn4{{$resource->id}}').hide();
                                              $('#bulkRejectBtn4{{$resource->id}}').hide();
                                            }
                                          });
                                        </script>

                                        @if($resource->employee->isEmpty() && !auth()->user()->hasRole('admin'))
                                        <form action="{{route('orders.setEmployees')}}" method="post">
                                          <input type="text" name="id" value="{{$resource->id}}" hidden>

                                          <div class="row">
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                <label>الموظفين</label>
                                                <select name="employee_id[]" class="form-control" multiple required>
                                                  @foreach(\App\Models\Employee::where('job_id', 2)->where('branch_id', Auth::user()->branch_id)->get() as $employee)
                                                  <option value="{{$employee->id}}">{{$employee->name}}</option>
                                                  @endforeach
                                                </select>
                                              </div>
                                            </div>
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                <label>ملاحظة</label>
                                                <input type="text" name="notes" class="form-control">
                                              </div>
                                            </div>
                                            <div class="col-md-12">
                                              <button type="submit" class="btn btn-primary">تأكيد</button>
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

                          <a href="{{url('orders_out_store/'.$resource->id)}}" target="_blank" class="btn btn-success" title="إذن صرف من المخزن"><i class="fa fa-print"></i></a>
                          <a href="{{url('orders_out_license/'.$resource->id)}}" class="btn btn-instagram" target="_blank" title="إذن"><i class="fa fa-print"></i></a>
                          <a href="{{url('orders-out/'.$resource->id)}}" class="btn btn-info" target="_blank" title="فاتورة"><i class="fa fa-print"></i></a>
                        </td>
                      </tr>
                      @empty
                      <tr>
                        <td class="text-danger text-center" colspan="10">لم يتم العثور على بيانات</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                  {{$ordersOut->links()}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.box -->
    </div>
  </div>

  <!-- Accept Modal -->
  <div class="modal fade" id="acceptModal" tabindex="-1" role="dialog" aria-labelledby="acceptModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="acceptModalLabel">قبول الصنف</h4>
        </div>
        <div class="modal-body">
          <div class="selected-items-list"></div>
          <form action="{{ route('orders.accept') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id">
            <div class="form-group">
              <label for="accept_notes">ملاحظات</label>
              <textarea name="notes" id="accept_notes" class="form-control" rows="3"></textarea>
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

  <!-- Refuse Modal -->
  <div class="modal fade" id="refuseModal" tabindex="-1" role="dialog" aria-labelledby="refuseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="refuseModalLabel">رفض الصنف</h4>
        </div>
        <div class="modal-body">
          <div class="selected-items-list"></div>
          <form action="{{ route('orders.refuse') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id" id="id">
            <div class="form-group">
              <label for="refuse_notes">ملاحظات</label>
              <textarea name="notes" id="refuse_notes" class="form-control" rows="3"></textarea>
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

  <!-- Bulk Accept Modal -->
  <div class="modal fade" id="bulkAcceptModal{{$resource->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            &times;
          </button>
          <h4 class="modal-title">
            قبول الأصناف المحددة
          </h4>
        </div>
        <div class="modal-body">
          <h4 class="text-danger">
            أنت على وشك قبول جميع الأصناف المحددة
          </h4>
          <br />
          <form action="{{route('orders.accept')}}" method="post" id="bulkAcceptForm{{$resource->id}}">
            @csrf
            <input type="hidden" name="id" id="bulkAcceptIds{{$resource->id}}">
            <div class="form-group">
              <label>كتابة ملاحظتك</label>
              <textarea name="notes" id="accept_notes" class="form-control" rows="3"></textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">
                إلغاء
              </button>
              <button type="submit" class="btn btn-success" id="bulkAcceptButton{{$resource->id}}">
                قبول
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bulk Reject Modal -->
  <div class="modal fade" id="bulkRejectModal{{$resource->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            &times;
          </button>
          <h4 class="modal-title">
            رفض الأصناف المحددة
          </h4>
        </div>
        <div class="modal-body">
          <h4 class="text-danger">
            أنت على وشك رفض جميع الأصناف المحددة
          </h4>
          <br />
          <form action="{{route('orders.refuse')}}" method="post" id="bulkRejectForm{{$resource->id}}">
            @csrf
            <input type="hidden" name="id" id="bulkRejectIds{{$resource->id}}">
            <div class="form-group">
              <label>كتابة ملاحظتك</label>
              <textarea name="notes" id="refuse_notes" class="form-control" rows="3"></textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">
                إلغاء
              </button>
              <button type="submit" class="btn btn-danger" id="bulkRejectButton{{$resource->id}}">
                رفض
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    $('#acceptModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget)
      var id = button.data('id')
      var name = button.data('name')
      var modal = $(this)
      modal.find('.modal-body #id').val(id);
      modal.find('.modal-body #name').val(name);
    });

    $('#refuseModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget)
      var id = button.data('id')
      var name = button.data('name')
      var modal = $(this)
      modal.find('.modal-body #id').val(id);
      modal.find('.modal-body #name').val(name);
    });

    $('#acceptForm').submit(function () {
      $('#acceptButton').prop('disabled', true);
    });

    $('#refuseForm').submit(function () {
      $('#refuseButton').prop('disabled', true);
    });
  </script>
</div>
@endsection
