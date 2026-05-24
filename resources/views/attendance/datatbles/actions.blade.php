
<!-- @if (auth()->user()->id != 100000) -->
    <button data-id="{{$attendance}}"
    style="margin-top: -3px;"
    class="btn btn-primary btn-sm btn-flat destroy"
    {{!$attendance?'disabled':''}}
    {{$disable}}
    >
    <i class="fa  fa-refresh fa-spin"></i>
    </button>
<!-- @else

@endif -->
