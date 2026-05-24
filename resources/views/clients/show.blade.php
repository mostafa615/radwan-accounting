@extends('layout.app')
@section('title','العملاء')
@section('sub-title','عرض')
@section('content')
<div class="row">
    <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">بيانات العميل</h3>
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
        <div class="table-responsive">
            <table class="table table-responsive table-bordered">
                <tbody>
                        <tr>
                                <td>الاسم</td>
                                <td>{{$client->name}}</td>                        
                        </tr>
                        <tr>
                                        <td>1 تليفون</td>
                                        <td>{{$client->phone_1}}</td>                        
                        </tr>
                        <tr>
                                <td>2 تليفون</td>
                                <td>{{$client->phone_2}}</td>                        
                        </tr>

                        <tr>
                                <td>تليفون 3</td>
                                <td>{{$client->phone_3}}</td>                        
                        </tr>

                        <tr>
                                <td>واتس</td>
                                <td>{{$client->whatsapp}}</td>                        
                        </tr>

                        <tr>
                                <td>ملاحظات</td>
                                <td>{{$client->notes}}</td>                        
                        </tr>

                        <tr>
                                        <td>العنوان</td>
                                        <td>{{$client->address}}</td>                        
                        </tr>

                        <tr>
                                        <td>المحافظة</td>
                                        <td>{{optional($client->country)->name}}</td>                        
                        </tr>

                        <tr>
                                <td>رصيد اول المدة</td>
                                <td><?php echo number_format((float)$client->init, 2) ?></td>                        
                        </tr>

                         <tr>
                                <td> الرصيد الحالي </td>
                                <td><?php echo number_format((float)$client->balance, 2) ?></td>                        
                        </tr>
                        <tr>
                                        <td>صورة البطاقة 1 </td>
                                        <td>
                                                <a href="{{ $client->id_image_1 }}" class="fancybox" title="{{ $client->name }}">
                                                        <img src="{{ $client->id_image_1 }}" class="img-thumbnail" width="100px" height="100px">
                                                </a>
                                        </td>
                        </tr>

                        <tr>
                                        <td>صورة البطاقة 2</td>
                                        <td>
                                                <a href="{{ $client->id_image_2 }}" class="fancybox" title="{{ $client->name }}">
                                                        <img src="{{ $client->id_image_2 }}" class="img-thumbnail" width="100px" height="100px">
                                                </a>
                                        </td>
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