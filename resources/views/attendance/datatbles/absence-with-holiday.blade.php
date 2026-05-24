<div class="pretty p-default p-curve p-thick p-smooth">
    
    <input type="radio"   name="check-{{$id}}"
           data-is-check="true" data-name="absence_with_holiday"
           {{$absenceWithHoliday?'checked':''}}
           data-employee="{{$id}}"
           {{$disable}}
           class="observe" style="display: inline-block"/>
    
    <span style="display: inline-block">{{$attendYearCount ?? 0}}</span>
    <div class="state p-warning-o"  style="display: inline-block">
        <label></label>
    </div>
</div>