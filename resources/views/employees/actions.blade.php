<a href="{{route('employees.show',$id)}}" class="btn  btn-sm btn-info  btn-flat">عرض</a>
<a href="{{route('employees.edit',$id)}}" class="btn btn-sm  btn-warning  btn-flat">تعديل</a>
<form action="{{route('employees.destroy',$id)}}" class="inline" method="POST">
{{csrf_field()}}
{{method_field('DELETE')}}
    <button user="submit" class="btn btn-sm confirm btn-danger  btn-flat"  > 
        حذف
    </button>
</form>

@if (App\Models\Employee::find($id)->active == 1)
<a href="#" onclick="toggleActive('{{ url("employee/deactive/") . "/" . $id }}')" class="btn  btn-sm btn-danger  btn-flat">الغاء تفعيل الموظف نهائيا من النظام</a>
@else
<a href="#" onclick="toggleActive('{{ url("employee/active/") . "/" . $id }}')" class="btn  btn-sm btn-success  btn-flat">تفعيل الموظف من جديد</a>
@endif
