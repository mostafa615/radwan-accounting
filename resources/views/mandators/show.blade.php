@extends('layout.app')
@section('title','المندوبين')
@section('sub-title','عرض')
@section('content')
<div class="row">
    <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">بيانات المندوب</h3>
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
        <div class="table-responsive">
            <table class="table table-responsive table-bordered">
                <tbody>
                        <tr>
                                <td>الاسم</td>
                                <td>{{$mandator->name}}</td>                        
                        </tr>
                        <tr>
                                <td>1 تليفون</td>
                                <td>{{$mandator->phone_1}}</td>                        
                        </tr>
                        <tr>
                                <td>2 تليفون</td>
                                <td>{{$mandator->phone_2}}</td>                        
                        </tr>

                    

                        <tr>
                                        <td>تليفون 3</td>
                                        <td>{{$mandator->phone_3}}</td>                        
                        </tr>

                        <tr>
                                        <td>البريد الالكتروني</td>
                                        <td>{{$mandator->email}}</td>                        
                        </tr>

                        <tr>
                                        <td>wechat</td>
                                        <td>{{$mandator->wechat}}</td>                        
                        </tr>

                        <tr>
                                        <td>سجل تجاري</td>
                                        <td>{{$mandator->commercial_record}}</td>                        
                        </tr>

                        <tr>
                                        <td>بطاقة ضريبية</td>
                                        <td>{{$mandator->tax_card}}</td>                        
                        </tr>


                        <tr>
                                <td>واتس</td>
                                <td>{{$mandator->whatsapp}}</td>                        
                        </tr>

                        <tr>
                                <td>ملاحظات</td>
                                <td>{{$mandator->notes}}</td>                        
                        </tr>

                        <tr>
                                        <td>العنوان</td>
                                        <td>{{$mandator->address}}</td>                        
                        </tr>

                        <tr>
                                        <td>المحافظة</td>
                                        <td>{{optional($mandator->country)->name}}</td>                        
                        </tr>

                        {{-- <tr>
                                <td>رصيد بداية المدة</td>
                                <td>{{$mandator->init}}</td>                        
                        </tr>

                         <tr>
                                <td>رصيد نهاية المدة</td>
                                <td>{{$mandator->balance}}</td>                        
                        </tr> --}}
                        <tr>
                                <td>صورة البطاقة 1 </td>
                                <td>
                                        <a href="{{ $mandator->id_image_1 }}" class="fancybox" title="{{ $mandator->name }}">
                                                <img src="{{ $mandator->id_image_1 }}" class="img-thumbnail" width="100px" height="100px">
                                        </a>
                                </td>
                        </tr>

                        <tr>
                                <td>صورة البطاقة 2</td>
                                <td>
                                        <a href="{{ $mandator->id_image_2 }}" class="fancybox" title="{{ $mandator->name }}">
                                                <img src="{{ $mandator->id_image_2 }}" class="img-thumbnail" width="100px" height="100px">
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