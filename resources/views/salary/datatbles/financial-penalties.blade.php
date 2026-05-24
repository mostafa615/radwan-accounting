<div class="form-group">
        <input type="text"
               value="{{$financialPenalties?$financialPenalties:'0'}}"
               required class=" validate-salary-{{$id}} form-control input-sm number fin"
               id="salary-{{$id}}-financial-penalties"
               style="width: 70px"
               @if(Auth()->user()->id != 1)
               disabled
                @endif
        >
</div>