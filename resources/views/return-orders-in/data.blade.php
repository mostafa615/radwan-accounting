
                        <table class="table table-bordered data-tables"  >
                           <thead>
                                <tr>
                                <th>#</th>
                                <th>رقم الفاتورة</th>
                                <th>العميل</th>
                                <th>هاتف العميل</th>
                                <th>عرض</th>
                                <th>الاجمالي</th>
                                <th>الخصم</th>
                                <th>المدفوع</th>
                                <th>المتبقي</th>
                                <th>الإعدادات</th>
                            </tr>
                            <?php
                            $counter = 1;
                            ?>
                           </thead>
                           <tbody>
                                @foreach($resources as $resource)
                                <tr>
                                    <td>{{$counter}}</td>
                                    <td>{{$resource->id or '-'}}</td>
                                    <td>{{$resource->ownerable->name or '-'}}</td>
                                    <td>{{$resource->ownerable->phone_1 or '-'}}</td>
                                    <td>
                                        <button type="button" class="btn btn-info"  onclick="quickview('{{ $resource->id }}')"
                                                data-target="#invoice_{{$resource->id}}"><i
                                                    class="fa fa-television"></i></button>
                                        <!-- Modal -->
                                    </td>
                                    <td>{{$resource->total}}</td>
                                    <td>{{$resource->discount}}</td>
                                    <td>{{$resource->cost}}</td>
                                    <td>{{$resource->total - $resource->cost -$resource->discount}}</td>
                                    <td>
                                        <a href="{{url('return-orders-in/'.$resource->id)}}"
                                           class="btn btn-info" target="_blank"><i
                                                    class="fa fa-print"></i></a>
                                        @if(Auth::user()->can('edit_order'))
                                            <a href="{{url('return-orders-in/'.$resource->id.'/edit')}}"
                                               class="btn btn-warning"><i
                                                        class="fa fa-edit"></i></a>
                                        @endif
                                        @if(Auth::user()->can('delete_order'))
                                            <button class="btn btn-danger" data-toggle="modal"
                                                    data-target="#delete_{{$resource->id}}"><i
                                                        class="fa fa-trash-o"></i>
                                            </button>
                                        @endif
                                        <div id="delete_{{$resource->id}}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                        <h4 class="modal-title">تأكيد الحذف</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>هل أنت متأكد من الحذف ؟</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        {{Form::open(['route'=>['return-orders-in.destroy',$resource->id],'method'=>'DELETE'])}}
                                                        <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">لا
                                                        </button>
                                                        <button type="submit" class="btn btn-danger">نعم</button>
                                                        {{Form::close()}}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                $counter++
                                ?>
                            @endforeach
                           </tbody>
                        </table>