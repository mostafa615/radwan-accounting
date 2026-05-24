<div class="pretty p-default p-curve p-thick p-smooth">
    <input type="radio" name="check-{{$id}}"
           data-is-check="true" data-name="late_with_permission"
           {{$lateWithPermission?'checked':''}}
           {{$disable}}
           data-employee="{{$id}}" class="observe"/>
    <div class="state p-primary-o">
        <label></label>
    </div>
</div>