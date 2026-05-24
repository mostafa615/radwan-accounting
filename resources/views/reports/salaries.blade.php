@extends('layout.app')
@section('title','التقارير')
@section('sub-title','المرتبات')
@section('content')
    <div class="box">
        <div class="box-body table-responsive" style="overflow: auto!important" >

            <table class="table table table-bordered text-center" id="salary">
                <thead>
                <tr>
                    <td>#</td>
                    <td>الموظف</td>
                    <td>الملاحظات</td>
                    <td>الاساسي</td>
                    <td>تأمينات</td>
                    <td>سلف</td>
                    <td>مديونية</td>
                    <td>مكافأت</td>
                    <td>جزاءات</td>
                    <td>النقلات</td>
                    <td>ايام التاخير </td>
                    <td> الاضافي </td>
                    <td>ايام الغياب </td>
                    <td>تأخيرات </td>
                    <td> وغياب</td>
                    <td>الصافي</td>
                    <td>تاريخ الصرف</td>
                </tr>
                </thead>
                <tbody>
                @foreach($resources as $resource)
              
                <?php 
                                $employee = App\Models\Employee::find($resource->employee_id);
                                $workDays = App\Models\AttendanceSettings::first()->work_days;
                                $workHours = App\Models\AttendanceSettings::first()->work_hours;
                                $hour_cost = $resource->basic / ($workDays * $workHours);
                                $lateCost = $hour_cost * $employee->late($date);
                ?>
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{optional($resource->employee)->name}}</td>
                        <td>{{$resource->notes}}</td>
                        <td>{{$resource->basic}}</td>
                        <td>{{$resource->insurance}}</td>
                        <td>{{$resource->loans}}</td>
                        <td>{{$resource->madionia}}</td>
                        <td>{{$resource->bonus}}</td>
                        <td>{{$resource->financial_penalties}}</td>
                        <td>{{$resource->transports}}</td>
                        <td>{{ round($resource->employee->late($date),2) }}</td>
                        <td>{{ round($hour_cost * $resource->employee->overTime($date),2) }}</td>
                        <td>{{ $resource->employee->absence($date) }}</td>
                        <td>
                            <?php 
                                echo number_format($lateCost, 0);
                            ?>
                        </td>
                        <td>
                            <?php  
                                $workDays = App\Models\AttendanceSettings::first()->work_days;
                                $day_cost = $resource->basic / ($workDays);
                                $absenceCost = $day_cost * $employee->absence($date);
                                echo number_format($absenceCost, 0);
                            ?>
                        </td>
                        <td>{{$resource->basic - $resource->madionia - $resource->loans + $resource->transports + $resource->bonus - $resource->financial_penalties - $resource->insurance - (int) $lateCost  + round($hour_cost * $resource->employee->overTime($date),2) - (int) $absenceCost }}</td>
                        <td>{{$resource->created_at->format('Y-m-d')}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@stop
@push('scripts')

<script>
    $(document).ready(function() {
    $('#salary').DataTable( {
        dom: 'Bfrtip',
        "ordering": false,
        paging: false,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>

@endpush