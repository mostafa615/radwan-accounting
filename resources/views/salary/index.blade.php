@extends('layout.app')
@section('title','المرتبات')
@section('sub-title','الرئسية')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">المرتبات</h3>
                    <div class="box-btn">
                    @if(Auth::user()->can('salary_setting'))
                        <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary btn-flat btn-sm" data-toggle="modal"
                                    data-target="#modal">
                                <i class="fa fa-calendar"></i>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form class="validate">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="modalLabel"> اختر التاريخ</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="date">التاريخ</label>
                                                            <input type="text" id="date" required
                                                                   class="form-control date"
                                                                   name="date"
                                                                   value="{{Carbon\Carbon::now()->toDateString()}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default btn-sm btn-flat"
                                                        data-dismiss="modal">الغاء
                                                </button>
                                                <button type="submit" id="accept"
                                                        class="btn btn-primary btn-sm btn-flat">
                                                    موافق
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive" style="overflow-y: auto;">
                        <form>
                            {!! $dataTable->table(['class' => 'table table-bordered']) !!}
                        </form>

                    </div>
                </div>

                <!-- /.box-body -->

            </div>
            <!-- /.box -->
        </div>

    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="salary_recet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="salary_recet">بياان بصافى الراتب</h4>
          </div>
          <div class="modal-body salary_recet_body">
            <table class="table table-bordered" id="salary_recet_table" >
                <thead>
                    <tr>
                        <th>#</th>
                        <th>البيان</th>
                        <th>القيمه</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>الموظف</td>
                        <td>
                            <span class="salary_employee" ></span>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>الراتب الاساسى</td>
                        <td>
                            <span class="salary_basic" ></span>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>المديونيه</td>
                        <td class="salary_madionia" ></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>السلف</td>
                        <td class="salary_loans" ></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>النقلات</td>
                        <td class="salary_transports" ></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>المكافائات</td>
                        <td class="salary_awards" ></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>جزاءات</td>
                        <td class="salary_discount" ></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>التامينات</td>
                        <td class="salary_insurance" ></td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>التاخيرات</td>
                        <td class="salary_late" ></td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>الغيابات</td>
                        <td class="salary_absence" ></td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td>الصافى</td>

                        <td class="salary_final" ></td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <td>الملاحظات</td>
                        <td class="salary_notes" ></td>
                    </tr>
                </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">اغلاق</button> 
          </div>
        </div>
      </div>
    </div>
    
    
    <div> 
            <table class="table table-bordered" id="salary_recet_table_clone_node" >
           
                <thead>
                    <tr>
                        <th>#</th>
                        <th>البيان</th>
                        <th>القيمه</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>الموظف</td>
                        <td>
                            <span class="salary_employee" ></span>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>الراتب الاساسى</td>
                        <td>
                            <span class="salary_basic" ></span>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>المديونيه</td>
                        <td class="salary_madionia" ></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>السلف</td>
                        <td class="salary_loans" ></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>النقلات</td>
                        <td class="salary_transports" ></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>المكافائات</td>
                        <td class="salary_awards" ></td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>جزاءات</td>
                        <td class="salary_discount" ></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>التامينات</td>
                        <td class="salary_insurance" ></td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>التاخيرات</td>
                        <td class="salary_late" ></td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>الغيابات</td>
                        <td class="salary_absence" ></td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td>الأضافي</td>
                        <td class="salary_over_time" ></td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <td>الصافى</td>
                        <td class="salary_final" ></td>
                    </tr>
                    <tr>
                        <td>13</td>
                        <td>الملاحظات</td>
                        <td class="salary_notes" ></td>
                    </tr>
                </tbody>
            </table>
    </div>
@stop


@push('scripts')
    <script>


        var $modal = $('#modal');
        var $settingModal = $('#settingsModal');
        var $accept = $('#accept')
        var $date = $('#date');
        var date = '{{Carbon\Carbon::now()->toDateString()}}';
        var $dateForm = $('.validate');
        var $table = $($('.table')[0]);

        $(document).ready(function () {
            
            $dateForm.validate();
            $dateForm.on('submit', function (e) {
                e.preventDefault();
                if ($dateForm.valid()) {
                    date = $date.val();
                    $table.DataTable().clear().draw();
                    $modal.modal('hide');
                }
            })


            $(document).on('click', '.perform-save', function (e) {

                e.preventDefault();
                id = $(this).data('employee-id');


                if ($(`.validate-salary-${id}`).valid()) {
                    basic = $(`#salary-${id}-basic`).val();
                    notes = $(`#salary-${id}-notes`).val();
                    bonus = $(`#salary-${id}-bonus`).val();
                    financialPenalities = $(`#salary-${id}-financial-penalties`).val();
                    loans = $(`#salary-${id}-loans`).val();
                    madionia = $(`#salary-${id}-madionia`).val();
                    transports = $(`#salary-${id}-transports`).val();
                    insurance = $(`#salary-${id}-insurance`).val();
                    reposite_id = $(`#reposite_id_${id}`).val();
                    console.log(id, Number(transports), loans, financialPenalities, bonus);
                    $.ajax({
                        url: '{{route("api.salary.perform-save")}}',
                        type: 'POST',
                        data: {
                            employee_id: id,
                            date: $date.val(),
                            basic: basic,
                            bonus: bonus,
                            financial_penalties: financialPenalities,
                            loans: loans,
                            notes: notes,
                            madionia: madionia,
                            transports: transports,
                            insurance: insurance,
                            reposite_id: reposite_id
                        }, success: function (data) {
                            console.log(data);
                            $table.DataTable().clear().draw();
                            iziToast.success({
                                timeout: 1000,
                                transitionIn: 'flipInX',
                                transitionOut: 'flipOutX',
                                position: 'bottomLeft',
                                rtl: true,
                                message: 'تم التعديل بنجاح ',
                            });
                        },
                        error: function () {

                        }
                    })
                }

            })

        });
        
        function calculateNet (tr) {
            var table = document.createElement("table");
            table.className = "table table-bordered";
            table.id = "salary_recet_table";
            table.innerHTML = $("#salary_recet_table_clone_node").html();
             
            $(".salary_recet_body").html(''); 
            $(".salary_recet_body")[0].appendChild(table);
            //$("#salary_recet_table").html($("#salary_recet_table_clone_node").html());
            
            var bs = $(tr).parent().find(".basicSalary").val();
            var mad = $(tr).parent().find(".mad").val();
            var loans = $(tr).parent().find(".loans").val();
            var tran = $(tr).parent().find(".tran").val();
            var bonus = $(tr).parent().find(".bonus").val();
            var fin = $(tr).parent().find(".fin").val();
            var ins = $(tr).parent().find(".ins").val();
            var late = $(tr).parent().find(".late-cost").text();
            var over_time = $(tr).parent().find(".over-time-cost").text();
            var absence = $(tr).parent().find(".absence-cost").text();
            var notes = $(tr).parent().find(".notes").val();
            
            var net = parseFloat(bs) - 
                      parseFloat(mad) - 
                      parseFloat(loans) + 
                      parseFloat(tran) + 
                      parseFloat(bonus) - 
                      parseFloat(fin) - 
                      parseFloat(ins) - 
                      parseFloat(late) +
                      parseFloat(over_time) - 
                      parseFloat(absence);
                      
            // set values
            $(".salary_employee").html($($(tr).parent().children()[0]).text());
            
            $(".salary_basic").html(parseFloat(bs).toFixed(2));   
            $(".salary_madionia").text(parseFloat(mad).toFixed(2)); 
            $(".salary_loans").text(parseFloat(loans).toFixed(2)); 
            $(".salary_transports").text(parseFloat(tran).toFixed(2)); 
            $(".salary_awards").text(parseFloat(bonus).toFixed(2)); 
            $(".salary_discount").text(parseFloat(fin).toFixed(2)); 
            $(".salary_insurance").text(parseFloat(ins).toFixed(2)); 
            $(".salary_late").text(parseFloat(late).toFixed(2));
            $(".salary_absence").text(parseFloat(absence).toFixed(2)); 
            $(".salary_over_time").text(parseFloat(over_time).toFixed(2)); 
            
            $(".salary_final").text(net.toFixed(0));  
            
            $(".salary_notes").text(notes); 
            
            datatable();
            //$('#salary_recet_table').DataTable().draw();    
            $("#salary_recet").modal();
               
 
              
            $(tr).parent().find(".net").html(Math.round(net));
        }
        
        
        function saveBonus(employee_id, salary_id, date, button) {
            var tr = button.parentElement.parentElement;
            
            var bonus = $(tr).parent().find(".bonus").val();
            var fin = $(tr).parent().find(".fin").val();
            var ins = $(tr).parent().find(".ins").val();
            
            var data = "?salary_id="+salary_id+"&employee_id="+employee_id+"&bonus="+bonus+"&financial_penalties="+fin+"&insurance="+ins+"&date="+date;
            $.get('{{ url("/api/save-bonus") }}'+data, function(){
                iziToast.success({
                    timeout: 1000,
                    transitionIn: 'flipInX',
                    transitionOut: 'flipOutX',
                    position: 'bottomLeft',
                    rtl: true,
                    message: 'تم التعديل بنجاح ',
                });
                $table.DataTable().clear().draw();
            }); 
        }

        function resetSalary(employee_id, salary_id, date, button) {
            var tr = button.parentElement.parentElement;
            
            var bonus = $(tr).parent().find(".bonus").val();
            var fin = $(tr).parent().find(".fin").val();
            var ins = $(tr).parent().find(".ins").val();
            
            var data = "?salary_id="+salary_id+"&employee_id="+employee_id+"&bonus="+bonus+"&financial_penalties="+fin+"&insurance="+ins+"&date="+date;
            $.get('{{ url("/api/reset-salary") }}'+data, function(){
                iziToast.success({
                    timeout: 1000,
                    transitionIn: 'flipInX',
                    transitionOut: 'flipOutX',
                    position: 'bottomLeft',
                    rtl: true,
                    message: 'تم التعديل بنجاح ',
                });
                $table.DataTable().clear().draw();
            }); 
        }
    </script>
     
     <script>
        function datatable() {
            var $fileName = $('.salary_employee').html();
            // alert($fileName);
            $('#salary_recet_table').DataTable( {
            paging: false, 
            dom: 'Bfrtip',
            buttons: [
            {
                extend: 'excel',
                filename: $fileName
            },
            {
                extend: 'print',
                filename: $fileName 
            },
           
            ]
         }); 
        }
        
        datatable();
     </script>
@endpush


@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush