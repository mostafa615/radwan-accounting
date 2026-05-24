@extends('layout.app')
@section('title','مسؤول خزنة')
@section('sub-title','معلق')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#pending_accounts_tab" data-toggle="tab" aria-expanded="true">
                            المدفوعات</a></li>
                    <li class=""><a href="#pending_transactions_tab" data-toggle="tab" aria-expanded="false"> التحويل
                            النقدي</a></li>
                    <li class=""><a href="#pending_loans_tab" data-toggle="tab" aria-expanded="false"> السلف </a></li>
                    <li class=""><a href="#pending_dailies_tab" data-toggle="tab" aria-expanded="false"> التعاملات
                            اليومية </a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="pending_accounts_tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input id="account_type_in" type="radio" checked name="account_type" value="in">
                                    <label for="account_type_in"> استلام نقدية</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input id="account_type_out" type="radio" name="account_type" value="out">
                                    <label for="account_type_out">صرف نقدية</label>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table width="100%" id="pending_accounts_table" class="table table-bordered"></table>
                        </div>
                    </div>
                    <div class="tab-pane" id="pending_transactions_tab">
                        <div class="table-responsive">
                            <table width="100%" id="pending_transactions_table" class="table table-bordered"></table>
                        </div>
                    </div>
                    <div class="tab-pane" id="pending_loans_tab">
                        <div class="table-responsive">
                            <table width="100%" id="pending_loans_table" class="table table-bordered"></table>
                        </div>
                    </div>
                    <div class="tab-pane" id="pending_dailies_tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input id="daily_type_in" type="radio" checked name="daily_type" value="in">
                                    <label for="daily_type_in"> استلام نقدية</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input id="daily_type_out" type="radio" name="daily_type" value="out">
                                    <label for="daily_type_out">صرف نقدية</label>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table width="100%" id="pending_dailies_table" class="table table-bordered"></table>
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
        $accountsTable = $('#pending_accounts_table');
        $accountType = $('[name=account_type]');
        $accountType.change(function () {
            $accountsTable.DataTable().clear().draw();
        })
        $accountsTable.DataTable({
            dom: 'Bfrtip',
            paging: false,
            language: {
                url: '{{url('/vendor/datatables/arabic.json')}}'
            },
            processing: true,
            serverSide: true,
            ajax: {
                type: 'POST',
                url: '{{route('pending-pays.accounts-datatable')}}',
                data: function (data) {
                    data.account_type = $accountType.filter(':checked').val()
                }
            },
            columns: [
                {data: 'id', name: 'accounts.id', title: 'رقم السند'},
                {data: 'date', name: 'date', title: 'التاريخ'},
                {data: 'owner', name: 'owner', title: 'المسؤول'},
                {data: 'name', name: 'reposites.name', title: 'الخزنة'},
                {data: 'cost', name: 'accounts.cost', title: 'المدفوع'},
                {data: 'action', name: 'action', title: 'عمليات'},
            ],
            buttons: ['reset', 'reload']
        });
        $(document).on('click', '.detect-account-status', function () {
            $(this).attr('disabled', 'true');
            route = $(this).data('route');
            $.ajax({
                url: route,
                type: 'PUT',
                data: {
                    status: $(this).data('status')
                }, success: function (data) {
                    console.log(data);
                    if (data.done) {
                        $accountsTable.DataTable().clear().draw();
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
                            message: 'هذه الكمية لم تعد موجودة بالخزنة',
                        });
                    }
                }
            })
        })
    </script>
@endpush
@push('scripts')
    <script>
        $transactionsTable = $('#pending_transactions_table');
        $transactionsTable.DataTable({
            dom: 'Bfrtip',
            paging: false,
            language: {
                url: '{{url('/vendor/datatables/arabic.json')}}'
            },
            processing: true,
            serverSide: true,
            ajax: {
                type: 'POST',
                url: '{{route('pending-pays.transactions-datatable')}}',
            },
            columns: [
                {data: 'id', name: 'transactions.id', title: 'الكود'},
                {data: 'date', name: 'date', title: 'التاريخ'},
                {data: 'user', name: 'users.name', title: 'المستخدم'},
                {data: 'name', name: 'reposites.name', title: 'الخزنة'},
                {data: 'cost', name: 'accounts.cost', title: 'القيمة'},
                {data: 'notes', name: 'transactions.notes', title: 'ملاحظات'},
                {data: 'action', name: 'action', title: 'عمليات'},
            ],
            buttons: ['reset', 'reload']
        });
        $(document).on('click', '.detect-transaction-status', function () {
            $(this).attr('disabled', 'true');
            route = $(this).data('route');
            $.ajax({
                url: route,
                type: 'PUT',
                data: {
                    status: $(this).data('status')
                }, success: function (data) {
                    if (data.done) {
                        $transactionsTable.DataTable().clear().draw();
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
                            message: 'هذه الكمية لم تعد موجودة بالخزنة',
                        });
                    }
                }
            })
        })
    </script>
@endpush
@push('scripts')
    <script>
        $loansTable = $('#pending_loans_table');
        $loansTable.DataTable({
            dom: 'Bfrtip',
            paging: false,
            language: {
                url: '{{url('/vendor/datatables/arabic.json')}}'
            },
            processing: true,
            serverSide: true,
            ajax: {
                type: 'POST',
                url: '{{route('pending-pays.loans-datatable')}}',
            },
            columns: [
                {data: 'id', name: 'loans.id', title: 'الكود'},
                {data: 'employee', name: 'employees.name', title: 'الموظف'},
                {data: 'date', name: 'date', title: 'التاريخ'},
                {data: 'name', name: 'reposites.name', title: 'الخزنة'},
                {data: 'cost', name: 'cost', title: 'القيمة'},
                {data: 'notes', name: 'transactions.notes', title: 'ملاحظات'},
                {data: 'action', name: 'action', title: 'عمليات'},
            ],
            buttons: ['reset', 'reload']
        });
        $(document).on('click', '.detect-loan-status', function () {
            $(this).attr('disabled', 'true');
            route = $(this).data('route');
            $.ajax({
                url: route,
                type: 'PUT',
                data: {
                    status: $(this).data('status')
                }, success: function (data) {
                    if (data.done) {
                        $loansTable.DataTable().clear().draw();
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
                            message: 'هذه الكمية لم تعد موجودة بالخزنة',
                        });
                    }
                }
            })
        })
    </script>
@endpush
@push('scripts')
    <script>
        $dailiesTable = $('#pending_dailies_table');
        $dailyType = $('[name=daily_type]');
        $dailiesTable.DataTable({
            dom: 'Bfrtip',
            paging: false,
            language: {
                url: '{{url('/vendor/datatables/arabic.json')}}'
            },
            processing: true,
            serverSide: true,
            ajax: {
                type: 'POST',
                url: '{{route('pending-pays.dailies-datatable')}}',
                data: function (data) {
                    data.daily_type = $dailyType.filter(':checked').val()
                }
            },
            columns: [
                {data: 'id', name: 'dailies.id', title: 'الكود'},
                {data: 'date', name: 'date', title: 'التاريخ'},
                {data: 'text', name: 'trees.text', title: 'النوع'},
                {data: 'cost', name: 'cost', title: 'القيمة'},
                {data: 'notes', name: 'dailies.notes', title: 'ملاحظات'},
                {data: 'action', name: 'action', title: 'عمليات'},
            ],
            buttons: ['reset', 'reload']
        });
        $dailyType.change(function () {
            $dailiesTable.DataTable().clear().draw();
        })
        $(document).on('click', '.detect-daily-status', function () {
            route = $(this).data('route');
            $(this).attr('disabled', 'true');
            $.ajax({
                url: route,
                type: 'PUT',
                data: {
                    status: $(this).data('status')
                }, success: function (data) {
                    if (data.done) {
                        $dailiesTable.DataTable().clear().draw();
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
                            message: 'هذه الكمية لم تعد موجودة بالخزنة',
                        });
                    }
                }
            })
        })
    </script>
@endpush