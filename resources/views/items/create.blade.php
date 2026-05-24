@extends('layout.app')
@section('title','الاصناف')
@section('sub-title','اضافة')
@section('content')
    <div class="row">
        @if($groups->count())
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"> اضافة </h3>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <form role="form" class="validate" action="{{route('items.store')}}" method="POST"
                          enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">سعر البيع</label>
                                        <input type="text"  value="{{old('price')}}" required class="form-control" name="price" id="price">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="group_id">المجموعة </label>
                                        {{Form::select('group_id',$groups->pluck('name','id'),null,['class'=>'form-control select2','id'=>'group_id','placeholder'=>'من فضلك اختر المجموعة '])}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">الاسم</label>
                                        <input type="text" value="{{old('name')}}" required class="form-control" name="name" id="name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="length">الطول</label>
                                        <input type="text" value="{{old('length')}}" required class="form-control" name="length" id="length">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="width">العرض</label>
                                        <input type="text" value="{{old('width')}}" required class="form-control" name="width" id="width">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="weight_one">الوزن</label>
                                        <input type="text" value="{{old('weight_one')}}" required class="form-control" name="weight_one" id="weight_one">
                                    </div>
                                </div>
                            </div>
                            @foreach ($metas->chunk(2) as $chunk )
                                <div class="row">
                                    @foreach ($chunk as $meta )
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="{{$meta->col_name}}">{{$meta->name}}</label>
                                                <input type="text" required class="form-control"
                                                       name="details[{{$meta->col_name}}]" id="{{$meta->col_name}}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-sm btn-success btn-flat">اضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    لايوجد مجموعات للاصناف
                </div>
            </div>
        @endif
    </div>
@stop
@push('scripts')
    <script>
        $(document).ready(function () {

            // $('.validate').validate({
            //     rules: {
            //         name: {
            //             remote: {
            //                 type: 'post',
            //                 url: '{{route('validate')}}',
            //                 data: {
            //                     field: "name",
            //                     value: function () {
            //                         return $('[name=name]').val();
            //                     },
            //                     method: 'unique:items,name',
            //                 }
            //             }
            //         }
            //     },
            //     messages: {
            //         name: {
            //             remote: 'هذا الصنف موجودة مسبقا'
            //         }
            //     }
            // })
        });
        $('#group_id').on('change', function () {
            $('#name').val($('#group_id option:selected').text());
        });
    </script>
@endpush