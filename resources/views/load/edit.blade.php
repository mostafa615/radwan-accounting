@extends('layout.app')
@section('title','تحويل خامات')
@section('sub-title','تعديل')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> تعديل تحويل </h3>
                    <label>بتاريخ : </label>   {{$resource->date}}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{Form::open(['route'=>['load.update',$resource->id],'method'=>'PATCH'])}}
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <table class="table text-center ">
                        <thead>
                        <tr>
                            <th>الصنف</th>
                            <th>الكمية</th>
                            <th>حذف</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($resource->loadDetails as $detail)
                            @if($detail->status != 'accepted')
                            <tr>
                                <td>{{$detail->item->name or '-'}}</td>
                                <td>
                                    <input type="hidden" name="detail_id[]" value="{{$detail->id}}">
                                    <input type="text" name="quantity[]" required value="{{$detail->quantity}}" class="form-control text-center" >
                                </td>
                                <td><a href="{{url('delete_load_item/'.$detail->id)}}" class="btn btn-danger "><i class="fa fa-trash-o"></i> </a> </td>
                            </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                    <br>
                    <br>

                </div>
                <div class="box-footer center-block">
                    <button type="submit" class="btn btn-sm btn-success  col-md-12 ">حفظ</button>
                </div>
            </div>
        </div>
        {{Form::close()}}
    </div>
@stop
