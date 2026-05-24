
@if(Auth::user()->can('payments_supplier'))
<a href="{{route('accounts.index',['id'=>$id,'owner'=>'supplier'])}}" class="btn  btn-sm btn-primary  btn-flat">الحسابات</a>
@endif
@if(Auth::user()->can('show_supplier'))
<a href="{{route('suppliers.show',$id)}}" class="btn  btn-sm btn-info  btn-flat">عرض</a>
@endif
@if(Auth::user()->can('edit_supplier'))
<a href="{{route('suppliers.edit',$id)}}" class="btn btn-sm  btn-warning  btn-flat">تعديل</a>
@endif
@if(Auth::user()->can('delete_supplier'))
<form action="{{route('suppliers.destroy',$id)}}" class="inline" method="POST">
{{csrf_field()}}
{{method_field('DELETE')}}
    <button user="submit" class="btn btn-sm confirm btn-danger  btn-flat"> 
        حذف
    </button>
</form>
@endif
