@extends('layout.app')
@section('title','التقارير')
@section('sub-title','تقرير اليوميات')
@section('content')
    <div class="box">
        <div class="box-body">
            <table class="table table table-bordered text-center" id="example_1">
                <thead>
                <tr>
                    <td>#</td>
                    <td>النوع </td>
                    <td>المبلغ</td>
                    <td>التاريخ</td>
                    <td>الفرع</td>
                </tr>
                </thead>
                <tbody>
                @foreach($resources  as $resource)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{\App\Models\Tree::where('id',$resource->tree_id)->first()->text}}</td>
                        <td>{{$resource->cost}}</td>
                        <td>{{$resource->date}}</td>
                        <td>{{\App\Models\Branch::where('id',$resource->branch_id)->first()->name}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2">الإجمالي</td>
                    <td colspan="3">{{$resources->sum('cost')}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@stop