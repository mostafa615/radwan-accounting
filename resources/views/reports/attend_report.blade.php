@extends('layout.app')
@section('title','التقارير')
@section('sub-title','الحضور والانصراف السنوي')
@section('content')
<div class="box">
    <div class="box-header"> 
    </div>
    <div class="box-body">

        <table class="table table table-bordered text-center" id="example_1">
            <thead>
                <tr>
                    <td>اسم الموظف</td>
                    <td> غ بإذن</td>
                    <td>غ بدون إذن</td>
                    <td>غ بدون إذن في الخصم</td>
                    <!-- <td>س التأخير</td> -->
                    <td>س ت باذن</td>
                    <td> ت بدون اذن</td>
                    <td>انصراف بإذن</td>
                    <td> س التأخير(يوم) </td>
                    <td>م أ المخصومة</td>
                </tr> 
            </thead>
            <tbody> 
                
                @foreach($employees as $employee)
                <?php 
                $absenceWithPermission = $employee->attend_report['absenceWithPermission'];
                $totalAbsence = $employee->attend_report['totalAbsence'];
                $late = $employee->attend_report['late'];
                $sumDays = round($employee->attend_report['lates_with_permission_last'] / 60, 2) + round($employee->attend_report['lates_without_permission_last'] / 60, 2)  + round($employee->attend_report['leave_without_permission_last'] / 60,2);
                ?>
                <tr>
                    <td>{{$employee->name}}</td>
                    <td>{{ $absenceWithPermission}}</td>
                    <td>{{$employee->attend_report['absenceInMonth']}}</td>
                    <td>{{$totalAbsence}}</td>
                    <!-- <td>{{round($late, 2)}}</td> -->
                    <td>{{round($employee->attend_report['lates_with_permission_last'] / 60, 2)}}</td>
                    <td>{{round($employee->attend_report['lates_without_permission_last'] / 60, 2) }}</td>
                    <td>{{round($employee->attend_report['leave_without_permission_last'] / 60,2)}}</td>
                    <td>{{round($sumDays / 8, 2)}}</td>
                    <td>{{round(($absenceWithPermission) + ($totalAbsence) + ($sumDays / 8), 2)}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@stop
@push('scripts')
@endpush