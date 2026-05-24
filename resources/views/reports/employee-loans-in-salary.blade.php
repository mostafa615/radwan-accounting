@extends('layout.app')

@section('title','التقارير')

@section('sub-title','مدفوعات الموظف للسلف من المرتب')

@section('content')

<div class="row">

    <div class="col-md-12"> 

        <div class="nav-tabs-custom">

            <ul class="nav nav-tabs">

                <li class="active"><a href="#employee-loans-in-salary-tab" data-toggle="tab" aria-expanded="true">الموظفين</a></li>

                <li class="pull-left">

                    <button  data-toggle="modal" data-target="#modal" class="btn btn-success btn-flat btn-sm">

                        <i class="fa fa-cog fa-spin"></i>

                    </button>

                </li>

            </ul>

            <div class="tab-content">

                <div class="tab-pane  active" id="employee-loans-in-salary-tab">

                    <div class="table-responsive">

                        <table width="100%" id="example_1" class="table table-bordered ">



                            <thead>

                                <tr>

                                    <th>البيان</th>

                                    <th>المبلغ</th>

                                    <th>التاريخ</th>

                                </tr>

                            </thead>



                            <tbody>

                                @foreach($loans as $item)

                                <tr>

                                    <td>{{ $item->type =="solfa"? "سلفه" : "مديونية" }}</td>

                                    <td>{{ $item->cost }}</td>

                                    <td>{{ $item->date }}</td>

                                </tr>

                                @endforeach

                                @foreach($loans2 as $item)

                                <tr>

                                    <td>{{ $item->type =="solfa"? "سلفه" : "مديونية" }}</td>

                                    <td>{{ $item->cost }}</td>

                                    <td>{{ $item->date }}</td>

                                </tr>

                                @endforeach

                                @foreach($mads as $item)

                                <tr>

                                    <td>مديونيه</td>

                                    <td>{{ $item->madionia }}</td>

                                    <td>{{ $item->date }}</td>

                                </tr>

                                @endforeach

                                @foreach($dailies as $item)

                                <tr>

                                    <td>{{ $item->notes }}</td>

                                    <td>{{ $item->cost }}</td>

                                    <td>{{ $item->date }}</td>

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



    </div>





</div>

@stop

@push('scripts')

{{--  <script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>  --}}

 

@endpush