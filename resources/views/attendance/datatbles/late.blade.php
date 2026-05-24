<div class="pretty p-default p-curve p-thick p-smooth">
    <input type="radio" name="check-{{$id}}"
           {{$late==1?'checked':''}} data-is-check="true"
           data-name="late" data-employee="{{$id}}"
           class="observe"
            {{$disable}}
    />
    <div class="state p-info-o">
        <label></label>
    </div>
</div>