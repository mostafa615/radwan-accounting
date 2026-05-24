<div class="pretty p-default p-curve p-thick p-smooth">
    <input type="radio" name="check-{{$id}}"
           data-is-check="true" data-name="absence_with_permission"
           {{$absenceWithPermission?'checked':''}}
           data-employee="{{$id}}"
           {{$disable}}
           class="observe"
    />
    <div class="state p-warning-o">
        <label></label>
    </div>
</div>