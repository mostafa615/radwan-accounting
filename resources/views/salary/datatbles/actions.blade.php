<div style="width: 100px">
    @if($salaryExist)
    <button data-employee-id="{{$id}}" class="perform-save btn btn-sm btn-flat btn-danger">
        <i class="fa fa-check"></i>
    </button>
    @else
    <button data-employee-id="{{$id}}" class="perform-save btn btn-sm btn-flat btn-success">
        <i class="fa fa-check"></i>
    </button>
    @endif
    <!-- <button type="button" onclick="resetSalary('{{ $id }}', '{{ $salary->id }}', '{{ $date }}', this)"  class="btn btn-sm btn-warning btn-flat">
         reset
    </button> -->
</div>