<div class="form-group">
    <input type="text"
           value="{{$insurance?$insurance:'0'}}"
           required class=" validate-salary-{{$id}} form-control input-sm number ins"
           id="salary-{{$id}}-insurance"
           style="width: 70px"
           min="0"
           step="any"
           disabled readonly
           {{-- @if(Auth()->user()->id != 1)
           disabled
           @endif --}}
    >
    {{-- <span>{{$insurance ?? 0}}</span> --}}
</div>