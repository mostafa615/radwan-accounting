{{-- <a href="{{route('jobs.show',$id)}}" class="btn  btn-sm btn-info  btn-flat">عرض</a> --}}
<a href="{{route('jobs.edit',$id)}}" class="btn btn-sm  btn-warning  btn-flat">تعديل</a>
<form action="{{route('jobs.destroy',$id)}}" class="inline" method="POST">
{{csrf_field()}}
{{method_field('DELETE')}}
    <button user="submit" class="btn btn-sm confirm btn-danger  btn-flat"> 
        حذف
    </button>
</form>