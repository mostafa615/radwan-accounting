<div class="pretty p-default p-curve p-thick p-smooth">
    <input type="radio" name="check-leave-{{$id}}"
           data-is-check="true" data-name="leave_with_permission"
           {{$leave_with_permission == 1?'checked':''}}
           {{$disable}}
           data-employee="{{$id}}" class="observe"/>
    <div class="state p-primary-o">
        <label></label>
    </div>
</div>