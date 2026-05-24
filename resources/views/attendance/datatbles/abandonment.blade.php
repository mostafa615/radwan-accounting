<input type="time"
       data-name="abandonment_time"
       data-employee="{{$id}}"
       value="{{ $abandonment }}"
       data-type='abandonment'
       class="form-control abandonment observe abandonment-{{ $id }}"
        {{$disable}}
>