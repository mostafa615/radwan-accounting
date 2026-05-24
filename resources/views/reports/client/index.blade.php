@extends('layout.app')
@section('title','التقارير')
@section('sub-title','كشف حساب عميل')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <label class="col-md-3">العميل</label>
                    <label class="col-md-9">{{$client->name}}</label>
                    <label class="col-md-3">رصيد أول المدة</label>
                    <label class="col-md-9">{{$client->init}}</label>
                    <label class="col-md-3"> الرصيد الحالي</label>
                    <label class="col-md-9">{{$client->balance}}</label>
                </div>
                <div class="box-body">
                    <h1>المبيعات</h1>
                    <table class="table data-table table-bordered text-center"  >
                        <thead>
                        <tr>
                            <td>رقم الفاتورة</td>
                            <td>المدفوع</td>
                            <td>المتبقي</td>
                            <td>الخصم</td>
                            <td>الإجمالي</td>
                            <td>التاريخ</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total_order_in_cost = 0;
                        $total_order_in_rest = 0;
                        $total_order_in_total = 0;
                        $total_order_in_discount = 0;
                        ?>
                        @foreach($orders_in as $item)
                            <?php
                            $total_order_in_cost = $total_order_in_cost + $item->cost;
                            $total_order_in_rest = $total_order_in_rest + $item->rest;
                            $total_order_in_total = $total_order_in_total + $item->final_total;
                            $total_order_in_discount = $total_order_in_discount + $item->discount;
                            ?>
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->cost}}</td>
                                <td>{{$item->rest}}</td>
                                <td>{{$item->discount}}</td>
                                <td>{{$item->final_total}}</td>
                                <td>{{$item->date}}</td>
                            </tr>
                        @endforeach
                        <tr style="background-color: grey">
                            <td>الأجمالي</td>
                            <td>{{$total_order_in_cost}}</td>
                            <td>{{$total_order_in_rest}}</td>
                            <td>{{$total_order_in_discount}}</td>
                            <td>{{$total_order_in_total}}</td>
                            <td>#</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="box-body">
                    <h1>المرتجعات</h1>
                    <table class="table data-table table-bordered text-center"  >
                        <thead>
                        <tr>
                            <td>رقم الفاتورة</td>
                            <td>المدفوع</td>
                            <td>المتبقي</td>
                            <td>الخصم</td>
                            <td>الإجمالي</td>
                            <td>التاريخ</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $total_order_out_cost = 0;
                        $total_order_out_rest = 0;
                        $total_order_out_total = 0;
                        $total_order_out_discount = 0;

                        ?>
                        @foreach($orders_out as $item)
                            <?php
                            $total_order_out_cost = $total_order_out_cost + $item->cost;
                            $total_order_out_rest = $total_order_out_rest + $item->rest;
                            $total_order_out_total = $total_order_out_total + $item->final_total;
                            $total_order_out_discount = $total_order_out_discount + $item->discount;
                            ?>
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->cost}}</td>
                                <td>{{$item->rest}}</td>
                                <td>{{$item->discount}}</td>
                                <td>{{$item->final_total}}</td>
                                <td>{{$item->date}}</td>
                            </tr>
                        @endforeach
                        <tr style="background-color: grey">
                            <td>الأجمالي</td>
                            <td>{{$total_order_out_cost}}</td>
                            <td>{{$total_order_out_rest}}</td>
                            <td>{{$total_order_out_discount}}</td>
                            <td>{{$total_order_out_total}}</td>
                            <td>#</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="box-body">
                    <h1>المدفوعات</h1>
                    <table class="table data-table table-bordered text-center"  >
                        <thead>
                        <tr>
                            <td>المبلغ</td>
                            <td> التاريخ</td>
                        </tr>
                        </thead>
                        <?php
                        $total_in_paid = 0;
                        ?>
                        <tbody>
                        @foreach($accounts_in as $item)
                            <?php
                            $total_in_paid = $total_in_paid + $item->cost;
                            ?>
                            <tr>
                                <td>{{$item->cost}}</td>
                                <td>{{$item->date}}</td>
                            </tr>
                        @endforeach
                        <tr style="background-color: grey">
                            <td>الأجمالي</td>
                            <td>{{$total_in_paid}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="box-body">
                    <h1>المنصرف</h1>
                    <table class="table data-table table-bordered text-center">
                        <thead>
                        <tr>
                            <td>المبلغ</td>
                            <td> التاريخ</td>
                        </tr>
                        </thead>
                        <?php
                        $total_out_paid = 0;
                        ?>
                        <tbody>
                        @foreach($accounts_out as $item)
                            <?php
                            $total_out_paid = $total_out_paid + $item->cost;
                            ?>
                            <tr>
                                <td></td>
                                <td>{{$item->cost}}</td>
                                <td>{{$item->date}}</td>
                            </tr>
                        @endforeach
                        <tr style="background-color: grey">
                            <td>الأجمالي</td>
                            <td>{{$total_out_paid}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
<script>
    $(document).ready(function () {
        $('.table').dataTable({
            dom: 'Bfrtip',
            buttons: [
                 'excel', 'print'
            ],
            paging:false
        });
    });
</script>
@endpush
