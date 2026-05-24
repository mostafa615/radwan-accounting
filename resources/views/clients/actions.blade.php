
@if(Auth::user()->can('payments_client'))
<a href="{{route('accounts.index',['id'=>$id,'owner'=>'client'])}}" class="btn  btn-sm btn-primary  btn-flat">الحسابات</a>
@endif
@if(Auth::user()->can('show_client'))
<a href="{{route('clients.show',$id)}}" class="btn  btn-sm btn-info  btn-flat">عرض</a>
@endif
@if(Auth::user()->can('edit_client'))
<a href="{{route('clients.edit',$id)}}" class="btn btn-sm  btn-warning  btn-flat">تعديل</a>
@endif
@if(Auth::user()->can('delete_client'))
<form action="{{route('clients.destroy',$id)}}" class="inline" method="POST">
{{csrf_field()}}
{{method_field('DELETE')}}
    <button user="submit" class="btn btn-sm confirm btn-danger  btn-flat"> 
        حذف
    </button>
</form>
@endif
