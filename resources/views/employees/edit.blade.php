@extends('layout.app')
@section('title','الموظفين')
@section('sub-title','تعديل')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> تعديل </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" class="validate" action="{{route('employees.update',$employee)}}" method="POST"
                      enctype="multipart/form-data">
                    {{csrf_field()}}
                    {{method_field('PUT')}}
                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">الاسم</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="{{$employee->name}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email"> البريد الالكتروني</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                           value="{{$employee->email}}">
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="job_id"> الوظيفة</label>
                                    <select class="form-control" name="job_id" id="job_id">
                                        <option value="">لايوجد</option>
                                        @foreach ($jobs as $job )
                                            <option value="{{$job->id}}" {{$employee->job_id == $job->id?'selected':''}}>{{$job->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">الراتب الاساسي </label>
                                    <input type="number" class="form-control" name="salary" id="salary"
                                           value="{{$employee->salary}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address">العنوان</label>
                                    <input type="text" class="form-control" name="address" id="address"
                                           value="{{$employee->address}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="whatsapp">واتس</label>
                                    <input type="text" class="form-control" name="whatsapp" id="whatsapp"
                                           value="{{$employee->whatsapp}}">
                                </div>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="country_id"> المحافظة</label>
                                    <select class="form-control" name="country_id" id="country_id">
                                        @foreach ($countries as $country )
                                            <option value="{{$country->id}}" {{$employee->country_id == $country->id ?'selected':''}}>{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone_1"> تليفون 1</label>
                                    <input type="text" class="form-control" name="phone_1" id="phone_1"
                                           value="{{$employee->phone_1}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone_2"> تليفون 2</label>
                                    <input type="text" class="form-control" name="phone_2" id="phone_2"
                                           value="{{$employee->phone_2}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone_3"> تليفون 3</label>
                                    <input type="text" class="form-control" name="phone_3" id="phone_3"
                                           value="{{$employee->phone_3}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">

                                    <label for="branch_id"> الفرع </label>

                                    <select id="branch_id" class="form-control" name="branch_id">
                                        @foreach ($branches as $branch)
                                            <option {{$employee->branch_id == $branch->id?'selected':''}} value="{{$branch->id}}">{{$branch->name}}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_of_birth"> تاريخ الميلاد</label>
                                    <input type="text" class="form-control date" name="date_of_birth" id="date_of_birth"
                                           value="{{optional($employee->date_of_birth)->toDateString()}}">
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="date_of_appointment"> تاريخ التنعيين</label>
                                    <input type="text" class="form-control date" name="date_of_appointment"
                                           id="date_of_appointment"
                                           value="{{optional($employee->date_of_appointment)->toDateString()}}">
                                </div>

                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="degree"> المؤهل</label>
                                    <input type="text" class="form-control" name="degree" id="degree"
                                           value="{{$employee->degree}}">
                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="holidaies_balance">رصيد الاجازات</label>
                                    <input type="number" class="form-control" value="{{$holidaies_balance}}" name="holidaies_balance" id="holidaies_balance">
                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="summer_holidays"> اجازات المصيف</label>
                                    <input type="number" class="form-control" value="{{$summer_holidays}}" name="summer_holidays" id="summer_holidays">
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="medical_insurance">التأمين الطبي</label>
                                    <input type="number" step="any" min="0" class="form-control" name="medical_insurance" id="medical_insurance"
                                           value="{{$employee->medical_insurance}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="social_insurance">التأمين الاجتماعي</label>
                                    <input type="number" step="any" min="0" class="form-control" name="social_insurance" id="social_insurance"
                                           value="{{$employee->social_insurance}}">
                                </div>
                            </div>


                        </div>
                        <!--<div class="row">-->
                        <!--    <div class="col-md-12">-->
                        <!--      <input type="hidden" name="summer_holiday_permission" value="0">-->
      
                        <!--          <div class="form-group">-->
                        <!--              <label>تفعيل اجازة المصيف</label>-->
                        <!--              <div class="custom-control custom-switch material-switch">-->
                        <!--                      <input type="checkbox" name="summer_holiday_permission" class="custom-control-input" id="employeeSwitch{{$employee->id}}"-->
                        <!--                      onchange="this.checked? this.value = 1 : this.value = 0" {{ $employee->summer_holiday_permission == 1? 'checked' : ''}} >-->
                        <!--                      <label class="custom-control-label" for="employeeSwitch{{$employee->id}}"></label>-->
                        <!--              </div>-->
                        <!--          </div>-->
                        <!--  </div>-->
                        <!--  </div>-->


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">ملاحظات </label>
                                    <textarea class="form-control" name="notes"
                                              id="notes">{{$employee->notes}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="file-upload btn-sm btn-block btn btn-default">
                                        الصورة الشخصية <input type="file" name="avatar"/>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">

                                <div class="form-group">
                                    <label class="file-upload btn-sm btn btn-default">
                                        صورة البطاقة 1 <input type="file" name="id_image_1"/>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">

                                <div class="form-group">
                                    <label class="file-upload btn-sm btn btn-default">
                                        صورة البطاقة 2 <input type="file" name="id_image_2"/>
                                    </label>
                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="form-group">
                                    <label class="file-upload btn-sm btn btn-default">
                                        CV <input type="file" name="cv"/>
                                    </label>
                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="form-group">
                                    <label class="file-upload btn-sm btn btn-default">
                                        صحيفة الحالة الجنائية <input type="file" name="criminal_record"/>
                                    </label>
                                </div>

                            </div>


                        </div>


                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-sm btn-success btn-flat">تعديل</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->

        </div>
    </div>
@stop
@push('scripts')
    <script>
        $(document).ready(function () {

            $('.validate').validate({
                rules: {
                    email: {
                        remote: {
                            type: 'post',
                            url: '{{route('validate')}}',
                            data: {
                                field: "email",
                                value: function () {
                                    return $('[name=email]').val();
                                },
                                method: 'unique:employees,email,{{$employee->id}}',
                            }
                        }
                    },
                    phone_1: {
                        remote: {
                            type: 'post',
                            url: '{{route('validate')}}',
                            data: {
                                field: "phone_1",
                                value: function () {
                                    return $('[name=phone_1]').val();
                                },
                                method: 'unique:employees,phone_1,{{$employee->id}}|unique:employees,phone_2,{{$employee->id}}|unique:employees,phone_3,{{$employee->id}}',
                            }
                        }
                    },
                    phone_2: {
                        remote: {
                            type: 'post',
                            url: '{{route('validate')}}',
                            data: {
                                field: "phone_2",
                                value: function () {
                                    return $('[name=phone_2]').val();
                                },
                                method: 'unique:employees,phone_1,{{$employee->id}}|unique:employees,phone_2,{{$employee->id}}|unique:employees,phone_3,{{$employee->id}}',
                            }
                        }
                    },
                    phone_3: {
                        remote: {
                            type: 'post',
                            url: '{{route('validate')}}',
                            data: {
                                field: "phone_3",
                                value: function () {
                                    return $('[name=phone_3]').val();
                                },
                                method: 'unique:employees,phone_1,{{$employee->id}}|unique:employees,phone_2,{{$employee->id}}|unique:employees,phone_3,{{$employee->id}}',
                            }
                        }
                    },
                    id_image_1: {
                        extension: 'jpg|jpeg|png'
                    },
                    id_image_2: {
                        extension: 'jpg|jpeg|png'
                    }
                },
                messages: {
                    email: {
                        remote: 'هذه القيمة موجودة مسبقا'
                    },
                    phone_1: {
                        remote: 'هذه القيمة موجودة مسبقا'
                    },
                    phone_2: {
                        remote: 'هذه القيمة موجودة مسبقا'
                    },
                    phone_3: {
                        remote: 'هذه القيمة موجودة مسبقا'
                    }
                }
            })


        })

    </script>
@endpush