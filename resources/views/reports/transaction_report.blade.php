@extends('layout.app')
@section('title','التقارير')
@section('sub-title','التحويلات بين الخزن')
@section('content')
    <div class="box">
        <div class="box-body">
            <table class="table table table-bordered text-center" id="example_1">
                <thead>
                <tr>
                    <td>#</td>
                    <td>من خزنة </td>
                    <td>الي خزنة </td>
                    <td>المبلغ </td>
                    <td>التاريخ </td>
                </tr>
                </thead>
                <tbody>
                @foreach($resources  as $resource)
                    <tr>
                        <td>
                                {{$resource->id}}
                        </td>
                        <td>{{$resource->from->name or '-'}}</td>
                        <td>{{$resource->to->name or '-'}}</td>
                        <td>{{$resource->cost}}</td>
                        <td>{{$resource->date}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop