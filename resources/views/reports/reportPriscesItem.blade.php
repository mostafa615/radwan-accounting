@extends('layout.app')
@section('title','التقارير')
@section('sub-title','تسعير الصنف')
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
                    
                    <td>الصنف</td>
                    
                    <td>الكمية</td>
                    
                    <td>وزن استاندرد</td>
                    
                    <td>السعر</td>
                    
                    <td>الصنف</td>
                    
                    <td>الكمية</td>
                    
                    <td>وزن استاندرد</td>
                    
                    <td>السعر</td>
                </tr>
                </thead>
                <tbody>
                @foreach(array_chunk($resources->toArray(),2) as $items)
                    <tr>
                        @foreach($items  as $resource)
                            @php 
                                
                                $qun = App\Models\Quantity::where("item_id", $resource['id'])->where('ownerable_type', 'App\Models\Store')->sum("quantity");
                            
                            @endphp
                            <td>{{$resource['name']}}</td>
                            
                            
                            <td>
                                
                                <label class="visible-print">{{ $qun }}</label>
                                <input type="number"  style="width: 100px" class="form-control text-center"
                                    value="{{ $qun }}"  min="0" step="0"  readonly  >
                            </td>
                            <td>
                                <input type="hidden" name="item_id[]" value="{{$resource['id']}}">
                                <label class="visible-print">{{$resource['standard_weight']}}</label>
                                <input type="number" name="standard_weight[]" style="width: 100px" class="form-control text-center"
                                    value="{{$resource['standard_weight']}}" required min="0" step="0.01"  >
                            </td>
                            <td>
                                <label class="visible-print">{{$resource['price']}}</label>
                                <input type="number" name="price[]" style="width: 100px" class="form-control text-center"
                                       value="{{$resource['price']}}" required min="0"  >
                            </td>
                        @if(count($items) <2)
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