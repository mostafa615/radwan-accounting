@extends('layout.app')
@section('title','التقارير')
@section('sub-title','المرتبات')
@section('content')
<style>
    .line {
  height: 2px;
  flex: 1;
  background: #d2d6de;
  margin: 20px 10px 10px 10px;
}
@media print {
    /* Add your print-specific styles here */
    body {
        font-size: 12pt;
    }
 
    /* Example: Hide the print button when printing */
    button {
        display: none;
    }
}
</style>
	
<button onclick="printPage()" >طباعة</button>



    <div class="box" id="printJS-form">
        <div class="" style="overflow: hidden!important" >
        <div style="display:none">{{ $variable = 1 }}</div>
         

                @foreach($resources as $resource)
                    <?php 
                        $employee = App\Models\Employee::find($resource->employee_id);
                        $workDays = App\Models\AttendanceSettings::first()->work_days;
                        $workHours = App\Models\AttendanceSettings::first()->work_hours;
                        $hour_cost = $resource->basic / ($workDays * $workHours);
                        $lateCost = $hour_cost * $employee->late($date);
                        $day_cost = $resource->basic / ($workDays);
                        $absenceCost = $day_cost * $employee->absence($date);
                    ?>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="height:400px; margin-bottom:115px;">
                   
                        <div style="display:none">{{ $variable++ }}</div>

                        <div class="line" style="height: 2px !important; flex: 1 !important; background: #d2d6de !important;margin: 20px 10px 10px 10px !important;"></div>
                        <center style="font-size:15px; font-weight:bold;"> الموظف: <span>{{optional($resource->employee)->name}} </span></center><br>
                        الاساسي: <span>{{$resource->basic}} </span><br>
                        تأمينات: <span>{{$resource->insurance}} </span><br>
                        سلف: <span>{{$resource->loans}} </span><br>
                        مديونية: <span>{{$resource->madionia}} </span><br>
                        مكافأت: <span>{{$resource->bonus}} </span><br>
                        جزاءات: <span>{{$resource->financial_penalties}} </span><br>
                        النقلات: <span>{{$resource->transports}} </span><br>
                        ايام التاخير: <span>{{ round($resource->employee->late($date),2) }} </span><br>
                        الاضافي: <span>{{ round($hour_cost * $resource->employee->overTime($date),2) }}</span><br>
                        ايام الغياب: <span>{{ $resource->employee->absence($date) }}</span><br>
                        تاخيرات: <span><?php echo number_format($lateCost, 0); ?> </span><br>
                        غياب: <span><?php echo number_format($absenceCost, 0); ?> </span><br>
                        الصافي: <span>{{$resource->basic - $resource->madionia - $resource->loans + $resource->transports + $resource->bonus - $resource->financial_penalties - $resource->insurance -(int) $lateCost + round($hour_cost * $resource->employee->overTime($date),2) -(int) $absenceCost }}</span><br>
                        تاريخ الصرف: <span>{{$resource->created_at->format('Y-m-d')}}</span><br>
                         ملاحظات: <span>{{$resource->notes}}</span><br>
                        <div class="line" style="height: 2px !important; flex: 1 !important; background: #d2d6de !important;margin: 20px 10px 10px 10px !important; "></div>
                    </div>
                    
                @endforeach
          
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
        // buttons: [
        //     'copy', 'csv', 'excel', 'pdf', 'print'
        // ]
    } );
} );
</script>

<script>
    function printPage() {
        window.print();
    }
</script>

@endpush