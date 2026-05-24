@extends('layout.app')
@section('title','التقارير')
@section('sub-title','تقرير مندوب')
@section('content')
    <div class="box">
        <div class="box-body">
            <table class="table table table-bordered text-center" id="example_1">
                <thead>
                <tr>
                    <td>#</td>
                    <td>المندوب </td>
                    <td>رقم الفاتورة</td>
                    <td>الإجمالي</td>
                    <td>التاريخ </td>
                </tr>
                </thead>
                <tbody>
                <?php
                $total=0;
                ?>
                @foreach($resources  as $resource)
                    <?php
                            if ($resource->is_return == true){
                                $total=$total-$resource->total;   
                            }else {
                                    $total=$total+$resource->total;
                                }
                    ?>
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$resource->mandator->name or '-'}}</td>
                        <td>{{$resource->id or '-'}}</td>
                        <td>@if($resource->is_return == true) - @endif{{$resource->total}}</td>
                        <td>{{$resource->date}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2">الإجمالي</td>
                    <td colspan="3">{{$total}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop