@extends('layout.app')
@section('title','الحضور و الانصراف')
@section('sub-title','الرئسية')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">الحضور و الانصراف</h3>
                    <div class="box-btn">
                    
                        <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary btn-flat btn-sm" data-toggle="modal"
                                    data-target="#modal">
                                <i class="fa fa-calendar"></i>
                            </button>
                    @if(Auth::user()->can('attendance_setting'))    
                        <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary btn-flat btn-sm" data-toggle="modal"
                                    data-target="#settingsModal">
                                <i class="fa fa-cog"></i>
                            </button>
                    @endif
                            <!-- Modal -->
                            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form class="validate">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="modalLabel"> اختر التاريخ</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="date">التاريخ</label>
                                                            @if(auth()->user()->hasRole('admin'))
                                                            <input type="text" id="date" required class="form-control date" name="date" value="{{Carbon\Carbon::now()->toDateString()}}">
                                                            @else
                                                            <input type="text" id="date" required class="form-control" name="date" value="{{Carbon\Carbon::now()->toDateString()}}" readonly>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default btn-sm btn-flat"
                                                        data-dismiss="modal">الغاء
                                                </button>
                                                <button type="submit" id="accept"
                                                        class="btn btn-primary btn-sm btn-flat">
                                                    موافق
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- settings Modal -->
                            <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog"
                                 aria-labelledby="settingsModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form class="validate-settings">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="settingsModalLabel"> الاعدادات العامة</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="attendance_time">وقت الحضور</label>
                                                            <input type="time" id="attendance_time" required
                                                                   class="form-control" name="attendance_time"
                                                                   value="{{optional(optional($setting)->attendance_time)->toTimeString()}}">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="work_days">عدد ايام العمل في الشهر</label>
                                                            <input type="text" id="work_days" required
                                                                   class="form-control number" name="work_days"
                                                                   value="{{optional($setting)->work_days}}">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="work_hours"> عدد ساعات العمل في اليوم </label>
                                                            <input type="text" id="work_hours" required
                                                                   class="form-control number" name="work_hours"
                                                                   value="{{optional($setting)->work_hours}}">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="allowed_absence">عدد ايام الغياب في السنة </label>
                                                            <input type="number" id="allowed_absence" required
                                                                   class="form-control number" name="allowed_absence"
                                                                   value="{{optional($setting)->allowed_absence}}">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="allowed_emergency_absence"> عدد ايام العارضة في السنة </label>
                                                            <input type="number" id="allowed_emergency_absence" required
                                                                   class="form-control number" name="allowed_emergency_absence"
                                                                   value="{{optional($setting)->allowed_emergency_absence}}">
                                                        </div>
                                                    </div>


                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="allowance_time"> فترة السماح بالدقائق</label>
                                                            <input type="number" id="allowance_time" required
                                                                   class="form-control number" name="allowance_time"
                                                                   value="{{optional($setting)->allowance_time}}">
                                                        </div>
                                                    </div>
                                                    @php
                                                        $endPermitedTime = Carbon\Carbon::parse($setting->end_permited_attend_time)->format('H-i-s');
                                                        // dd($endPermitedTime->toTimeString());
                                                    @endphp
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="end_permited_attend_time">اخر معاد للتسجيل</label>
                                                            <strong><mark> {{optional($setting)->end_permited_attend_time}}</mark></strong>
                                                            <input type="time" id="end_permited_attend_time" required
                                                                   class="form-control" name="end_permited_attend_time"
                                                                   value="{{optional(optional($setting)->end_permited_attend_time)->toTimeString()}}">
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default btn-sm btn-flat"
                                                        data-dismiss="modal">الغاء
                                                </button>
                                                <button type="submit" id="accept"
                                                        class="btn btn-primary btn-sm btn-flat">
                                                    موافق
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        
                    </div>

                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <div class="alert alert-danger">
                        <p class="text-center">
                            <strong>
                                التاريخ : -
                            </strong>
                            <span id="current-date">
                  {{Carbon\Carbon::now()->toDateString()}}
                </span>
                        </p>
                    </div>

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
    <script>


        var $modal = $('#modal');
        var $settingModal = $('#settingsModal');
        var $accept = $('#accept')
        var $date = $('#date');
        var date = '{{Carbon\Carbon::now()->toDateString()}}';
        var $dateForm = $('.validate');
        var $table = $('.table');
        var $currentDate = $('#current-date')
        var $settingForm = $('.validate-settings');

        $(document).ready(function () {
            $dateForm.validate();
            $settingForm.validate()
            $dateForm.on('submit', function (e) {
                e.preventDefault();
                if ($dateForm.valid()) {
                    date = $date.val();
                    $table.DataTable().clear().draw();
                    $modal.modal('hide');
                    $currentDate.text(date);
                }

            })
            $settingForm.on('submit', function (e) {

                e.preventDefault();

                attendance_time = $('#attendance_time').val();
                end_permited_attend_time = $('#end_permited_attend_time').val();
                work_hours = $('#work_hours').val();
                work_days
                work_days = $('#work_days').val();
                allowed_absence = $('#allowed_absence').val();
                allowed_emergency_absence = $('#allowed_emergency_absence').val();
                allowance_time = $('#allowance_time').val();

                if ($settingForm.valid()) {
                    $.ajax({
                        url: '{{route('api.attendance-settings.update')}}',
                        type: 'POST',
                        data: {
                            attendance_time: attendance_time,
                            end_permited_attend_time: end_permited_attend_time,
                            work_hours: work_hours,
                            work_days: work_days,
                            allowed_absence: allowed_absence,
                            allowed_emergency_absence: allowed_emergency_absence,
                            allowance_time: allowance_time
                        },
                        success: function (data) {
                            iziToast.success({
                                timeout: 1000,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                                position: 'bottomLeft',
                                rtl: true,
                                message: 'تم التعديل بنجاح ',
                            });
                        },
                        error: function (err) {
                            iziToast.error({
                                timeout: 1000,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                                position: 'bottomLeft',
                                rtl: true,
                                message: 'خطا بالسيرفر ',
                            });
                        }
                    })
                    $settingModal.modal('hide');
                }

            })


            $(document).on('change', '.observe', function () {
                var key = null;
                var value = null;
                var employee_id = $(this).data('employee')
                var abandonment_time = $(".abandonment-"+employee_id).val();
                key = $(this).data('name')
                if ($(this).data('is-check')) {
                    value = $(this).prop('checked') ? 1 : 0;
                } else {
                    value = $(this).val()
                }

                data = {
                    employee_id: employee_id,
                    date: $date.val(),
                    key: key,
                    value: value,
                    abandonment_time: abandonment_time,
                    auth_user_id:'{!! Auth()->user()->id !!}'
                };
                console.log(data);
                //send ajax request
                $.ajax({
                    url: '{{route('api.attendance-store')}}',
                    data: data,
                    type: 'POST',
                    success: function (data) {
                        $table.DataTable().clear().draw();
                        if (data.done) {
                            iziToast.success({
                                timeout: 1000,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                                position: 'bottomLeft',
                                rtl: true,
                                message: 'تم التعديل بنجاح ',
                            });
                        }
                        else if (data.notPermission) {
                            iziToast.warning({
                                timeout: 1000,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                                position: 'bottomLeft',
                                rtl: true,
                                message: 'لا يمكن عمل وقت الانصراف بعد الوقت المحدد',
                            });
                        }
                        else {
                            iziToast.warning({
                                timeout: 1000,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                                position: 'bottomLeft',
                                rtl: true,
                                message: 'رصيد الموظف من الاجازات لايكفي',
                            });
                        }

                        console.log(data);
                    },
                    error: function (err) {
                        console.log(err);
                        iziToast.error({
                            timeout: 1000,
                            transitionIn: 'flipInX',
                            transitionOut: 'flipOutX',
                            position: 'bottomLeft',
                            rtl: true,
                            message: 'خطا بالسيرفر ',
                        });
                    }
                })
            })


            $(document).on('click', '.destroy', function () {
                id = $(this).data('id');
                data = {
                    id: id,
                    auth_user_id:'{!! Auth()->user()->id !!}'
                };
                $.ajax({
                    url: '{{route('api.attendance-destroy')}}',
                    data: data,
                    type: 'DELETE',
                    success: function (data) {


                        $table.DataTable().clear().draw();
                        iziToast.success({
                            timeout: 1000,
                            transitionIn: 'flipInX',
                            transitionOut: 'flipOutX',
                            position: 'bottomLeft',
                            rtl: true,
                            message: 'تمت اعادة الضبط بنجاح ',
                        });
                        console.log(data);
                    },
                    error: function (err) {
                        console.log(err);
                        iziToast.error({
                            timeout: 1000,
                            transitionIn: 'flipInX',
                            transitionOut: 'flipOutX',
                            position: 'bottomLeft',
                            rtl: true,
                            message: 'خطا بالسيرفر ',
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