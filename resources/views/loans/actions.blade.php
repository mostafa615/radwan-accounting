{{-- <a href="{{route('loans.show',$id)}}" class="btn  btn-sm btn-info  btn-flat">عرض</a> --}}

@if(App\Models\Loan::find($id)->type == "madionia")
    @if(Auth::user()->can('madionia'))
    <a href="{{route('loans.edit',$id)}}" class="btn btn-sm  btn-warning  btn-flat">تعديل</a>
    @if(App\Models\Loan::find($id)->cost > App\Models\Loan::find($id)->paid_value && App\Models\Loan::find($id)->type == "madionia" && App\Models\Loan::find($id)->paid == 0)
    <a href="#" onclick="showMadioniaModal('{{ $id }}')" class="btn btn-sm  btn-success btn-flat">تسديد</a>
    @endif
    <form action="{{route('loans.destroy',$id)}}" class="inline" method="POST">
    {{csrf_field()}}
    {{method_field('DELETE')}}
        <button user="submit" class="btn btn-sm confirm btn-danger  btn-flat"> 
            حذف
        </button>
    </form>
    @endif

@else
    @if(Auth::user()->can('madionia'))
    <a href="{{route('loans.edit',$id)}}" class="btn btn-sm  btn-warning  btn-flat">تعديل</a>
    <form action="{{route('loans.destroy',$id)}}" class="inline" method="POST">
        {{csrf_field()}}
        {{method_field('DELETE')}}
            <button user="submit" class="btn btn-sm confirm btn-danger  btn-flat"> 
                حذف
            </button>
    </form>
    @endif

@endif
<input type="hidden" id="loanRemainValue{{ $id }}" value="{{ App\Models\Loan::find($id)->cost - App\Models\Loan::find($id)->paid_value }}" >

   

