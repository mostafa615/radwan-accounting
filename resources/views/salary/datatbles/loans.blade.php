<div class="form-group">
    <input type="text"
           class="form-control input-sm number validate-salary-{{$id}} loans"
           value="{{$loans}}" required
            {{-- {{ Auth::user()->id == 1? '' : 'readonly=""' }}  --}}
           placeholder="{{$loans}}"
           id="salary-{{$id}}-loans"
           style="width: 70px"
           disabled readonly
           {{-- @if(Auth()->user()->id != 1)
           disabled
            @endif --}}
    >
    {{-- <span>{{$loans ?? 0}}</span> --}}
</div>