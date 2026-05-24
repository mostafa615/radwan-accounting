@extends('layout.app')
@section('title','التقارير')
@section('sub-title','كارت صنف')
@push('styles')
<style>
    
</style>
@endpush
@section('content')
    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
            {{Form::open(['url'=>'update_quantities','method'=>'POST'])}}
            <table class="table table-bordered" id="example2">
                <thead>
                <tr>
                    <td>المخزن</td>
                    <td>الصنف</td>
                    <td>الرصيد الافتتاحي </td>
                    <td>الكمية</td>
                    <td>وزن استاندرد</td>
                    <td>السعر</td>
                    <td>المخزن</td>
                    <td>الصنف</td>
                    <td>الرصيد الافتتاحي </td>
                    <td>الكمية</td>
                    <td>وزن استاندرد</td>
                    <td>السعر</td>
                </tr>
                </thead>
                <tbody>
                @foreach(array_chunk($resources->toArray(),2) as $items)
                    <tr>
                        @foreach($items  as $resource)
                            <td>{{$resource['ownerable']['name']}}</td>
                            <td>{{$resource['item']['name']}}</td>
                            <td><label class="visible-print">{{$resource['init']}}</label>
                                <input type="number" name="init[]" style="width: 100px" class="form-control text-center"
                                       value="{{$resource['init']}}" required min="0" {{ Auth::user()->id != 1? 'readonly disabled' : '' }}></td>
                            <td>
                                <label class="visible-print">{{$resource['quantity']}}</label>
                                <input type="number" name="quantity[]" style="width: 100px" class="form-control text-center"
                                       value="{{$resource['quantity']}}" required step="any" min="0" {{ Auth::user()->id != 1? 'readonly disabled' : '' }}>
                                <input type="hidden" name="id[]" value="{{$resource['id']}}">
                            </td>
                            <td>
                                <input type="hidden" name="item_id[]" value="{{$resource['item_id']}}">
                                <label class="visible-print">{{$resource['item']['standard_weight']}}</label>
                                <input type="number" name="standard_weight[]" style="width: 100px" class="form-control text-center"
                                    value="{{$resource['item']['standard_weight']}}" required min="0" step="0.01" readonly disabled>
                            </td>
                            <td>
                                <label class="visible-print">{{$resource['item']['price']}}</label>
                                <input type="number" name="price[]" style="width: 100px" class="form-control text-center"
                                       value="{{$resource['item']['price']}}" required min="0" readonly disabled>
                            </td>
                        @if(count($items) <2)
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-success col-md-12">تعديل الكميات</button>
            {{Form::close()}}
            </div>
        </div>
    </div>
@stop
@push('scripts')
<script>
      $("#example2").DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            paging:false
        });
</script>
@endpush