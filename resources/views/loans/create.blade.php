@extends('layout.app')
@section('title','السلف')
@section('sub-title','اضافة')
@section('content')
<style>
    #madioniaInput {
        display: none;
    }
</style>
<div class="row">
    @if($employees->count() and $reposites->count())
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> اضافة </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" class="validate" action="{{route('loans.store')}}" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selectedValue">النوع</label>
                                    @if(Auth::user()->can('madionia'))
                                        {{-- {{Form::select('type',['solfa'=>'سلفة', 'madionia'=>'مديونية'],null,['class'=>'form-control loan-type'])}} --}}
                                        <select class="form-control loan-type" name="type" id="selectedValue" onchange="showMadioniaInput()">
                                            <option value="solfa">سلفة</option>
                                            <option value="madionia">مديونية</option>
                                        </select>
                                    @else
                                        {{-- {{Form::select('type',['solfa'=>'سلفة'],null,['class'=>'form-control loan-type'])}} --}}
                                        <select class="form-control loan-type" name="type">
                                            <option value="solfa">سلفة</option>
                                        </select>
                                    @endif
                                </div>
                            </div>
                            @if(Auth::user()->can('madionia'))
                            <div class="col-md-6 loan-month" id="madioniaInput">
                                <div class="form-group">
                                    <label for="cost">شهور المديونيه</label>
                                    <input type="number" min="0" required class="form-control loanMonthValue" name="month" >
                                </div> 
                                <span class="valuePerMonth h6" ></span>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">التاريخ</label>
                                @if(auth()->user()->hasRole('admin'))
                                <input type="text" required class="form-control date" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" autocomplete="off" name="date" id="date">
                                @else
                                <input type="text" required class="form-control" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" autocomplete="off" name="date" id="date" readonly>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cost">القيمة</label>
                                <input type="text" min="0"  required class="form-control" name="cost" id="cost" onkeyup="calculateValuePerMonth(this.value)" >
                            </div>

                        </div>
                    </div>


                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="reposite_id"> الخزنة</label>
                                <select class="form-control" name="reposite_id" id="reposite_id">
                                    @foreach ($reposites as $reposite )
                                    <option value="{{$reposite->id}}" data-max="{{$reposite->balance}}">{{$reposite->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employee_id"> الموظف</label>
                                <select class="form-control" name="employee_id" id="employee_id">
                                    @foreach ($employees as $employee )
                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes">ملاحظات </label>
                                <textarea class="form-control" name="notes" id="notes"></textarea>
                            </div>
                        </div>

                    </div>




                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-sm btn-success btn-flat">اضافة</button>
                </div>
            </form>
        </div>
        <!-- /.box -->

    </div>
    @else
    @unless($employees->count())
    <div class="col-sm-12">
        <div class="alert alert-danger">
            لايوجد موظفين
        </div>
    </div>
    @endunless


    @unless($reposites->count())
    <div class="col-sm-12">
        <div class="alert alert-danger">
            لايوجد خزن
        </div>
    </div>
    @endunless


    @endif
</div>
<script>
    function showMadioniaInput() {
      var select = document.getElementById("selectedValue");
      var madioniaInput = document.getElementById("madioniaInput");

      var selectedValue = select.value;

      if (selectedValue == "madionia") {
        madioniaInput.style.display = "block";
      }
      else {
        madioniaInput.style.display = "none";
      }
    }
</script>
@stop
@push('scripts')
<script>
    $(document).ready(function () {
        $repositeId = $('#reposite_id')
        $cost = $('#cost')

        changeMax()
        $repositeId.change(changeMax);
        $('.validate').validate();

        function changeMax() {
            $cost.prop('max', $repositeId.find(':selected').data('max'));
        }
    });

    function calculateValuePerMonth(value) { 
        var vpm = value / parseInt($(".loanMonthValue").val());
        $(".valuePerMonth").html("القيمه المدفوعه خلال الشهر هى " + vpm);
    }
</script>
@endpush