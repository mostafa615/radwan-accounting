@extends('layout.app')
@section('title','الموردين')
@section('sub-title','عرض')
@section('content')
<div class="row">
    <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">بيانات المورد</h3>
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
        <div class="table-responsive">
            <table class="table table-responsive table-bordered">
                <tbody>
                        <tr>
                                <td>الاسم</td>
                                <td>{{$supplier->name}}</td>                        
                        </tr>
                        <tr>
                                <td>1 تليفون</td>
                                <td>{{$supplier->phone_1}}</td>                        
                        </tr>
                        <tr>
                                <td>2 تليفون</td>
                                <td>{{$supplier->phone_2}}</td>                        
                        </tr>

                    

                        <tr>
                                        <td>تليفون 3</td>
                                        <td>{{$supplier->phone_3}}</td>                        
                        </tr>
{{--  --}}
                        <tr>
                                        <td>website</td>
                                        <td>{{$supplier->website}}</td>                        
                        </tr>

                        <tr>
                                        <td>البريد الالكتروني</td>
                                        <td>{{$supplier->email}}</td>                        
                        </tr>

                        <tr>
                                        <td>wechat</td>
                                        <td>{{$supplier->wechat}}</td>                        
                        </tr>

                        <tr>
                                        <td>سجل تجاري</td>
                                        <td>{{$supplier->commercial_record}}</td>                        
                        </tr>

                        <tr>
                                        <td>بطاقة ضريبية</td>
                                        <td>{{$supplier->tax_card}}</td>                        
                        </tr>

{{--  --}}

                        <tr>
                                <td>واتس</td>
                                <td>{{$supplier->whatsapp}}</td>                        
                        </tr>

                        <tr>
                                <td>ملاحظات</td>
                                <td>{{$supplier->notes}}</td>                        
                        </tr>

                        <tr>
                                        <td>العنوان</td>
                                        <td>{{$supplier->address}}</td>                        
                        </tr>

                        <tr>
                                        <td>المحافظة</td>
                                        <td>{{optional($supplier->country)->name}}</td>                        
                        </tr>

                        <tr>
                                        <td>الممثل</td>
                                        <td>{{optional($supplier->actor)->name}}</td>                        
                        </tr>

                        <tr>
                                <td>رصيد بداية المدة</td>
                                <td>{{$supplier->init}}</td>                        
                        </tr>

                         <tr>
                                <td>الرصيد الحالي </td>
                                <td>{{$supplier->balance}}</td>                        
                        </tr>
                        <tr>
                                <td>صورة البطاقة 1 </td>
                                <td>
                                        <a href="{{ $supplier->id_image_1 }}" class="fancybox" title="{{ $supplier->name }}">
                                                <img src="{{ $supplier->id_image_1 }}" class="img-thumbnail" width="100px" height="100px">
                                        </a>
                                </td>
                        </tr>

                        <tr>
                                <td>صورة البطاقة 2</td>
                                <td>
                                        <a href="{{ $supplier->id_image_2 }}" class="fancybox" title="{{ $supplier->name }}">
                                                <img src="{{ $supplier->id_image_2 }}" class="img-thumbnail" width="100px" height="100px">
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