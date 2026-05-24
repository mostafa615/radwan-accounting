@extends('layout.app')
@section('title','التعاملات اليومية')
@section('sub-title','تعديل')
@section('content')
    <div class="row">
      @if($reposites->count())
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> تعديل </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" class="validate" action="{{route('daily.update',$daily)}}" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <input type="hidden" name="type" value="{{$daily->type}}">
            <input type="hidden" name="tree_id" value="{{$daily->tree_id}}">
              <div class="box-body">
                {{-- <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="name">رقم السند</label>
                      <input type="text"  required class="form-control" name="no" id="no" value="{{$daily->no}}">
                    </div>
    
                  </div>
                </div> --}}

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="date">التاريخ</label>
                      @if(auth()->user()->hasRole('admin'))
                      <input type="text" required class="form-control date" name="date" id="date" value="{{optional($daily->date)->toDateString()}}" autocomplete="off">
                      @else
                      <input type="text" required class="form-control" name="date" id="date" value="{{optional($daily->date)->toDateString()}}" autocomplete="off" readonly>
                      @endif
                    </div>
    
                  </div>
                </div>
              
             

              <div class="row">
                  <div class="col-sm-12">
                      <div class="form-group">
                          <label for="reposite_id"> الخزنة </label>
                          <select name="reposite_id" required id="reposite_id">
                              @foreach ($reposites as $reposite )
                                      <option data-max="{{$reposite->balance}}" {{$reposite->id == $daily->reposite_id?'selected':''}} value="{{$reposite->id}}">{{$reposite->name}}</option>
                              @endforeach
                          </select>
                      </div>
                </div>

              </div>

           
              <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="cost">القيمة </label>
                      <input type="text"  required class="form-control" name="cost" id="cost" value="{{$daily->cost}}">
                    </div>
    
                  </div>
                </div>


                          
             
                      
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">الوصف </label>
                            <textarea class="form-control" name="notes" id="notes">{{$daily->notes}}</textarea>
                          </div>
                    </div>
                  </div>

                
                    
        </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-sm btn-success btn-flat">تعديل</button>
                <button type="button" class="btn btn-warning btn-flat btn-sm btn-flat" data-toggle="modal" data-target="#Modal"> 
                    <i class="fa fa-tree"></i>  
                  </button>

                  
                  <!-- Modal -->
                  <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="ModalLabel"> الايرادات والمصروفات</h4>
                        </div>
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
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>



              </div>
            </form>
          </div>
          <!-- /.box -->
         
        </div>
        @else 
        @unless($reposites->count())
          <div class="col-sm-12">
            <div class="alert alert-danger">
              لايوجد خزن
            </div>
          </div>

        @endunless

        @endif
    </div>
@stop
@push('scripts')
<script>
    $(document).ready(function(){

        $('.validate').validate({
          rules:{
            no:{
              remote:{
                  type:'post',
                  url:'{{route('validate')}}',
                  data:{
                      field:"no",
                      value:function()
                      {
                          return $('[name=no]').val();
                      },
                      method:'unique:dailies,no,{{$daily->id}}',
                  }
                  }
          }
        },
            messages:{
              no:{
                remote:'هذه القيمة موجودة مسبقا'
              }
            }
        })


    })

</script>
@endpush

@push('scripts')

<script>
    $(document).ready(function(){

      $repositeId = $('#reposite_id')
      $cost = $('#cost')
      $type = $('#type')

      $repositeId.change(function(){
          if($type.val() == 'out'){
            $cost.prop('max',$repositeId.find(':selected').data('max'))
          }
      })

      $(".jstree").on("ready.jstree", function(){
        $('.jstree').jstree(true).deselect_all()
        $('.jstree').jstree(true).select_node("{{$daily->tree_id}}")
    });  

      $('.jstree').jstree({
        "core" : {
          "animation" : 0,
          "check_callback" : true,
          'force_text' : true,
          "themes" : { "stripes" : true },
          'data' :{!! json_encode(DB::table('trees')->get()) !!}
        },
        "types" : {
          "#" : { "max_children" : 1, "valid_children" : ["root"] },
          "root" : { "icon" : "fa  fa-folder-o", "valid_children" : ["default"] },
          "file" : { "icon" : "fa fa-file-o", "valid_children" : [] }
        },
        "plugins" : [ "search", "state", "types", "wholerow" ]
      });

  $('.jstree').on('select_node.jstree',function(e,node){
      console.log(node.selected[0] , 'selectecddd')
        var selectedNodeId = node.selected[0]
          var outs = $('.jstree').jstree(true).get_json('j1_2', {flat:true,no_state:true,no_id:false,no_children:false,no_li_attr:true,no_a_attr:true,no_data:true})        
          var outsids =  outs.map(function(node){ return node = node.id})
          var ins = $('.jstree').jstree(true).get_json('j1_1', {flat:true,no_state:true,no_id:false,no_children:false,no_li_attr:true,no_a_attr:true,no_data:true})                
          var insids =  ins.map(function(node){ return node = node.id})                      
          var type = '';
          if (outsids.includes(selectedNodeId) )   //masrofat 
          {
            
            type="out"
            $cost.prop('max',$repositeId.find(':selected').data('max'))
          }
          else if (insids.includes(selectedNodeId))    
          {
            $cost.prop('max',false);
            type="in"
          }
      $('[name=tree_id]').val(selectedNodeId)

      $('[name=type]').val(type)

    })
})
  
    
</script>

@endpush