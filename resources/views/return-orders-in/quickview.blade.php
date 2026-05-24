 
                                            <div class="modal-dialog ">

                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close"   onclick="$('.quickview').hide();"  >
                                                            &times;
                                                        </button>
                                                        <h4 class="modal-title">الفاتورة</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="box">
                                                            <div class="box-header">
                                                                <table class="table table-responsive table-bordered">
                                                                    <tr>
                                                                        <td>رقم الفاتورة</td>
                                                                        <td>{{$resource->id}}</td>
                                                                        <td>التاريخ</td>
                                                                        <td>{{$resource->created_at}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>العميل</td>
                                                                        <td>{{$resource->ownerable->name or '-'}}</td>
                                                                        <td>الموظف</td>
                                                                        <td>{{$resource->user->name or '-'}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>الخزنة</td>
                                                                        <td>{{$resource->reposite->name or '-'}}</td>
                                                                        <td></td>
                                                                        <td></td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td>المدفوع</td>
                                                                        <td>{{$resource->cost or '-'}}</td>
                                                                        <td>المتبقي</td>
                                                                        <td>{{$resource->total - $resource->cost -$resource->discount}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>الخصم</td>
                                                                        <td>{{$resource->discount or '-'}}</td>
                                                                        <td> الإجمالي</td>
                                                                        <td>{{$resource->total or '-'}}</td>
                                                                    </tr>
                                                                </table>
                                                            </div> 
                                                            <div class="box-body">
                                                                <table class="table table-responsive table-bordered">
                                                                    <tr>
                                                                        <td>الصنف</td>
                                                                        <td>المخزن</td>
                                                                        <td>الكمية</td>
                                                                        <td>السعر</td>
                                                                        <td>الخصم</td>
                                                                        <td>الإجمالي</td>
                                                                    </tr>
                                                                    <?php
                                                                    $sub_total = 0;
                                                                    $sub_total_quantity = 0;
                                                                    $sub_total_price = 0;
                                                                    $sub_total_discount = 0;
                                                                    ?> 
                                                                        @foreach($resource->orderDetails()->get() as $order_detail)
                                                                            <?php
                                                                            $sub_total_quantity = $sub_total_quantity + $order_detail->quantity;
                                                                            $sub_total_price = $sub_total_price + $order_detail->unite_price;
                                                                            $sub_total_discount = $sub_total_discount + $order_detail->discount;
                                                                            $sub_total = $sub_total + (($order_detail->unite_price * $order_detail->quantity) - $order_detail->discount);
                                                                            ?>
                                                                            <tr>
                                                                            <td>{{ $order_detail->getItem()->name }}</td>
                                                                            <td>{{$order_detail->getStore()->name }}</td>
                                                                            <td>{{$order_detail->quantity}}</td>
                                                                            <td>{{$order_detail->unite_price}}</td>
                                                                            <td>{{$order_detail->discount}}</td>
                                                                            <td>{{($order_detail->unite_price*$order_detail->quantity)-$order_detail->discount}}</td>
                                                                            </tr>
                                                                        @endforeach 
                                                                    <tr>
                                                                        <td colspan="2">الإجمالي</td>
                                                                        <td>{{$sub_total_quantity}}</td>
                                                                        <td>{{$sub_total_price}}</td>
                                                                        <td>{{$sub_total_discount}}</td>
                                                                        <td>{{$sub_total}}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" onclick="$('.quickview').hide();" >إغلاق
                                                        </button>
                                                    </div>
                                                </div>
                                            </div> 