@extends('layout.app')
@section('title','التقارير')
@section('sub-title','كشف حساب العملاء')
@push('styles')
    <style>
        #report-export-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        #report-export-buttons .report-export-btn {
            min-width: 150px;
            justify-content: center;
            display: inline-flex;
            align-items: center;
        }

        #report-export-buttons .report-export-btn i {
            margin-left: 6px;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#load-tab" data-toggle="tab" aria-expanded="true">العملاء</a></li>
                    {{--<li class="pull-left">--}}
                        {{--<button data-toggle="modal" data-target="#modal" class="btn btn-success btn-flat btn-sm">--}}
                            {{--<i class="fa fa-cog fa-spin"></i>--}}
                        {{--</button>--}}
                    {{--</li>--}}
                </ul>
                {{-- <div class="box box-primary">
                    <div class="box-header with-border">
                        <form action="{{route('reports.all-clients.index')}}" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="client_id" class="form-control select2-js client_id" id="clients">
                                        <option value="">العملاء</option>
                                        @foreach ($clients as $client)
                                            <option value="{{$client->id}}" {{old('client_id') == $client->id ? 'selected' : ''}}>{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>بحث</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> --}}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="client-search">البحث عن العميل</label>
                                    <input type="text" class="form-control" id="client-search" placeholder="اكتب اسم العميل للبحث...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="phone-search">البحث برقم الهاتف</label>
                                    <input type="text" class="form-control" id="phone-search" placeholder="اكتب رقم الهاتف للبحث...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount-search">البحث بالمبلغ</label>
                                    <input type="text" class="form-control" id="amount-search" placeholder="اكتب المبلغ للبحث...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="branch-search">البحث بالفرع</label>
                                    <select class="form-control" id="branch-search">
                                        <option value="">جميع الفروع</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date-search">البحث بالتاريخ</label>
                                    <input type="text" class="form-control date" id="date-search" placeholder="اختر التاريخ للبحث..." autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane  active" id="load-tab">
                        <div class="table-responsive">
                            <table width="100%"  class="table table-bordered" id="clients-report-table">
                                <thead>
                                <tr>
                                    <td>الاسم</td>
                                    <td>رقم الهاتف</td>
                                    <td>رصيد نهاية المدة</td>
                                    <td>رقم اخر فاتوره</td>
                                    <td>الفرع</td>
                                    <td>التاريخ</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $resources as $resource)
                                    <tr>
                                        <td>{{$resource->name}}</td>
                                        <td>{{ $resource->phone_1 ?? '-' }}</td>
                                        <td>{{ number_format((float)$resource->balance, 2) }}</td>
                                        <td>
                                            @if($resource->latest_order_id)
                                                <a href="{{ url('/orders-in/' . $resource->latest_order_id) }}" target="_blank">
                                                    {{ $resource->latest_order_id }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td data-branch="{{ $resource->latest_order_branch_name ?? '' }}">
                                            {{ $resource->latest_order_branch_name ?? '-' }}
                                        </td>
                                        <td data-date="{{ $resource->latest_order_date ?? '' }}">
                                            {{ $resource->latest_order_date_display ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="modalLabel">كشف حساب العملاء</h4>
                        </div>
                        <form class="validate">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="from">من</label>
                                            <input required type="text" class="form-control date" name="from" id="from"
                                                   autocomplete="off"
                                                   value="{{Carbon\Carbon::now()->subDay(180)->format('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="to">الي</label>
                                            <input required type="text" class="form-control date" name="to" id="to"
                                                   autocomplete="off" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
                                        </div>
                                    </div>

                                </div>
                                {{--  .row  --}}
                            </div>
                            {{--  .modal-body  --}}
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-sm btn-flat" data-dismiss="modal">
                                    الغاء
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm btn-flat">موافق</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>


    </div>
@stop
@push('scripts')
    {{--  <script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>  --}}

    <script>
        $form = $('.validate')
        $modal = $('#modal')
        $type = $('#type')
        $from = $('#from')
        $to = $('#to')

        from = null;
        to = null;

        var reportTable = null;

        $(document).ready(function () {

            $form.validate();

            $form.submit(function (e) {
                e.preventDefault();
                if ($form.valid()) {
                    $modal.modal('hide');
                    from = $from.val();
                    to = $to.val();
                    type = $type.val();

                    $('.table').each(function (index, item) {
                        if ($.fn.DataTable.isDataTable(item) && item.id !== 'clients-report-table') {
                            $(item).DataTable().clear().draw()
                        }
                    })
                }
            })

            reportTable = $('#clients-report-table').DataTable({
                sTableId: 'clients-report-table',
                dom: 'Bfrtip',
                paging: false,
                info: false,
                searching: false,
                language: {
                    url: '{{url('/vendor/datatables/arabic.json')}}'
                },
                buttons: [
                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-success btn-flat btn-sm report-export-btn',
                        text: '<i class="fa fa-file-excel-o"></i> تحميل Excel',
                        title: 'كشف حساب العملاء'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-primary btn-flat btn-sm report-export-btn',
                        text: '<i class="fa fa-print"></i> طباعة',
                        title: 'كشف حساب العملاء',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ],
                columnDefs: [
                    {targets: '_all', orderable: false}
                ]
            });

            reportTable.buttons().container().appendTo('#report-export-buttons');

            // Real-time search functionality for name, phone, amount, branch, and date
            function filterTable() {
                var nameSearch = $('#client-search').val().toLowerCase();
                var phoneSearch = $('#phone-search').val();
                var amountSearch = $('#amount-search').val();
                var branchSearch = $('#branch-search').val();
                var dateSearch = $('#date-search').val();
                var table = $('#clients-report-table tbody');
                
                table.find('tr').each(function() {
                    var $row = $(this);
                    var clientName = $row.find('td:first').text().toLowerCase();
                    var phoneText = $row.find('td:nth-child(2)').text();
                    var balanceText = $row.find('td:nth-child(3)').text();
                    var branchText = $row.find('td[data-branch]').attr('data-branch') || '';
                    var dateText = $row.find('td[data-date]').attr('data-date') || '';
                    
                    // Remove commas from balance text for comparison
                    var balanceTextClean = balanceText.replace(/,/g, '');
                    
                    // Check name filter
                    var nameMatch = nameSearch === '' || clientName.indexOf(nameSearch) !== -1;
                    
                    // Check phone filter - match if phone contains the search value
                    var phoneMatch = phoneSearch === '' || phoneText.indexOf(phoneSearch) !== -1;
                    
                    // Check amount filter - match if amount contains the search value
                    var amountMatch = amountSearch === '' || balanceTextClean.indexOf(amountSearch) !== -1;
                    
                    // Check branch filter
                    var branchMatch = branchSearch === '' || branchText === branchSearch;
                    
                    // Check date filter - match if date exactly matches
                    var dateMatch = dateSearch === '' || dateText === dateSearch;
                    
                    // Show row if all filters match
                    if (nameMatch && phoneMatch && amountMatch && branchMatch && dateMatch) {
                        $row.show();
                    } else {
                        $row.hide();
                    }
                });
            }

            $('#client-search').on('keyup', filterTable);
            $('#phone-search').on('keyup', filterTable);
            $('#amount-search').on('keyup', filterTable);
            $('#branch-search').on('change', filterTable);
            $('#date-search').on('changeDate', filterTable);
            $('#date-search').on('change', filterTable);


        })


    </script>
@endpush