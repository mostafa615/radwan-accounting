@extends('layout.app')
@section('title','الحسابات')
@section('sub-title',$owner->name)
@section('content')
<div class="row">
    <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">الحسابات</h3>
              <div class="box-btn">
                
                    <!-- Button trigger modal -->
                   @if($reposites->count())
                    <a class="btn btn-primary btn-sm btn-flat" href="{{route('accounts.create',['owner'=>request()->owner,'id'=>request()->id])}}">
                            اضافة
                    </a>
                    @endif
                    <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group">
                                        <label for="all">
                                                <input type="radio"  id="all" name="type" class="fillter" value="" checked> 
                                                الكل
                                        </label>
                                </div>
                            </div>


                        <div class="col-sm-4">
                            <div class="form-group">
                                    <label for="in">
                                            <input type="radio"  id="in" name="type" class="fillter" value="in" > 
                                            {{$names['inName']}}
                                    </label>
                            </div>
                        </div>

                        <div class="col-sm-4">
                                <div class="form-group">
                                        <label for="out">
                                                <input type="radio" id="out"  class="fillter" name="type" value="out"> 
                                                {{$names['outName']}}
                                        </label>
                                </div>
                            </div>


                    </div>


                    <!-- Modal -->
                    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="createModalLabel"> اضافة</h4>
                          </div>
                          <form class="create-form">
                          <div class="modal-body">
                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="date">التاريخ </label>
                                        <input type="text" class="form-control date" name="date" required >
                                    </div>
                                </div>


                                 <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="no">رقم السند </label>
                                            <input type="text" class="form-control no" name="no"  required >
                                        </div>
                                    </div>

                                  

                                        
                                    <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="reposite_id"> الخزنة </label>
                                                <select name="reposite_id" class="reposite_id">
                                                    @foreach ($reposites as $reposite )
                                                            <option data-max="{{$reposite->balance}}" value="{{$reposite->id}}">{{$reposite->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                    </div>

                                    <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="type"> الدفع  </label>
                                                <select required class="form-control type check">
                                                    <option value="">اختر</option>
                                                    <option value="in">وارد</option>
                                                  <option value="out">صادر</option>
                                                </select>
                                            </div>
                                    </div>


                                    <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="order_id"> الفواتير </label>
                                                <select class="form-control check order_id" name="order_id">
                                                        <option value="">لايوجد</option>
                                                </select>
                                            </div>
                                    </div>

                                    <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="cost">القيمة  </label>
                                                <input type="text" class="form-control cost" min="0" name="cost" required >
                                            </div>
                                        </div>



                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-flat btn-sm" data-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-primary btn-flat btn-sm">موافق</button>
                          </div>
                        </form>
                        </div>
                      </div>
                    </div>





                    {{--  edit-form  --}}
                    <!-- Modal -->
                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="editModalLabel"> اضافة</h4>
                          </div>
                          <form class="edit-form" id="edit-form">
                          <div class="modal-body">
                            <div class="row">

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="date">التاريخ </label>
                                        <input type="text" class="form-control date" name="date" required >
                                    </div>
                                </div>


                                 <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="no">رقم السند </label>
                                            <input type="text" class="form-control no" name="no"  required >
                                        </div>
                                    </div>

                                  

                                        
                                    <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="reposite_id"> الخزنة </label>
                                                <select name="reposite_id" class="reposite_id">
                                                    
                                                </select>
                                            </div>
                                    </div>

                                    <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="type"> الدفع  </label>
                                                <select required class="form-control type check">
                                                    <option value="">اختر</option>
                                                    <option value="in">وارد</option>
                                                  <option value="out">صادر</option>
                                                </select>
                                            </div>
                                    </div>


                                    <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="order_id"> الفواتير </label>
                                                <select class="form-control check order_id" name="order_id">
                                                        <option value="">لايوجد</option>
                                                </select>
                                            </div>
                                    </div>

                                    <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="cost">القيمة  </label>
                                                <input type="text" class="form-control cost" min="0" name="cost" required >
                                            </div>
                                        </div>



                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-flat btn-sm" data-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-primary btn-flat btn-sm">موافق</button>
                          </div>
                        </form>
                        </div>
                      </div>
                    </div>


                    {{-- end-edit-form  --}}

              </div>
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="table-responsive">
             {!! $dataTable->table(['class' => 'table table-bordered']) !!} 
            
            </div>
            </div>

            <!-- /.box-body -->
         
          </div>
          <!-- /.box -->
        </div>

</div>
@stop
@push('scripts')
<script>
    type = '';
    $(document).ready(function(){
        $table = $('.table').DataTable();
        $('.fillter').change(function(){
            type =  $(this).val()
            $table.clear().draw();

        })
    })
</script>
@endpush 

 @push('scripts')
<script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
{!! $dataTable->scripts() !!}
@endpush 

