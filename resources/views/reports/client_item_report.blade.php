@extends('layout.app')
@section('title','التقارير')
@section('sub-title',' أصناف عميل')
@section('content')
    <div class="box">
        <div class="box-body">

            <table class="table table table-bordered text-center" id="example_1">
                <thead>
                <tr>
                    <th>#</th>
                    <th>الصنف</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>رقم الفاتورة</th>
                    <th>تاريخ الفاتورة</th>
                </tr>
                </thead>
                <tbody>
                @foreach($resources as $resource)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{optional($resource->item)->name}}</td>
                        <td>{{$resource->quantity}}</td>
                        <td>{{$resource->unite_price}}</td>
                        <td>
                            <a href="{{url('orders-in/'.$resource->order_id)}}" target="_blank">
                                {{$resource->order_id}}
                            </a>
                        </td>
                        <td>{{optional($resource->order)->date}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
@push('scripts')
@endpush