@extends('layout.app')
@section('title','التقارير')
@section('sub-title','الاجازات')
@section('content')

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">

              <li class="active"><a href="#attendance-detailed-tab" data-toggle="tab" aria-expanded="true">تفصيلي</a></li>
            
            </ul>
            <div class="tab-content">
              <div class="tab-pane  active" id="attendance-detailed-tab">
                 <div class="table-responsive">
                 <div class="box">
        <div class="box-header"> 
            <row>
                    <?php $availableEmergency = App\Models\AttendanceSettings::first()->allowed_emergency_absence; ?>
                <div class="col-md-6">
                    <table class="table table table-bordered text-center" id="">
                        <thead>
                            <tr>
                                <td> اسم الموظف </td>
                                <td> رصيد الاجازات </td>
                                <td>  المصيف </td>
                                <td> العارضة  </td>
                            </tr>
                            <tr>
                                <td>{{ optional($employee)->name }}</td>
                                <td>{{ $employee->holidaysWithYear($year) }}</td>
                                <td>{{ $employee->summerHolidaysWithYear($year) }}</td>
                                <td>{{ $availableEmergency }}</td>
                            </tr> 
                            </thead>
                        <tbody> 
                        </tbody>
                    </table>
                </div>
            </row>
        </div>
    
        <div class="box-body">
      
            <table class="table table table-bordered text-center" id="example_1">
                <thead>
                    <tr>
                        <td> #</td>
                        <td> التاريخ</td>
                        <td>رصيد الأجازات</td>
                        <td>المصيف</td>
                        <td>العارضة </td>
                    </tr> 
                </thead>
                <tbody> 
                    <?php
                        $absence_with_holiday = $resources->where('absence_with_holiday',1)->count();
                        $summer_holidays = $resources->where('summer_holidays',1)->count();
                        $emergency_absence = $resources->where('emergency_absence',1)->count();
                    ?>   
                
                    
                    <tr style="background-color:#00FF00;">
                        <td>الاجمالي</td>
                        <td>{{ $resources->count() }}</td>
                        <td>{{ $absence_with_holiday }}</td>
                        <td>{{ $summer_holidays }}</td>
                        <td>{{ $emergency_absence }}</td>
                    </tr>
                    <tr style="background-color:#FFFF00;">
                        <td>المتبقي منها</td>
                        <td></td>
                        <td>{{ $employee->holidaysWithYear($year) - $absence_with_holiday }}</td>
                        <td>{{ $employee->summerHolidaysWithYear($year) - $summer_holidays }}</td>
                        <td>{{ $availableEmergency - $emergency_absence }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
                     <!-- <table width="100%" id="attendance-detailed-table" class="table table-bordered"></table>  -->
                </div>
              </div>
              <!-- /.tab-pane -->

           

            </div>
            <!-- /.tab-content -->
          </div>



    
@stop
@push('scripts')
@endpush