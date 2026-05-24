@extends('layout.app')
@section('title','التقارير')
@section('sub-title','التحميل بين المخازن')
@section('content')
<div class="box">
    <div class="box-body">
        <table class="table table table-bordered text-center" id="example_1">
            <thead>
            <tr>
                <td>#</td>
                <td>من مخزن </td>
                <td>الي مخزن </td>
                <td>التاريخ </td>
            </tr>
            </thead>
            <tbody>
            @foreach($resources  as $resource)
                <tr>
                    <td>
<a target="_blank" href="{{url('load_print_to/'.$resource->id)}}">
                            {{$resource->id}}
                        </a>
                    </td>
                    <td>{{$resource->from->name or '-'}}</td>
                    <td>{{$resource->to->name or '-'}}</td>
                    <td>{{$resource->date}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop