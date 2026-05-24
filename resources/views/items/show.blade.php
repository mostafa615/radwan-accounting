@extends('layout.app')
@section('title','المخازن')
@section('sub-title','عرض')
@section('content')
<div class="row">
    <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">بيانات الصنف</h3>
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
        <div class="table-responsive">
            <table class="table table-responsive table-bordered">
                <tbody>
                        <tr>
                                <td>الكود</td>
                                <td>{{$item->code}}</td>                        
                        </tr>
                        <tr>
                                <td>الاسم</td>
                                <td>{{$item->name}}</td>                        
                        </tr>
                        <tr>
                            <td>سعر البيع</td>
                            <td>{{$item->price}}</td>                        
                      </tr>

                        <tr>
                            <td> المجموعة</td>
                            <td>{{optional($item->group)->name}}</td>                        
                      </tr>
                        @foreach ($metas as $meta )
                        <tr>
                                        <td>{{$meta->name}}</td>
                                        <td>{{$item->detail[$meta->col_name]}}</td>                        
                        </tr>
                        @endforeach
                </tbody>
            </table>
              </div>
            </div>
            <!-- /.box-body -->
         
          </div>
          <!-- /.box -->
        </div>
</div>

@stop