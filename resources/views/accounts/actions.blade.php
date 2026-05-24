{{--<a href="{{route('accounts.edit',$id)}}" class="btn btn-sm  btn-warning  btn-flat"> <i class="fa fa-edit"></i>--}}
{{--</a>--}}
@if(!empty($image))
<a href="{{url($image)}}">
    <img src="{{url($image)}}" style="width: 70px;height: 70px">
</a>
@endif
@if(Auth::user()->can('delete_order'))
<form action="{{route('accounts.destroy',$id)}}" class="inline" method="POST">
    {{csrf_field()}}
    {{method_field('DELETE')}}
    <button user="submit" class="btn btn-sm confirm btn-danger  btn-flat">
        <i class="fa fa-close"></i>
    </button>
</form>
@endif