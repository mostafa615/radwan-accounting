{{-- <a href="{{route('group.show',$id)}}" class="btn  btn-sm btn-info  btn-flat">عرض</a> --}}
<form action="{{route('transactions.destroy',$id)}}" class="inline" method="POST">
{{csrf_field()}}
{{method_field('DELETE')}}
    <button {{!$pending?'disabled':''}}  class="btn btn-sm confirm btn-danger  btn-flat"> 
        حذف
    </button>
</form>