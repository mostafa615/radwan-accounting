<div class="form-group">
    <input type="text"  value="{{$basic}}" required 
            {{-- {{ Auth::user()->id == 1? '' : 'readonly=""' }}  --}}
           class="validate-salary-{{$id}} form-control input-sm number basicSalary"
           id="salary-{{$id}}-basic" style="width: 70px"
           readonly disabled
           {{-- @if(Auth()->user()->id != 1)
           disabled
            @endif --}}
    >
    {{-- <span>{{$basic ?? 0}}</span> --}}
</div>