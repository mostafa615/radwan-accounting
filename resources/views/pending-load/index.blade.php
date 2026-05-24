@extends('layout.app')
@section('title','طلبات التحميل')
@section('sub-title','الرئيسية')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <!-- <li @if ($type != 'load') class="active" @endif>
                        <a href="{{url('pending-load?type=order_load_type_in_list')}}">
                            طلبات
                            التحميل من الفواتير</a>
                    </li> -->
                    <li @if ($type == 'load') class="active" @endif><a href="{{url('pending-load?type=load')}}"> طلبات
                            التحميل بين المخازن</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane @if ($type == 'order_load_type_in_list' || $type == 'order_load_type_out_list' ) active @endif"
                         id="pending_order_loads_tab_n">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li @if ($type == 'order_load_type_in_list')class="active" @endif >
                                    <a href="{{url('pending-load?type=order_load_type_in_list')}}">
                                        صادر
                                    </a>

                                    {{--<a href="#order_load_type_in_list" data-toggle="tab" aria-expanded="true">--}}
                                    {{--صادر--}}
                                    {{--</a>--}}
                                </li>
                                <li @if ($type == 'order_load_type_out_list')class="active" @endif >
                                    <a href="{{url('pending-load?type=order_load_type_out_list')}}">
                                        {{--<a href="#order_load_type_out_list" data-toggle="tab" aria-expanded="false">--}}
                                        وارد
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="order_load_type_in_list">
                                    {{Form::open(['route'=>'pending-load.orders.update','method'=>'POST'])}}
                                    <table width="100%" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <td>الصنف</td>
                                            <td>المستخدم</td>
                                            <td>رقم الفاتورة</td>
                                            <td>تاريخ الفاتورة</td>
                                            <td>الكمية</td>
                                            <td>الموافقة</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($order_load_type_in) && count($order_load_type_in) > 0)
                                            @foreach($order_load_type_in as $detail)
                                                <tr>
                                                    <td>{{optional($detail->item)->name}}</td>
                                                    <td>{{optional(optional($detail->order)->user)->name}}</td>
                                                    <td>{{optional($detail->order)->id}}</td>
                                                    <td>{{optional($detail->order)->date}}</td>
                                                    <td>{{$detail->quantity}}</td>
                                                    <td>
                                                        <input type="checkbox"
                                                               name="id[]"
                                                               value="{{$detail->id}}"
                                                               checked>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    <button type="submit"
                                            class="btn btn-success col-md-12" onclick="$(this).attr('disabled', 'true');">
                                        الموافقة
                                    </button>
                                    {{Form::close()}}
                                    <div class="clearfix"></div>
                                    <br>
                                </div>
                                <div class="tab-pane " id="order_load_type_out_list">

                                    {{Form::open(['route'=>'pending-load.orders.update','method'=>'POST'])}}
                                    <table width="100%" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <td>الصنف</td>
                                            <td>المستخدم</td>
                                            <td>رقم الفاتورة</td>
                                            <td>تاريخ الفاتورة</td>
                                            <td>الكمية</td>
                                            <td>الموافقة</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($order_load_type_out) && count($order_load_type_out) > 0)
                                            @foreach($order_load_type_out as $detail)
                                                <tr>
                                                    <td>{{optional($detail->item)->name}}</td>
                                                    <td>{{optional(optional($detail->order)->user)->name}}</td>
                                                    <td>{{optional($detail->order)->id}}</td>
                                                    <td>{{optional($detail->order)->date}}</td>
                                                    <td>{{$detail->quantity}}</td>
                                                    <td>
                                                        <input type="checkbox"
                                                               name="id[]"
                                                               value="{{$detail->id}}"
                                                               checked>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    <button type="submit"
                                            class="btn btn-success col-md-12">
                                        الموافقة
                                    </button>
                                    {{Form::close()}}
                                    <div class="clearfix"></div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane @if ($type == 'load') active @endif " id="pending_store_loads_tab_n">
                        <div class="table-responsive">
                            <table width="100%" id="pending_store_loads_table" class="table table-bordered">
                                <tr>
                                    <td>الكود</td>
                                    <td>المستخدم</td>
                                    <td>من</td>
                                    <td>التاريخ</td>
                                    <td>ملاحظات</td>
                                    <td>عمليات</td>
                                </tr>
                                @if(!empty($loads))
                                    @foreach($loads as $load)
                                        <tr>
                                            <td>{{$load->id}}</td>
                                            <td>{{optional($load->user)->name}}</td>
                                            <td>{{optional($load->from)->name}}</td>
                                            <td>{{$load->date}}</td>
                                            <td>{{$load->notes}}</td>
                                            <td>
                                                <button class="btn btn-success" data-toggle="modal"
                                                        data-target="#showLoadItemsModal{{$load->id}}">
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                                <div id="showLoadItemsModal{{$load->id}}" class="modal fade"
                                                     role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close"
                                                                        data-dismiss="modal">
                                                                    &times;
                                                                </button>
                                                                <h4 class="modal-title">العمليات</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="table-responsive">
                                                                            <table width="100%"
                                                                                   id="order-items-table"
                                                                                   class="table table-bordered">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td>الصنف</td>
                                                                                    <td>الكمية</td>
                                                                                   <td>الحالة</td>

                                                                                    <td>الموافقة</td>
                                                                                </tr>
                                                                                {{--{{dd($load->loadDetails)}}--}}
                                                                                @if(!empty($load->load_details) && count($load->load_details) > 0)
                                                                                    @foreach($load->load_details as $detail)
                                                                                        <tr>
                                                                                            <td>{{optional($detail->item)->name}}</td>
                                                                                            <td>{{$detail->quantity}}</td>
                                                                                            <td>
                                                                                                @if($detail->pending == 1)
                                                                                                  <span class="label label-warning">فى الانتظار</span>
                                                                                                @else
                                                                                                  <span class="label label-success">مقبول</span>
                                                                                                @endif
                                                                                            </td>
                                                                                            <td>
                                                                                                <a class="btn load-detect-status btn-flat btn-success btn-sm"
                                                                                                   href="{{route('pending-load.load.update',$detail->id)}}" >
                                                                                                    <i class="fa fa-check"></i>
                                                                                                </a>
                                                                                                <!--<form action="{{route('pending-loads.update', $detail->id)}}" method="post">-->
                                                                                                <!--    <button type="submit" class="btn load-detect-status btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>-->
                                                                                                <!--</form>-->
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                @endif
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        {{Form::close()}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
<!--<div class="modal-body">-->
<!--                                                                <div class="row">-->
<!--                                                                    <div class="col-sm-12">-->
<!--                                                                        <div class="table-responsive">-->
<!--                                                                            <table width="100%" id="order-items-table" class="table table-bordered">-->
<!--                                                                                <thead>-->
<!--                                                                                <tr>-->
<!--                                                                                    <td>الصنف</td>-->
<!--                                                                                    <td>الكمية</td>-->
<!--                                                                                    <td>الحالة</td>-->
<!--                                                                                    <td>الموافقة</td>-->
<!--                                                                                </tr>-->
<!--                                                                                </thead>-->
<!--                                                                                <tbody>-->
<!--                                                                                {{--{{dd($load->loadDetails)}}--}}-->
<!--                                                                                {{-- @if(!empty($load->load_details) && count($load->load_details) > 0) --}}-->
<!--                                                                                    @foreach($load->loadDetails as $detail)-->
<!--                                                                                        <tr>-->
<!--                                                                                            <td>{{optional($detail->item)->name}}</td>-->
<!--                                                                                            <td>{{$detail->quantity}}</td>-->
<!--                                                                                            <td>-->
<!--                                                                                                @if($detail->pending == 1)-->
<!--                                                                                                  <span class="label label-warning">فى الانتظار</span>-->
<!--                                                                                                @else-->
<!--                                                                                                  <span class="label label-success">مقبول</span>-->
<!--                                                                                                @endif-->
<!--                                                                                              </td>-->
<!--                                                                                            <td>-->
<!--                                                                                                {{-- <a class="btn load-detect-status btn-flat btn-success btn-sm"-->
<!--                                                                                                   href="{{route('pending-load.load.update', $detail->id)}}" >-->
<!--                                                                                                    <i class="fa fa-check"></i>-->
<!--                                                                                                </a> --}}-->
<!--                                                                                                @if($detail->pending == 1)-->
<!--                                                                                                    <form action="{{route('pending-loads.update', $detail->id)}}" method="post">-->
<!--                                                                                                        <button type="submit" class="btn load-detect-status btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>-->
<!--                                                                                                    </form>-->
<!--                                                                                                @endif-->
<!--                                                                                            </td>-->
<!--                                                                                        </tr>-->
<!--                                                                                    @endforeach-->
<!--                                                                                {{-- @endif --}}-->
<!--                                                                                </tbody>-->
<!--                                                                            </table>-->
<!--                                                                        </div>-->
<!--                                                                        {{Form::close()}}-->
<!--                                                                    </div>-->
<!--                                                                </div>-->
<!--                                                            </div>-->
                                                        </div>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="showOrderItemsModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="ModalLabel"> عمليات التحميل </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                {{Form::open(['route'=>'pending-load.orders.update','method'=>'POST'])}}
                                <div class="table-responsive">
                                    <table width="100%" id="order-items-table" class="table table-bordered">
                                    </table>
                                    <button type="submit" class="btn btn-success col-md-12">الموافقة</button>
                                </div>
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="showLoadsItemsModal" tabindex="-1" role="dialog"
             aria-labelledby="showLoadItemsModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="showLoadItemsModalLabel"> عمليات التحميل </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table width="100%" id="load-items-table" class="table table-bordered">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
@endpush
@push('scripts')
    <script>
        $showOrderItemsModal = $('#showOrderItemsModal');
        $ordersTableshowItemsTable = $('#order-items-table');
        $pendingOrderLoadsTable = $('#pending-order-loads-table')
        orderId = null;
        $orderLoadType = $('[name=order_load_type]');
        $(document).ready(function () {
            // pending store order table
            $orderLoadType.change(function () {
                console.log($orderLoadType.filter(':checked').val())
                $pendingOrderLoadsTable.DataTable().clear().draw();
            })

            $pendingOrderLoadsTable.DataTable({
                dom: 'Bfrtip',
                paging: false,
                language: {
                    url: '{{url('/vendor/datatables/arabic.json')}}'
                },
                processing: true,
                serverSide: true,
                ajax: {
                    type: 'POST',
                    url: '{{route('pending-load.orders.datatable')}}',
                    data: function (data) {
                        data.type = $orderLoadType.filter(':checked').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'orders.id', title: 'الكود'},
                    {data: 'branch', name: 'branches.name', title: 'الفرع'},
                    {data: 'user', name: 'users.name', title: 'المستخدم'},
                    {data: 'buyer', name: 'buyer', title: 'المشتري'},
                    {data: 'action', name: 'action', title: 'عمليات'},
                ],
                buttons: ['reset', 'reload']
            });

            // order
            $ordersTableshowItemsTable.DataTable({
                dom: 'Bfrtip',
                paging: false,
                language: {
                    url: '{{url('/vendor/datatables/arabic.json')}}'
                },
                processing: true,
                serverSide: true,
                ajax: {
                    type: 'POST',
                    url: '{{route('pending-load.orders.show')}}',
                    data: function (data) {
                        data.order_id = orderId
                        data.store_id = {{optional(auth()->user()->store)->id}}
                    }
                },
                columns: [
                    {data: 'item', name: 'items.name', title: 'الاسم'},
                    {data: 'quantity', name: 'order_details.quantity', title: 'الكمية'},
                    {data: 'action', name: 'action', title: 'عمليات'},
                ],
                buttons: ['reset', 'reload']
            });
            // orders
            $(document).on('click', '.show-order-items-btn', function () {
                $showOrderItemsModal.modal('show');
                orderId = $(this).data('id');
                $ordersTableshowItemsTable.DataTable().clear().draw();
            })
            // orders
            $(document).on('click', '.order-detect-status', function () {
                $(this).hide();
                route = $(this).data('route');
                $.ajax({
                    url: route,
                    type: 'PUT',
                    data: {
                        status: $(this).data('status')
                    }, success: function (data) {
                        if (data.done) {
                            $ordersTableshowItemsTable.DataTable().clear().draw();
                            $pendingOrderLoadsTable.DataTable().clear().draw();
                            iziToast.success({
                                timeout: 1000,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                                position: 'bottomLeft',
                                rtl: true,
                                message: 'تم التعديل بنجاح ',
                            });
                        } else {
                            iziToast.error({
                                timeout: 1000,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                                position: 'bottomLeft',
                                rtl: true,
                                message: 'هذه الكميات لم تعد موجودة بالمخزن لسحبها',
                            });
                        }
                    }
                })
            })
        })
    </script>
@endpush
@push('scripts')
    <script>
        $showLoadItemsModal = $('#showLoadsItemsModal');
        $loadsTableshowItemsTable = $('#load-items-table');
        $pendingStoreLoadsTable = $('#pending_store_loads_table');
        loadId = null;
        $(document).ready(function () {
            $pendingStoreLoadsTable.DataTable({
                dom: 'Bfrtip',
                paging: false,
                language: {
                    url: '{{url('/vendor/datatables/arabic.json')}}'
                },
                processing: true,
                serverSide: true,
                ajax: {
                    type: 'POST',
                    url: '{{route('pending-load.loads.datatable')}}'
                },
                columns: [
                    {data: 'id', name: 'id', title: 'الكود'},
                    {data: 'user', name: 'users.name', title: 'المستخدم'},
                    {data: 'store', name: 'stores.name', title: 'من'},
                    {data: 'date', name: 'date', title: 'التاريخ'},
                    {data: 'notes', name: 'notes', title: 'ملاحظات'},
                    {data: 'action', name: 'action', title: 'عمليات'},
                ],
                buttons: ['reset', 'reload']
            });
            // load details
            $loadsTableshowItemsTable.DataTable({
                dom: 'Bfrtip',
                paging: false,
                language: {
                    url: '{{url('/vendor/datatables/arabic.json')}}'
                },
                processing: true,
                serverSide: true,
                ajax: {
                    type: 'POST',
                    url: '{{route('pending-load.loads.show')}}',
                    data: function (data) {
                        data.load_id = loadId
                        data.store_id = {{optional(auth()->user()->store)->id}}
                         console.log("requetFrom" +  data.load_id)
                    }
                },
                columns: [
                    {data: 'item', name: 'items.name', title: 'الاسم'},
                    {data: 'quantity', name: 'load_details.quantity', title: 'الكمية'},
                    {data: 'action', name: 'action', title: 'عمليات'},
                ],
                buttons: ['reset', 'reload']
            });
           
            // loads
            $(document).on('click', '.show-load-items-btn', function () {
                $showLoadItemsModal.modal('show');
                loadId = $(this).data('id');
                $loadsTableshowItemsTable.DataTable().clear().draw();
            })
            // loads
            $(document).on('click', '.load-detect-status', function () {
                $(this).hide();
                route = $(this).data('route');
                $.ajax({
                    url: route,
                    type: 'PUT',
                    data: {
                        status: $(this).data('status')
                    }, success: function (data) {
                        if (data.done == true) {
                            $loadsTableshowItemsTable.DataTable().clear().draw();
                            $pendingStoreLoadsTable.DataTable().clear().draw();
                            iziToast.success({
                                timeout: 1000,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                                position: 'bottomLeft',
                                rtl: true,
                                message: 'تم التعديل بنجاح ',
                            });
                        } else {
                            iziToast.error({
                                timeout: 1000,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                                position: 'bottomLeft',
                                rtl: true,
                                message: 'تم حذف او تغيير الكميه ريفريش الصفحه',
                            });
                        }
                    }
                })
            })
        })
    </script>
@endpush