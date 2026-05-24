@extends('layout.app')
@section('title','التقارير')
@section('sub-title','تقرير الجرد ')
@section('content')
    <div class="row">
        <div class="box col-md-12">
            <div class="box-body">
                <table class="table table-bordered" id="example_10" >
                    <thead>
                    <tr>
                        <td>المجموعة</td>
                        <td>الصنف</td>

                        @foreach($stores as $store )
                        <td>{{$store->name}}</td>
                        @endforeach
                        <td>الاجمالي</td>
                        {{--<td>المخزن</td>--}}
                        {{--<td>الكمية</td>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($resources as $resource)
                        <tr>
                            <td  >{{optional($resource->group)->name}}</td>
                            <td  >{{$resource->name}}</td>

                            <?php
                            $total_quantity=0;
                            ?>
                            @foreach($stores as $store )
                                <?php
                                $total_quantity+=optional(App\Models\Quantity::where('ownerable_type','App\Models\Store')->where('ownerable_id',$store->id)->where('item_id',$resource->id)->first())->quantity;
                                ?>
                                <td>{{optional(App\Models\Quantity::where('ownerable_type','App\Models\Store')->where('ownerable_id',$store->id)->where('item_id',$resource->id)->first())->quantity}}</td>
                            @endforeach
                            <td>{{$total_quantity}}</td>
                            {{--</tr>--}}
                        {{--foreach($resource->quantities as $quantity)--}}
                            {{--<tr>--}}
                                {{--<td>{{optional($quantity->ownerable)->name}}</td>--}}
                                {{--<td>{{$quantity->quantity}}</td>--}}
                            {{--</tr>--}}
                        {{--endforeach--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop