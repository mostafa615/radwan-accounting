@php
  $load = App\Models\Load::find($id);
  $loads = $load->loadDetails;
  $pending = false;
  $refused = false;
  $pendingForDelete = false;
  foreach($loads as $lo){
    if($lo->status == 'pending' || $lo->status == 'refused')
      $pending = true;

    if($lo->status == 'accepted')
      $pendingForDelete = true;

    if($lo->status == 'refused')
      $refused = true;
  }
@endphp
<a href="{{url('load_print_from/'.$id)}}" title="من" class="btn  btn-sm btn-success  btn-flat" target="_blank"><i class="fa fa-print"></i> </a>
<a href="{{url('load_print_to/'.$id)}}" title="إلى" class="btn  btn-sm btn-instagram  btn-flat" target="_blank"><i class="fa fa-print"></i> </a>
<a href="{{route('load.show',$id)}}" class="btn  btn-sm btn-info  btn-flat">
  @if($refused)
    <span class="text-danger text-bold">عرض</span>
  @else
    <span>عرض</span>
  @endif
</a>
@if(Auth::user()->can('update_load'))
<a @if( !$pending || $load->from_id != auth()->user()->store_id && auth()->user()->id != 1) disabled href="#" @else href="{{route('load.edit',$id)}}" @endif  class="btn btn-sm  btn-warning  btn-flat">تعديل</a>
@endif
@if(Auth::user()->can('delete_load'))
<form action="{{route('load.destroy',$id)}}" class="inline" method="POST">
  {{csrf_field()}}
  {{method_field('DELETE')}}
  <button @if($pendingForDelete || $load->from_id != auth()->user()->store_id && auth()->user()->id != 1) disabled @endif user="submit" class="btn btn-sm confirm btn-danger  btn-flat">
    حذف
  </button>
</form>
@endif