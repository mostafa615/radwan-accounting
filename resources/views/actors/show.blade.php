@extends('layout.app')
@section('title','ممثلين الموردين')
@section('sub-title','عرض')
@section('content')
<div class="row">
    <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">بيانات ممثل  المورد</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
        <div class="table-responsive">
            <table class="table table-responsive table-bordered">
                <tbody>
                        <tr>
                                <td>الاسم</td>
                                <td>{{$actor->name}}</td>                        
                        </tr>
                        <tr>
                                        <td>1 تليفون</td>
                                        <td>{{$actor->phone_1}}</td>                        
                        </tr>
                        <tr>
                                <td>2 تليفون</td>
                                <td>{{$actor->phone_2}}</td>                        
                        </tr>

                        <tr>
                                <td>تليفون 3</td>
                                <td>{{$actor->phone_3}}</td>                        
                        </tr>

                        <tr>
                                <td>واتس</td>
                                <td>{{$actor->whatsapp}}</td>                        
                        </tr>

                        <tr>
                                <td>ملاحظات</td>
                                <td>{{$actor->notes}}</td>                        
                        </tr>

                        <tr>
                                        <td>العنوان</td>
                                        <td>{{$actor->address}}</td>                        
                        </tr>

                        <tr>
                                        <td>المحافظة</td>
                                        <td>{{optional($actor->country)->name}}</td>                        
                        </tr>
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