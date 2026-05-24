{{-- <a href="{{route('group.show',$id)}}" class="btn  btn-sm btn-info  btn-flat">عرض</a> --}}
@if(Auth::user()->id == 1 || Auth::user()->can('edit_groups'))
<a href="{{route('group.edit',$id)}}" class="btn btn-sm  btn-warning  btn-flat">تعديل</a>
@endif

@if(Auth::user()->id == 1 || Auth::user()->can('delete_groups'))
<form action="{{route('group.destroy',$id)}}" class="inline" method="POST">
{{csrf_field()}}
{{method_field('DELETE')}}
    <button user="submit" class="btn btn-sm confirm btn-danger  btn-flat"> 
        حذف
    </button>
</form>
@endif

@if(Auth::user()->id == 1 || Auth::user()->can('edit_groups'))
<a href="{{ url('/group/active') }}/{{ $id }}?active=0" class="btn btn-sm  btn-danger  btn-flat">الغاء التفعيل</a>
@endif 