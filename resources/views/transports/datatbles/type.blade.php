
@if($transport->type == "in")
<button data-employee-id="" class="perform-save btn btn-sm btn-flat btn-success">
        <i class="fa fa-check"> بيع </i>
    </button>
@elseif($transport->type == "out")
<button data-employee-id="" class="perform-save btn btn-sm btn-flat btn-danger">
        <i class="fa fa-check"> مرتجع </i>
    </button>
@endif
