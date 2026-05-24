{{-- @if($canBeReturned)
<a href="{{route('return-orders-out.create',$id)}}" class="btn  btn-sm btn-default  btn-flat">مرتجع</a>
@endif --}}
<a href="{{route('orders-out.show',$id)}}" class="btn  btn-sm btn-info  btn-flat">عرض</a>
{{-- <a href="{{route('orders-out.edit',$id)}}" class="btn btn-sm  btn-warning  btn-flat">تعديل</a> --}}
<form action="{{route('orders-out.destroy',$id)}}" class="inline" method="POST">
{{csrf_field()}}
{{method_field('DELETE')}}
    <button user="submit" class="btn btn-sm confirm btn-danger  btn-flat"> 
        حذف
    </button>
</form>