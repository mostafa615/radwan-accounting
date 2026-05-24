@extends('layout.app')
@section('title','طلبات تحويل الخامات')
@section('sub-title','الرئيسية')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body row">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#load-list" data-toggle="tab" aria-expanded="false"> طلبات التحميل بين المخازن</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="load-list">
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr class="bg-primary">
                        <td >الكود</td>
                        <td>المستخدم</td>
                        <td>تحويل من</td>
                        <td>التاريخ</td>
                        <td>ملاحظات</td>
                        <th>الحالة</th>
                        <th>العمليات</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($resources as $resource)
                      <tr>
                        <td>{{$resource->id}}</td>
                        <td>{{optional($resource->user)->name}}</td>
                        <td>{{optional($resource->from)->name}}</td>
                        <td>{{$resource->date}}</td>
                        <td>{{$resource->notes}}</td>
                        <td>
                          @if($resource->status == 'pending')
                          <span class="label label-warning">فى الانتظار</span>
                          @endif
                        </td>
                        <td>
                          <button class="btn btn-default" data-toggle="modal" data-target="#custom-modal_{{$resource->id}}">
                            <i class="fa fa-repeat"></i>
                          </button>

                          <div class="modal fade" id="custom-modal_{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                    &times;
                                  </button>
                                  <h4 class="modal-title" id="ModalLabel">قبول او رفض التحويل</h4>
                                </div>
                                <div class="modal-body">
                                  <h4 class="text-danger">انت الان على وشك قبول او رفض التحويل..</h4>
                                  <br />
                                  <form action="{{route('loads.refuse')}}" method="post">
                                    <input type="text" name="id" value="{{$resource->id}}" hidden />

                                    <div class="form-group">
                                      <label>كتابة ملاحظتك *</label>
                                      <textarea class="form-control" name="notes" id="notes" rows="3" required></textarea>
                                    </div>

                                    <div class="form-group">
                                      <label>اختيار الموظفين القائمين عليها *</label>
                                      <select name="employee_id[]" class="form-control" multiple required>
                                        @foreach(\App\Models\Employee::where('branch_id', Auth::user()->branch_id)->get() as $employee)
                                        <option value="{{$employee->id}}">{{$employee->name}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                    <br />
                                    <button type="submit" class="btn btn-danger">رفض التحويل</button>
                                    <button type="submit" class="btn btn-success" formaction="{{route('loads.accept')}}">قبول التحويل</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>

                          <button class="btn btn-success" data-toggle="modal" data-target="#showLoadItemsModal{{$resource->id}}">
                            <i class="fa fa-bars"></i>
                          </button>
                          <div id="showLoadItemsModal{{$resource->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                    &times;
                                  </button>
                                  <h4 class="modal-title">تفاصيل الخامات</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <div class="table-responsive">
                                        <table width="100%" id="order-items-table" class="table table-bordered">
                                          <tbody>
                                            <tr>
                                              <td>الصنف</td>
                                              <td>الكمية</td>
                                            </tr>
                                            @foreach($resource->loadDetails as $detail)
                                            <tr>
                                              <td>{{optional($detail->item)->name}}</td>
                                              <td>{{$detail->quantity}}</td>
                                            </tr>
                                            @endforeach
                                          </tbody>
                                        </table>
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
                        <td class="text-danger text-center" colspan="7">لم يتم العثور على بيانات</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                  {{$resources->links()}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.box -->
    </div>
  </div>
</div>
@endsection