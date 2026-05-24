<div class="form-group">
        <input type="text"  value="{{$bonus?$bonus:'0'}}" required
               class="validate-salary-{{$id}} form-control input-sm number bonus"
               id="salary-{{$id}}-bonus"
               style="width: 70px"
               @if(Auth()->user()->id != 1)
               disabled
                @endif
        >
        </div>