<a href="{{route('users.edit',$id)}}" class="btn  btn-warning btn-sm  btn-flat">تعديل</a>
<form action="{{route('users.destroy',$id)}}" class="inline" method="POST">
{{csrf_field()}}
{{method_field('DELETE')}}
    <button user="submit" class="btn btn-sm confirm btn-danger  btn-flat"> 
        حذف
    </button>
</form>