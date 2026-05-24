<div class="pretty p-default p-curve p-thick p-smooth">
    <input type="radio" name="check-{{$id}}" {{$absence?'checked':''}}
    data-is-check="true" data-name="absence"
           data-employee="{{$id}}" class="observe"
            {{$disable}}
    />
    <div class="state p-danger-o">
        <label></label>
    </div>
</div>