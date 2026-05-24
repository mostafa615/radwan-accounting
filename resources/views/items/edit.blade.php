@extends('layout.app')
@section('title','الاصناف')
@section('sub-title','تعديل')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> تعديل </h3>
                </div>
                <form role="form" class="validate" action="{{route('items.update',$item)}}" method="POST"
                      enctype="multipart/form-data">
                    {{csrf_field()}}
                    {{method_field('PUT')}}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">الاسم</label>
                                    <input type="text" required class="form-control" name="name" id="name"
                                           value="{{$item->name}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">سعر البيع</label>
                                    <input type="text" required class="form-control" name="price" id="price"
                                           value="{{$item->price}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="group_id">المجموعة </label>
                                    <select name="group_id" id="group_id">
                                        @foreach ($groups as  $group)
                                            <option value="{{$group->id}}" {{$item->group_id == $group->id?'selected':''}}>{{$group->name}}</option>
                                        @endforeach
                                    </select>
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
                                                   name="details[{{$meta->col_name}}]" id="{{$meta->col_name}}"
                                                   value="{{$item->detail[$meta->col_name]}}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-sm btn-success btn-flat">تعديل</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->

        </div>
    </div>
@stop
@push('scripts')
    <script>
        $(document).ready(function () {

            $('.validate').validate({
                rules: {
                    name: {
                        remote: {
                            type: 'post',
                            url: '{{route('validate')}}',
                            data: {
                                field: "name",
                                value: function () {
                                    return $('[name=name]').val();
                                },
                                method: 'unique:items,name,{{$item->id}}',
                            }
                        }
                    },
                    code: {
                        remote: {
                            type: 'post',
                            url: '{{route('validate')}}',
                            data: {
                                field: "name",
                                value: function () {
                                    return $('[name=code]').val();
                                },
                                method: 'unique:items,code,{{$item->id}}',
                            }
                        }
                    }
                },
                messages: {
                    name: {
                        remote: 'هذا الصنف موجودة مسبقا'
                    },
                    code: {
                        remote: 'هذا الصنف موجودة مسبقا'
                    }
                }
            })
        })
    </script>
@endpush