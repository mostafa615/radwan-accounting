@extends('layout.app')
@section('title','التقارير')
@section('sub-title','الاجازات')
@section('content')

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">

              <li class=""><a href="#attendance-abstracted-tab" data-toggle="tab" aria-expanded="false">اجمالي</a></li>
             
            </ul>
      

  <div class="" id="">
    <div class="table-responsive">
                     
    <div class="box-body">
      
      <table class="table table table-bordered text-center" id="example_1">
          <thead>
              <tr>
                  <td> #</td>
                  <td> الاسم</td>
                  <td> الأساسي</td>
                  <td>رصيد الأجازات</td>
                  <td>المصيف</td>
                  <td>العارضة </td>
              </tr> 
          </thead>
          <tbody> 


            <?php $availableEmergency = App\Models\AttendanceSettings::first()->allowed_emergency_absence; ?>

              @foreach($employees as $employee) 
              <?php
             
              // $resources = App\Models\Attendance::where('employee_id', $employee->id)
              // ->whereDate('date', '>=', Carbon\Carbon::parse($date_from))
              // ->whereDate('date', '<=', Carbon\Carbon::parse($date_to));


           

              //   $resources->where(function ($q) {
              //       $q->where('absence_with_holiday', 1)
              //       ->orWhere('summer_holidays', 1)
              //       ->orWhere('emergency_absence', 1);
              //   })
              //   ->get();
                ?>
              <?php
                  $absence_with_holiday = App\Models\Attendance::where('employee_id', $employee->id)
                  ->whereDate('date', '>=', Carbon\Carbon::parse($date_from))
                  ->whereDate('date', '<=', Carbon\Carbon::parse($date_to))
                  ->where('absence_with_holiday', 1)->count();
                  $summer_holidays = App\Models\Attendance::where('employee_id', $employee->id)
                  ->whereDate('date', '>=', Carbon\Carbon::parse($date_from))
                  ->whereDate('date', '<=', Carbon\Carbon::parse($date_to))
                  ->where('summer_holidays', 1)->count();
                  $emergency_absence = App\Models\Attendance::where('employee_id', $employee->id)
                  ->whereDate('date', '>=', Carbon\Carbon::parse($date_from))
                  ->whereDate('date', '<=', Carbon\Carbon::parse($date_to))
                  ->where('emergency_absence', 1)->count();
              ?> 
              
              <tr>

                  <td>{{ $loop->iteration }}</td>
                  <td>{{$employee->name }}</td>
                  <td>(الاجازات:{{ $employee->holidaysWithYear($year)}}) - (المصيف:{{ $employee->summerHolidaysWithYear($year) }}) - (العارضة:{{ $availableEmergency }})</td>
                  <td>{{ $absence_with_holiday }}</td>
                  <td>{{ $summer_holidays }}</td>
                  <td>{{ $emergency_absence }}</td>
               
              </tr>
              @endforeach
             
          </tbody>
      </table>

  </div>


                 </div>
              </div>
              <!-- /.tab-pane -->

            </div>
            <!-- /.tab-content -->
          </div>



    
@stop
@push('scripts')
@endpush