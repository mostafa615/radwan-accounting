
<?php 
        $load_details = App\Models\LoadDetail::find($id);
        $load = App\Models\Load::find($load_details->load_id);
        // dd($quantity);
?>
@if(auth()->user()->store_id == $load->to_id || auth()->user()->id == 1)
<button  data-route="{{route('api.pending-load.loads.update',[ $id, $quantity ])}}" data-status="accepted" class="btn load-detect-status btn-flat btn-success btn-sm">
        <i class="fa fa-check" ></i>
</button>
@endif