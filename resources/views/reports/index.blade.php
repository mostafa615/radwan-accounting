@extends('layout.app')
@section('title','التقارير')
@section('sub-title','الرئسية')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">التقارير</h3>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->can('customer_account_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="{{route('reports.client.index')}}">كشف حساب عميل</a>
                            </div>
                        @endif
                        @if(Auth::user()->can('client_account_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="{{route('reports.supplier.index')}}">كشف حساب مورد</a>
                            </div>
                        @endif
                        @if(Auth::user()->can('loading_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat" data-toggle="modal"
                                   data-target="#storeLoadModal" href="#">التحميل
                                    بين المخازن</a>
                                <div id="storeLoadModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">التفاصيل</h4>
                                            </div>
                                            {{Form::open(['route'=>'reports.load.index','method'=>'GET'])}}
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>من تاريخ</label>
                                                    {{Form::date('date_from',\Carbon\Carbon::now()->subDay(30),['class'=>'form-control '])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>الي تاريخ</label>
                                                    {{Form::date('date_to',\Carbon\Carbon::now(),['class'=>'form-control '])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>من مخزن</label>
                                                    @inject('store','App\Models\Store')
                                                    {{Form::select('stores_from[]',$store->pluck('name','id')->toArray(),null,['class'=>'form-control ','multiple'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>المخازن</label>
                                                    {{Form::select('stores_to[]',$store->pluck('name','id')->toArray(),null,['class'=>'form-control ','multiple'])}}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">عرض</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                                </button>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(Auth::user()->can('employee_working_date_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="{{route('reports.employee.index')}}">تاريخ تعيين الموظفين</a>
                            </div>
                        @endif
                        @if(Auth::user()->can('attendance_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="{{route('reports.attendance.index')}}">حضور وانصراف الموظفين</a>
                            </div>
                        @endif
                        @if(Auth::user()->can('customers_account_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="{{route('reports.all-clients.index')}}">كشف حساب العملاء</a>
                            </div>
                        @endif
                        @if(Auth::user()->can('clients_account_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="{{route('reports.all-suppliers.index')}}">كشف حساب الموردين</a>
                            </div>
                        @endif
                        @if(Auth::user()->can('employee_loans_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat" href="#" onclick="$('#modalSolf').show()" >مدفوعات الموظف للسلف من
                                    المرتب</a>
                            </div>
                        
                            <div class="modal" id="modalSolf" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modalSolf" aria-label="Close" onclick="$('#modalSolf').hide()" ><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="modalLabel">تقرير سلف الموظفين  </h4>
                                        </div>
                                        <form class="" action="{{ url('/') }}/employee/loans" method="get" >
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="from">من</label>
                                                            <input required type="text" class="form-control date" name="from" id="from">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="to">الي</label>
                                                            <input  required type="text" class="form-control date" name="to" id="to">
                                                        </div>
                                                    </div>


                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="employee_id">الموظف</label>
                                                            <select name="employee_id">
                                                                @foreach (App\Models\Employee::where('active', '1')->get() as $employee)
                                                                <option value="{{$employee->id}}">{{$employee->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div> 
                                                {{--  .row  --}}
                                            </div>
                                            {{--  .modal-body  --}}
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default btn-sm btn-flat" onclick="$('#modalSolf').hide()" data-dismiss="modalSolf">الغاء</button>
                                                <button type="submit" class="btn btn-primary btn-sm btn-flat">موافق </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(Auth::user()->can('item_card_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="#" data-toggle="modal" data-target="#itemCartModal">كارت صنف</a>
                                <!-- Modal -->
                                <div id="itemCartModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">التفاصيل</h4>
                                            </div>
                                            {{Form::open(['route'=>'reports.item-card.index','method'=>'GET'])}}
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>المجموعات</label>
                                                    @inject('group','App\Models\Group')
                                                    {{Form::select('group_id',$group->pluck('name','id')->toArray(),null,['class'=>'form-control ','id'=>'group_id','placeholder'=>'من فضلك اختر المجموعة'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>الاصناف</label>
                                                    @inject('item','App\Models\Item')
                                                    {{Form::select('items[]',[],null,['class'=>'form-control ','multiple','id'=>'item_id'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>المخازن</label>
                                                    @inject('store','App\Models\Store')
                                                    {{Form::select('stores[]',$store->pluck('name','id')->toArray(),null,['class'=>'form-control ','multiple'])}}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">عرض</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                                </button>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(Auth::user()->can('report_price_product'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="#" data-toggle="modal" data-target="#itemCartPriceModal">تسعير الصنف </a>
                                <!-- Modal -->
                                <div id="itemCartPriceModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">التفاصيل</h4>
                                            </div>
                                            {{Form::open(['route'=>'reportPriscesItem','method'=>'GET'])}}
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>المجموعات</label>
                                                    @inject('group','App\Models\Group')
                                                    {{Form::select('group_id',$group->pluck('name','id')->toArray(),null,['class'=>'form-control ','id'=>'group_id','placeholder'=>'من فضلك اختر المجموعة'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>الاصناف</label>
                                                    @inject('item','App\Models\Item')
                                                    {{Form::select('items[]',[],null,['class'=>'form-control ','multiple','id'=>'item_id'])}}
                                                </div>
                                                
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">عرض</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                                </button>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(Auth::user()->can('item_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="#" data-toggle="modal" data-target="#itemReportModal">حركات صنف</a>
                                <!-- Modal -->
                                <div id="itemReportModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">التفاصيل</h4>
                                            </div>
                                            {{Form::open(['route'=>'item_all_report','method'=>'GET'])}}
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>المجموعات</label>
                                                    @inject('group','App\Models\Group')
                                                    {{Form::select('group_id', $group->pluck('name','id')->toArray(), null, ['class'=>'form-control', 'id'=>'groups_id', 'placeholder'=>'من فضلك اختر المجموعة'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>الاصناف</label>
                                                    @inject('item','App\Models\Item')
                                                    {{Form::select('items[]', [], null, ['class'=>'form-control', 'multiple', 'id'=>'items_id'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>من تاريخ</label>
                                                    {{Form::date('date_from',\Carbon\Carbon::now()->subDay(30),['class'=>'form-control '])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>الي تاريخ</label>
                                                    {{Form::date('date_to',\Carbon\Carbon::now(),['class'=>'form-control '])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>من مخزن</label>
                                                    @inject('store','App\Models\Store')
                                                    {{Form::select('stores_id[]',$store->pluck('name','id')->toArray(),null,['class'=>'form-control','placeholder'=>'أختر المخزن'])}}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">عرض</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                                </button>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if(Auth::user()->can('item_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="#" data-toggle="modal" data-target="#itemReportModal1">حركات الصنف الجديده</a>
                                <!-- Modal -->
                                <div id="itemReportModal1" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">حركات الصنف الجديده</h4>
                                            </div>
                                            {{Form::open(['route'=>'new_item_all_report','method'=>'GET'])}}
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>المجموعات</label>
                                                    @inject('group','App\Models\Group')
                                                    {{Form::select('group_id', $group->pluck('name','id')->toArray(), null, ['class'=>'form-control', 'id'=>'groups_id_new', 'placeholder'=>'من فضلك اختر المجموعة'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>الاصناف</label>
                                                    @inject('item','App\Models\Item')
                                                    {{Form::select('items[]', [], null, ['class'=>'form-control', 'multiple', 'id'=>'items_id_new'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>من تاريخ</label>
                                                    {{Form::date('date_from',\Carbon\Carbon::now()->subDay(30),['class'=>'form-control '])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>الي تاريخ</label>
                                                    {{Form::date('date_to',\Carbon\Carbon::now(),['class'=>'form-control '])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>من مخزن</label>
                                                    @inject('store','App\Models\Store')
                                                    {{Form::select('stores_id[]',$store->pluck('name','id')->toArray(),null,['class'=>'form-control','placeholder'=>'أختر المخزن'])}}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">عرض</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                                </button>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if(Auth::user()->can('item_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="#" data-toggle="modal" data-target="#itemMovementsReportModal">تقرير حركات الصنف الجديد ماهر</a>
                                <!-- Modal -->
                                <div id="itemMovementsReportModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">تقرير حركات الصنف الجديد ماهر</h4>
                                            </div>
                                            {{Form::open(['route'=>'item_movements_report','method'=>'GET'])}}
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>المجموعات</label>
                                                    @inject('group','App\Models\Group')
                                                    {{Form::select('group_id', $group->pluck('name','id')->toArray(), null, ['class'=>'form-control', 'id'=>'groups_id_movements', 'placeholder'=>'من فضلك اختر المجموعة'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>الاصناف</label>
                                                    @inject('item','App\Models\Item')
                                                    {{Form::select('items[]', [], null, ['class'=>'form-control', 'multiple', 'id'=>'items_id_movements'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>من تاريخ</label>
                                                    {{Form::date('date_from',\Carbon\Carbon::now()->subDay(30),['class'=>'form-control '])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>الي تاريخ</label>
                                                    {{Form::date('date_to',\Carbon\Carbon::now(),['class'=>'form-control '])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>من مخزن</label>
                                                    @inject('store','App\Models\Store')
                                                    {{Form::select('stores_id[]',$store->pluck('name','id')->toArray(),null,['class'=>'form-control','placeholder'=>'أختر المخزن'])}}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">عرض</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                                </button>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if(Auth::user()->id ==1)
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="#" data-toggle="modal" data-target="#attendReportModal">تقرير حضور وغياب سنوي</a>
                                <!-- Modal -->
                                <div id="attendReportModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">التفاصيل</h4>
                                            </div>
                                            {{Form::open(['route'=>'attend_report','method'=>'GET'])}}
                                            <div class="modal-body">                                                
                                                <div class="form-group">
                                                    <label>من تاريخ</label>
                                                    <input type="date" name="date_from" class="form-control" style="position: relative" value="<?php
                                                    $dt = date("Y-m-d");
                                                    echo date( "Y-m-d", strtotime('-29 day', strtotime($dt)) ); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>إلي تاريخ</label>
                                                    <input type="date" name="date_to" class="form-control" style="position: relative" value="<?php echo date('Y-m-d'); ?>">
                                                </div>                                              
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">عرض</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                                </button>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(Auth::user()->can('mandators_report'))
                            <!--<div class="col-md-4">-->
                            <!--    <a class="btn btn-block btn-primary btn-sm btn-flat"-->
                            <!--       href="#" data-toggle="modal" data-target="#mandatorModal">المندوبين</a>-->
                                <!-- Modal -->
                            <!--    <div id="mandatorModal" class="modal fade" role="dialog">-->
                            <!--        <div class="modal-dialog">-->
                                        <!-- Modal content-->
                            <!--            <div class="modal-content">-->
                            <!--                <div class="modal-header">-->
                            <!--                    <button type="button" class="close" data-dismiss="modal">&times;-->
                            <!--                    </button>-->
                            <!--                    <h4 class="modal-title">التفاصيل</h4>-->
                            <!--                </div>-->
                            <!--                {{Form::open(['route'=>'mandator_report','method'=>'GET'])}}-->
                            <!--                <div class="modal-body">-->
                            <!--                    <div class="form-group">-->
                            <!--                        <label>المنوب</label>-->
                            <!--                        @inject('mandator','App\Models\Mandator')-->
                            <!--                        {{Form::select('mandator_id[]',$mandator->pluck('name','id')->toArray(),null,['class'=>'form-control ','multiple'])}}-->
                            <!--                    </div>-->
                            <!--                    <div class="form-group">-->
                            <!--                        <label>من تاريخ</label>-->
                            <!--                        {{Form::date('date_from',\Carbon\Carbon::now()->subDay(30),['class'=>'form-control '])}}-->
                            <!--                    </div>-->
                            <!--                    <div class="form-group">-->
                            <!--                        <label>الي تاريخ</label>-->
                            <!--                        {{Form::date('date_to',\Carbon\Carbon::now(),['class'=>'form-control '])}}-->
                            <!--                    </div>-->
                            <!--                </div>-->
                            <!--                <div class="modal-footer">-->
                            <!--                    <button type="submit" class="btn btn-success">عرض</button>-->
                            <!--                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق-->
                            <!--                    </button>-->
                            <!--                </div>-->
                            <!--                {{Form::close()}}-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
                        @endif
                        @if(Auth::user()->can('safe_account_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="#" data-toggle="modal" data-target="#safeModal">حساب الخزنة</a>
                                <!-- Modal -->
                                <div id="safeModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">التفاصيل</h4>
                                            </div>
                                            {{Form::open(['route'=>'safe_report','method'=>'GET'])}}
                                            {{Form::hidden('report_type','safe')}}
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>الخزنة</label>
                                                    @inject('reposite','App\Models\Reposite')
                                                    <?php
                                                        $repositeOptions = Auth::user()->hasRole('admin') || Auth::user()->hasRole('admin2')
                                                            ? $reposite->pluck('name', 'id')->toArray() 
                                                            : $reposite->where('branch_id', Auth::user()->branch_id)->pluck('name', 'id')->toArray();
                                                    ?>
                                                    {{Form::select('reposite_id',$repositeOptions,null,['class'=>'form-control '])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>من تاريخ</label>
                                                    {{Form::date('date_from',\Carbon\Carbon::now(),['class'=>'form-control '])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>الي تاريخ</label>
                                                    {{Form::date('date_to',\Carbon\Carbon::now(),['class'=>'form-control '])}}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">عرض</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                                </button>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(Auth::user()->can('safe_sales_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="#" data-toggle="modal" data-target="#salesModal"> حساب مبيعات الخزنه</a>
                             
                                <div id="salesModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                      
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">التفاصيل</h4>
                                            </div>
                                            {{Form::open(['route'=>'safe_report','method'=>'GET'])}}
                                            {{Form::hidden('report_type','sales')}}
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>الخزنة</label>
                                                    @inject('reposite','App\Models\Reposite')
                                                    {{Form::select('reposite_id[]',$reposite->pluck('name','id')->toArray(),null,['class'=>'form-control ','multiple'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>من تاريخ</label>
                                                    {{Form::date('date_from',\Carbon\Carbon::now()->subDay(30),['class'=>'form-control '])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>الي تاريخ</label>
                                                    {{Form::date('date_to',\Carbon\Carbon::now(),['class'=>'form-control '])}}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">عرض</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                                </button>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(Auth::user()->can('safe_transactions_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="#" data-toggle="modal" data-target="#transactionReportModal">التحويلات بين
                                    الخزن</a>
                                <!-- Modal -->
                                <div id="transactionReportModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">التفاصيل</h4>
                                            </div>
                                            {{Form::open(['route'=>'transaction_report','method'=>'GET'])}}
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>من خزنة</label>
                                                    @inject('reposite','App\Models\Reposite')
                                                    {{Form::select('from',$reposite->pluck('name','id')->toArray(),null,['class'=>'form-control ','placeholder'=>'اختر من خزنة'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>من خزنة</label>
                                                    {{Form::select('to',$reposite->pluck('name','id')->toArray(),null,['class'=>'form-control ','placeholder'=>'اختر الي خزنة'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>من تاريخ</label>
                                                    {{Form::date('date_from',\Carbon\Carbon::now()->subDay(365),['class'=>'form-control '])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>الي تاريخ</label>
                                                    {{Form::date('date_to',\Carbon\Carbon::now(),['class'=>'form-control '])}}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">عرض</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                                </button>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(Auth::user()->can('employee_log_report'))
                            <div class="col-md-4">
                                <a class="btn btn-block btn-primary btn-sm btn-flat"
                                   href="#" data-toggle="modal" data-target="#employeeReportModal">تقرير عمليات
                                    الموظفين</a>
                                <!-- Modal -->
                                <div id="employeeReportModal" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">التفاصيل</h4>
                                            </div>
                                            {{Form::open(['route'=>'employee_report','method'=>'GET'])}}
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>الموظف</label>
                                                    @inject('user','App\Models\User')
                                                    {{Form::select('user_id',$user->pluck('name','id')->toArray(),null,['class'=>'form-control ','placeholder'=>'اختر الموظف'])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>من تاريخ</label>
                                                    {{Form::date('date_from',\Carbon\Carbon::now()->subDay(365),['class'=>'form-control '])}}
                                                </div>
                                                <div class="form-group">
                                                    <label>الي تاريخ</label>
                                                    {{Form::date('date_to',\Carbon\Carbon::now(),['class'=>'form-control '])}}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">عرض</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                                </button>
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(Auth::user()->can('product_reports'))
                        <div class="col-md-4">
                            <a class="btn btn-block btn-primary btn-sm btn-flat"
                               href="#" data-toggle="modal" data-target="#inventoryModal">تقرير جرد </a>
                            <!-- Modal -->
                            <div id="inventoryModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;
                                            </button>
                                            <h4 class="modal-title">التفاصيل</h4>
                                        </div>
                                        {{Form::open(['route'=>'reports.inventory_report','method'=>'GET'])}}
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>المجموعات</label>
                                                @inject('group','App\Models\Group')
                                                {{Form::select('group_id',$group->pluck('name','id')->toArray(),null,['class'=>'form-control ','id'=>'inventory_group_id','placeholder'=>'من فضلك اختر المجموعة'])}}
                                            </div>
                                            <div class="form-group">
                                                <label>الاصناف</label>
                                                @inject('item','App\Models\Item')
                                                {{Form::select('items[]',[],null,['class'=>'form-control ','multiple','id'=>'inventory_item_id'])}}
                                            </div>
                                            <div class="form-group">
                                                <label>المخازن</label>
                                                @inject('store','App\Models\Store')
                                                {{Form::select('stores[]',$store->pluck('name','id')->toArray(),null,['class'=>'form-control ','multiple'])}}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">عرض</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                            </button>
                                        </div>
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                         @if(Auth::user()->can('daily_report'))
                        <div class="col-md-4">
                            <a class="btn btn-block btn-primary btn-sm btn-flat" data-toggle="modal"
                               data-target="#treeModal">
                                تقرير اليوميات
                            </a>
                            <div class="modal fade" id="treeModal" tabindex="-1" role="dialog"
                                 aria-labelledby="ModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="ModalLabel"> الايرادات والمصروفات</h4>
                                        </div>
                                        {{Form::open(['url'=>'daily_report','method'=>'GET'])}}
                                        {{--<input type="hidden" name="type" value="">--}}
                                        <input type="hidden" name="tree_id" value="">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="panel panel-default">
                                                        <div class="panel-body">
                                                            <div class="jstree">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <label>الفرع</label>
                                                    {{Form::select('branch_id[]',\App\Models\Branch::pluck('name','id')->toArray(),[],['class'=>'form-control', 'multiple','placeholder'=>'اختر الفرع'])}}
                                                </div>
                                                <div class="col-sm-12">
                                                    <label>
                                                        التاريخ من
                                                    </label>
                                                    {{Form::date('date_from',\Carbon\Carbon::now(),['class'=>'form-control'])}}
                                                </div>
                                                <div class="col-sm-12">
                                                    <label>
                                                        التاريخ الي
                                                    </label>
                                                    {{Form::date('date_to',\Carbon\Carbon::now(),['class'=>'form-control'])}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"> البحث</button>
                                        </div>
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                         @if(Auth::user()->can('client_item_report'))
                        <div class="col-md-4">
                            <a class="btn btn-block btn-primary btn-sm btn-flat" data-toggle="modal"
                               data-target="#clientItemsModal">
                                تقرير أصناف عميل
                            </a>
                            <div class="modal fade" id="clientItemsModal" tabindex="-1" role="dialog"
                                 aria-labelledby="ModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="ModalLabel">
                                                تقرير أصناف عميل
                                            </h4>
                                        </div>
                                        {{Form::open(['url'=>'client_item_report','method'=>'GET'])}}

                                        <div class="modal-body">
                                            {{--<div class="row">--}}
                                            <div class="form-group">
                                                <label>المجموعات</label>
                                                @inject('group','App\Models\Group')
                                                {{Form::select('group_id',$group->pluck('name','id')->toArray(),null,['class'=>'form-control ','id'=>'client_group_id','placeholder'=>'من فضلك اختر المجموعة'])}}
                                            </div>
                                            <div class="form-group">
                                                <label>الاصناف</label>
                                                @inject('item','App\Models\Item')
                                                {{Form::select('items[]',[],null,['class'=>'form-control ','multiple','id'=>'client_item_id'])}}
                                            </div>
                                            <div class="form-group">
                                                <label>العميل</label>
                                                @inject('client','App\Models\Client')
                                                {{Form::select('client_id',$client->pluck('name','id')->toArray(),null,['class'=>'form-control'])}}
                                            </div>
                                            {{--</div>--}}

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"> البحث</button>
                                        </div>
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(Auth::user()->can('client_item_report'))
                        <div class="col-md-4">
                            <a class="btn btn-block btn-primary btn-sm btn-flat" data-toggle="modal"
                               data-target="#clientSupplierModal">
                                تقرير عميل مورد
                            </a>
                            <div class="modal fade" id="clientSupplierModal" tabindex="-1" role="dialog"
                                 aria-labelledby="ModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="ModalLabel">
                                                تقرير عميل مورد
                                            </h4>
                                        </div>
                                        {{Form::open(['url'=>'supplier_client_report','method'=>'GET'])}}

                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>المورد</label>
                                                @inject('supplier','App\Models\Supplier')
                                                {{Form::select('supplier_id',$supplier->pluck('name','id')->toArray(),null,['class'=>'form-control ','placeholder'=>'من فضلك اختر المورد'])}}
                                            </div>
                                            <div class="form-group">
                                                <label>العميل</label>
                                                @inject('client','App\Models\Client')
                                                {{Form::select('client_id',$client->pluck('name','id')->toArray(),null,['class'=>'form-control','placeholder'=>'من فضلك اختر العميل '])}}
                                            </div>

                                            <div class="form-group">
                                                <label>من تاريخ</label>
                                                {{Form::date('date_from',\Carbon\Carbon::now()->subDay(365),['class'=>'form-control '])}}
                                            </div>
                                            <div class="form-group">
                                                <label>الي تاريخ</label>
                                                {{Form::date('date_to',\Carbon\Carbon::now(),['class'=>'form-control '])}}
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"> البحث</button>
                                        </div>
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(Auth::user()->can('salary_report'))
                        <div class="col-md-4">
                            <a class="btn btn-block btn-primary btn-sm btn-flat" data-toggle="modal"
                               data-target="#salariesModal">
                                تقرير المرتبات
                            </a>
                            <div class="modal fade" id="salariesModal" tabindex="-1" role="dialog"
                                 aria-labelledby="ModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="ModalLabel">
                                                تقرير المرتبات
                                            </h4>
                                        </div>
                                        {{Form::open(['url'=>'salaries_report','method'=>'GET'])}}

                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>الفرع</label>
                                                {{Form::select('branch_id',\App\Models\Branch::pluck('name','id')->toArray(),null,['class'=>'form-control','placeholder'=>'اختر الفرع','id'=>'branch-select1'])}}
                                            </div>
                                                                                        
                                            <div class="form-group">
                                                <label>الموظف</label>
                                                <select class="form-control" name="employee_id" id="employee-select1">
                                                    <option value="all">كل الموظفين فى الفرع</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label> تاريخ</label>
                                                {{Form::date('date',null,['class'=>'form-control '])}}
                                            </div>
                                            {{--<div class="form-group">--}}
                                            {{--<label>الي تاريخ</label>--}}
                                            {{--{{Form::date('date_to',\Carbon\Carbon::now(),['class'=>'form-control '])}}--}}
                                            {{--</div>--}}

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"> البحث</button>
                                        </div>
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(Auth::user()->can('salary_report'))
                        <div class="col-md-4">
                            <a class="btn btn-block btn-primary btn-sm btn-flat" data-toggle="modal"
                               data-target="#salariesprintModal">
                                تقرير طباعة المرتبات
                            </a>
                            <div class="modal fade" id="salariesprintModal" tabindex="-1" role="dialog"
                                 aria-labelledby="ModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="ModalLabel">
                                                تقرير طباعة المرتبات
                                            </h4>
                                        </div>
                                        {{Form::open(['url'=>'salaries_report_print','method'=>'GET'])}}

                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>الفرع</label>
                                                {{Form::select('branch_id',\App\Models\Branch::pluck('name','id')->toArray(),null,['class'=>'form-control','placeholder'=>'اختر الفرع','id'=>'branch-select'])}}
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>الموظف</label>
                                                <select class="form-control" name="employee_id" id="employee-select">
                                                    <option value="all">كل الموظفين فى الفرع</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label> تاريخ</label>
                                                {{Form::date('date',null,['class'=>'form-control '])}}
                                            </div>
                                            {{--<div class="form-group">--}}
                                            {{--<label>الي تاريخ</label>--}}
                                            {{--{{Form::date('date_to',\Carbon\Carbon::now(),['class'=>'form-control '])}}--}}
                                            {{--</div>--}}

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"> البحث</button>
                                        </div>
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                         @if(Auth::user()->can('achievements_report'))
                        <div class="col-md-4">
                            <a class="btn btn-block btn-primary btn-sm btn-flat" data-toggle="modal"
                               data-target="#holidaysModal">
                                تقرير الأجازات لموظف
                            </a>
                            <div class="modal fade" id="holidaysModal" tabindex="-1" role="dialog"
                                 aria-labelledby="ModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="ModalLabel">
                                                تقرير الاجازات لموظف
                                            </h4>
                                        </div>
                                        {{Form::open(['url'=>'holidays_report','method'=>'GET'])}}
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>الموظف</label>
                                                {{Form::select('employee_id',\App\Models\Employee::where('active', '1')->pluck('name','id')->toArray(),null,['class'=>'form-control ','placeholder'=>'من فضلك اختر الموظف '])}}
                                            </div>

                                            <div class="form-group">
                                                <label>التاريخ من</label>
                                                {{Form::date('date_from',null,['class'=>'form-control '])}}
                                            </div>
                                            <div class="form-group">
                                                <label> التاريخ الي </label>
                                                {{Form::date('date_to',null,['class'=>'form-control '])}}
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"> البحث</button>
                                        </div>
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(Auth::user()->can('achievements_report'))
                        <div class="col-md-4">
                            <a class="btn btn-block btn-primary btn-sm btn-flat" data-toggle="modal"
                               data-target="#holidayallsModal">
                                تقرير الاجازات كل الموظفين
                            </a>
                            <div class="modal fade" id="holidayallsModal" tabindex="-1" role="dialog"
                                 aria-labelledby="ModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="ModalLabel">
                                            تقرير الاجازات كل الموظفين
                                            </h4>
                                        </div>
                                        {{Form::open(['url'=>'holidays_all_report','method'=>'GET'])}}
                                        <div class="modal-body">
                                             <div class="form-group">
                                                <label>الفرع</label>
                                                {{Form::select('branch_id',\App\Models\Branch::where('display',1)->pluck('name','id')->toArray(),null,['class'=>'form-control ','placeholder'=>'اختر الفرع'])}}
                                            </div>
                                            <div class="form-group">
                                                <label>التاريخ من</label>
                                                {{Form::date('date_from',null,['class'=>'form-control '])}}
                                            </div>
                                            <div class="form-group">
                                                <label> التاريخ الي </label>
                                                {{Form::date('date_to',null,['class'=>'form-control '])}}
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"> البحث</button>
                                        </div>
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if(Auth::user()->can('sales_status_report'))
                        <div class="col-md-4">
                            <a class="btn btn-block btn-primary btn-sm btn-flat"
                               href="#" data-toggle="modal" data-target="#inventoryCaseModal"> تقرير حالة المبيعات </a>
                            <!-- Modal -->
                            <div id="inventoryCaseModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;
                                            </button>
                                            <h4 class="modal-title">التفاصيل</h4>
                                        </div>
                                        {{Form::open(['route'=>'reports.inventory_report_case','method'=>'GET'])}}
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>المجموعات</label>
                                                @inject('group','App\Models\Group')
                                                {{Form::select('group_id',$group->pluck('name','id')->toArray(),null,['class'=>'form-control ','id'=>'inventory_group_id','placeholder'=>'من فضلك اختر المجموعة'])}}
                                            </div>
                                            
                                             <div class="form-group">
                                                    <label> من تاريخ  </label>
                                                    {{Form::date('date_from',\Carbon\Carbon::now()->subDay(30),['class'=>'form-control '])}}
                                              </div>
                                                
                                            <div class="form-group">
                                                <label>الاصناف</label>
                                                @inject('item','App\Models\Item')
                                                {{Form::select('items[]',[],null,['class'=>'form-control ','multiple','id'=>'inventory_item_id'])}}
                                            </div>
                                            <div class="form-group">
                                                <label>المخازن</label>
                                                @inject('store','App\Models\Store')
                                                {{Form::select('stores[]',$store->pluck('name','id')->toArray(),null,['class'=>'form-control ','multiple'])}}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">عرض</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق
                                            </button>
                                        </div>
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                     

                        @if(Auth::user()->can('salary_report'))
                        <div class="col-md-4">
                            <a class="btn btn-block btn-primary btn-sm btn-flat" data-toggle="modal"
                               data-target="#sellsModal">
                              اجمالي المبيعات
                            </a>
                            <div class="modal fade" id="sellsModal" tabindex="-1" role="dialog"
                                 aria-labelledby="ModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="ModalLabel">
                                                 اجمالي المبيعات 
                                            </h4>
                                        </div>
                                        {{Form::open(['route'=>'reports.sells.index','method'=>'GET'])}}

                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>الفرع</label>
                                                {{Form::select('branch_id',\App\Models\Branch::pluck('name','id')->toArray(),null,['class'=>'form-control ','placeholder'=>'اختر الفرع'])}}
                                            </div>
                                            
                                           

                                            <div class="form-group">
                                                <label>التاريخ من</label>
                                                {{Form::date('date_from',null,['class'=>'form-control '])}}
                                            </div>
                                            <div class="form-group">
                                                <label> التاريخ الي </label>
                                                {{Form::date('date_to',null,['class'=>'form-control '])}}
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"> البحث</button>
                                        </div>
                                        {{Form::close()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-4">
                            <a href="#" class="btn btn-block btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#transports">تقرير النقلات</a>
                
                            <div id="transports" class="modal fade" role="dialog">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">تقرير النقلات</h4>
                                  </div>
                                  <form action="{{route('reports.transports')}}" method="get">
                                    <div class="modal-body">
                                      <div class="form-group">
                                        <label>اسم السائق</label>
                                        <select class="form-control" name="driver" required>
                                          @foreach(\App\Models\Employee::where('job_id', 5)->latest()->get() as $driver)
                                          <option value="{{$driver->id}}">{{$driver->name}}</option>
                                          @endforeach
                                        </select>
                                      </div>

                                      <div class="form-group">
                                        <label>من تاريخ</label>
                                        <input type="date" name="from" class="form-control" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}" required>
                                      </div>

                                      <div class="form-group">
                                        <label>الى تاريخ</label>
                                        <input type="date" name="to" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="submit" class="btn btn-primary">عرض</button>
                                      <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                                    </div>
                                  </form>
                                </div>
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
    <script>
        $('#group_id').on('change', function () {
            $.ajax({
                url: '{{url('group_items')}}' + '/' + $('#group_id').val(),
                type: 'GET',
                success: function (data) {
                    console.log(data);
                    $('#item_id').children().remove();
                    $('#item_id').append('<option value="0">  من فضلك اختر الصنف    </option>');
                    $.each(data.data, function (e) {
                        $('#item_id').append('<option value="' + data.data[e].id + '">' + data.data[e].name + '</option>');
                    });
                }
            })
        });

        ////

        $('#inventory_group_id').on('change', function () {
            $.ajax({
                url: '{{url('group_items')}}' + '/' + $('#inventory_group_id').val(),
                type: 'GET',
                success: function (data) {
                    console.log(data);
                    $('#inventory_item_id').children().remove();
                    $('#inventory_item_id').append('<option value="0">  من فضلك اختر الصنف    </option>');
                    $.each(data.data, function (e) {
                        $('#inventory_item_id').append('<option value="' + data.data[e].id + '">' + data.data[e].name + '</option>');
                    });
                }
            })
        });
        $('#client_group_id').on('change', function () {
            $.ajax({
                url: '{{url('group_items')}}' + '/' + $('#client_group_id').val(),
                type: 'GET',
                success: function (data) {
                    console.log(data);
                    $('#client_item_id').children().remove();
                    $('#client_item_id').append('<option value="0">  من فضلك اختر الصنف    </option>');
                    $.each(data.data, function (e) {
                        $('#client_item_id').append('<option value="' + data.data[e].id + '">' + data.data[e].name + '</option>');
                    });
                }
            })
        });
    </script>

    <script>
        $(document).ready(function () {

            $repositeId = $('#reposite_id')
            $cost = $('#cost')
            $type = $('#type')

            $repositeId.change(function () {
                if ($type.val() == 'out') {
                    $cost.prop('max', $repositeId.find(':selected').data('max'))
                }
            })

            $('.jstree').jstree({
                "core": {
                    "animation": 0,
                    "check_callback": true,
                    'force_text': true,
                    "themes": {"stripes": true},
                    'data':{!! json_encode(DB::table('trees')->get()) !!}
                },
                "types": {
                    "#": {"max_children": 1, "valid_children": ["root"]},
                    "root": {"icon": "fa  fa-folder-o", "valid_children": ["default"]},
                    "file": {"icon": "fa fa-file-o", "valid_children": []}
                },
                "plugins": ["search", "state", "types", "wholerow"]
            });

            $('.jstree').on('select_node.jstree', function (e, node) {
                console.log(node.selected[0], 'selectecddd')
                var selectedNodeId = node.selected[0]
                var outs = $('.jstree').jstree(true).get_json('j1_2', {
                    flat: true,
                    no_state: true,
                    no_id: false,
                    no_children: false,
                    no_li_attr: true,
                    no_a_attr: true,
                    no_data: true
                })
                var outsids = outs.map(function (node) {
                    return node = node.id
                })
                var ins = $('.jstree').jstree(true).get_json('j1_1', {
                    flat: true,
                    no_state: true,
                    no_id: false,
                    no_children: false,
                    no_li_attr: true,
                    no_a_attr: true,
                    no_data: true
                })
                var insids = ins.map(function (node) {
                    return node = node.id
                })
                var type = '';
                if (outsids.includes(selectedNodeId))   //masrofat
                {

                    type = "out"
                    $cost.prop('max', $repositeId.find(':selected').data('max'))
                }
                else if (insids.includes(selectedNodeId)) {
                    $cost.prop('max', false);
                    type = "in"
                }
                $('[name=tree_id]').val(selectedNodeId)

                $('[name=type]').val(type)

            })
        })


    </script>
    <script>
        $(document).ready(function() {
            // When the branch select field changes
            $('#branch-select').on('change', function() {
                var branchId = $(this).val();
                var employeeSelect = $('#employee-select');
                
                // Clear the previous employee options
                employeeSelect.empty();
                
                // Add the default option
                employeeSelect.append('<option value="all">كل الموظفين فى الفرع</option>');
                
                // If a branch is selected
                if (branchId) {
                    // Make an AJAX request to get the employees for the selected branch
                    $.ajax({
                        url: '/getEmployees/with/branch', // Replace with the actual endpoint URL that retrieves employees based on a branch ID
                        type: 'GET',
                        data: { branch_id: branchId },
                        success: function(response) {
                            // Add the retrieved employees to the employee select field
                            $.each(response, function(key, value) {
                                employeeSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // When the branch select field changes
            $('#branch-select1').on('change', function() {
                var branchId = $(this).val();
                var employeeSelect = $('#employee-select1');
                
                // Clear the previous employee options
                employeeSelect.empty();
                
                // Add the default option
                employeeSelect.append('<option value="all">كل الموظفين فى الفرع</option>');
                
                // If a branch is selected
                if (branchId) {
                    // Make an AJAX request to get the employees for the selected branch
                    $.ajax({
                        url: '/getEmployees/with/branch', // Replace with the actual endpoint URL that retrieves employees based on a branch ID
                        type: 'GET',
                        data: { branch_id: branchId },
                        success: function(response) {
                            // Add the retrieved employees to the employee select field
                            $.each(response, function(key, value) {
                                employeeSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // When the group select changes
            $('#groups_id').on('change', function() {
                var groupId = $(this).val(); // Get the selected group ID
        
                // Make an AJAX request to fetch the items for the selected group
                $.ajax({
                    url: '/get-items', // Replace with the actual URL to fetch items
                    type: 'GET',
                    data: {group_id: groupId}, // Pass the selected group ID as a parameter
                    success: function(response) {
                        // Clear the current items select options
                        $('#item_id').empty();
        
                        // Add the new items select options
                        $.each(response.items, function(key, value) {
                            $('#items_id').append($('<option>').text(value.name).attr('value', value.id));
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // When the group select changes
            $('#groups_id_new').on('change', function() {
                var groupId = $(this).val(); // Get the selected group ID
        
                // Make an AJAX request to fetch the items for the selected group
                $.ajax({
                    url: '/get-items', // Replace with the actual URL to fetch items
                    type: 'GET',
                    data: {group_id: groupId}, // Pass the selected group ID as a parameter
                    success: function(response) {
                        // Clear the current items select options
                        $('#item_id_new').empty();
        
                        // Add the new items select options
                        $.each(response.items, function(key, value) {
                            $('#items_id_new').append($('<option>').text(value.name).attr('value', value.id));
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // When the group select changes for movements report
            $('#groups_id_movements').on('change', function() {
                var groupId = $(this).val(); // Get the selected group ID
        
                // Make an AJAX request to fetch the items for the selected group
                $.ajax({
                    url: '/get-items', // Replace with the actual URL to fetch items
                    type: 'GET',
                    data: {group_id: groupId}, // Pass the selected group ID as a parameter
                    success: function(response) {
                        // Clear the current items select options
                        $('#items_id_movements').empty();
        
                        // Add the new items select options
                        $.each(response.items, function(key, value) {
                            $('#items_id_movements').append($('<option>').text(value.name).attr('value', value.id));
                        });
                    }
                });
            });
        });
    </script>
@endpush
