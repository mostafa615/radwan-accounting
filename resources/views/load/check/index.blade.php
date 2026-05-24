@extends('layout.app')
@section('title', 'فحص طلبات التحميل')
@section('sub-title', 'الرئيسية')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body row">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#loads" data-toggle="tab" aria-expanded="false">فحص طلبات التحميل بين المخازن</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="loads">
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr class="bg-primary">
                        <td>الكود</td>
                        <td>المستخدم</td>
                        <td>تحويل من</td>
                        <td>تحويل الى</td>
                        <td>التاريخ</td>
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
                        <td>
                          <button class="btn btn-default" data-toggle="modal" data-target="#custom-modal_{{$load->id}}">
                            <i class="fa fa-repeat"></i>
                          </button>
                          <div class="modal fade" id="custom-modal_{{$load->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title" id="ModalLabel">قبول او رفض التحويل</h4>
                                  </div>
                                  <div class="modal-body">
                                    <h4 class="text-danger">انت الان على وشك قبول او رفض التحويل</h4>
                                    <br>
                                    <form action="{{route('loads.check.refuse')}}" method="post">
                                        <input type="text" name="id" value="{{$load->id}}" hidden>
                                        
                                        <div class="form-group">
                                            <label>كتابة ملاحظتك</label>
                                            <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                                        </div>

                                        <div class="form-group">
                                          <label>اختيار الموظفين القائمين على التحميل - <span>فى حالة القبول</span></label>
                                          <select name="employee_id[]" class="form-control" multiple>
                                            @foreach(\App\Models\Employee::where('branch_id', Auth::user()->branch_id)->get() as $employee)
                                              <option value="{{$employee->id}}">{{$employee->name}}</option>
                                            @endforeach
                                          </select>
                                        </div>
                                        <br>
                                        <button type="submit" class="btn btn-danger">رفض التحويل</button>
                                        <button type="submit" class="btn btn-success" formaction="{{route('loads.check.accept')}}">قبول التحويل</button>
                                    </form>
                                  </div>
                              </div>
                            </div>
                          </div>

                          <button class="btn btn-info" data-toggle="modal" data-target="#showLoadItemsModal{{$load->id}}">
                            <i class="fa fa-television"></i>
                          </button>
                          <div class="modal fade" id="showLoadItemsModal{{$load->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                    &times;
                                  </button>
                                  <h4 class="modal-title" id="firstModalLabel">عرض الاصناف المحولة</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <div class="table-responsive">
                                        <table class="table table-bordered">
                                          <thead>
                                            <tr>
                                              <td>الصنف</td>
                                              <td>الكمية</td>
                                              <td>الحالة</td>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            @foreach($load->loadDetails as $detail)
                                            <tr>
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
@endsection