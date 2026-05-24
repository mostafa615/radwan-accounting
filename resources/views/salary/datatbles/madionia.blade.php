<div class="form-group row" style="width: 150px" >
    <div class="col-lg-4 col-md-4 col-sm-4" >
        {{ $employee_madionia }}
    </div>
    <div class=" col-lg-8 col-md-8 col-sm-8" >
        <input type="text" class="form-control input-sm number validate-salary-{{$id}} mad"  
           value="{{$loans / $month}}" 
           {{-- {{ Auth::user()->id == 1? '' : 'readonly=""' }}  --}}
           required  placeholder="{{$loans}}"
           id="salary-{{$id}}-madionia" style="width: 70px"
           readonly disabled
           {{-- @if(Auth()->user()->id != 1)
           disabled
            @endif --}}
    >
    {{-- <span>{{$loans / $month}}</span> --}}
    </div>
</div>