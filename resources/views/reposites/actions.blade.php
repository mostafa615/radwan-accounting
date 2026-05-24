{{-- <a href="{{route('reposites.show',$id)}}" class="btn  btn-sm btn-info  btn-flat">عرض</a> --}}

@if(Auth::user()->can('edit_reposite'))
<a href="{{route('reposites.edit',$id)}}" class="btn btn-sm  btn-warning  btn-flat">تعديل</a>
@endif
@if(Auth::user()->can('delete_reposite'))
<form action="{{route('reposites.destroy',$id)}}" class="inline" method="POST">
{{csrf_field()}}
{{method_field('DELETE')}}
    <button user="submit" class="btn btn-sm confirm btn-danger  btn-flat"> 
        حذف
    </button>
</form>
@endif
