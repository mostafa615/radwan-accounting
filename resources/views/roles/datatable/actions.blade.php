<a href="{{route('roles.edit',$id)}}" class="btn  btn-sm btn-warning  btn-flat">تعديل</a>
<form class="inline" action="{{route('roles.destroy',$id)}}" method="POST">
{{csrf_field()}}
{{method_field('DELETE')}}
    <button role="submit" class="btn btn-sm confirm btn-danger  btn-flat"> 
        حذف
    </button>
</form>