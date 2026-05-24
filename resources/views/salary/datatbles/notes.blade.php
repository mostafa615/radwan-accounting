<div class="form-group">  
    <textarea  {{ Auth::user()->id == 1? '' : 'readonly=""' }} 
           class=" form-control input-sm  notes"
           id="salary-{{$id}}-notes" style="width: 150px"
           @if(Auth()->user()->id != 1)
           disabled
            @endif>{{$notes}}</textarea>
</div>