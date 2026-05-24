<div class="form-group">
    <input type="text"  
           class="validate-salary-{{$id}} form-control input-sm number tran"
           value="{{$transports}}" 
           {{-- {{ Auth::user()->id == 1? '' : 'readonly=""' }}  --}}
           placeholder="{{$transports}}"
           id="salary-{{$id}}-transports"
           style="width: 70px"
           disabled readonly
           {{-- @if(Auth()->user()->id != 1)
           disabled
            @endif --}}
    >
    {{-- <span>{{$transports ?? 0}}</span> --}}
</div>