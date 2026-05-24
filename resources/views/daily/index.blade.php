@extends('layout.app')
@section('title','التعاملات اليومية')
@section('sub-title','الرئسية')
@section('content')
<div class="row">
    <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">التعاملات اليومية</h3>
              <div class="box-btn">
                    <a class="btn btn-success  btn-sm btn-flat" href="{{route('daily.create')}}">
                    اضافة
                    </a>   
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
                                          <button type="button" class="btn btn-success btn-sm btn-flat" onclick="createTree();"> اضافة</button>
            
                                          <button type="button" class="btn btn-warning btn-sm btn-flat" onclick="renameTree();">اعادة تسميه</button>
                              
                                          <button type="button" class="btn btn-danger btn-sm btn-flat" onclick="deleteTree();">حذف</button>
                                      </div>
                                    </div>


                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <div class="jstree">
                                                {{--  <ul>
                                                <li>ايرادات</li>
                                                <li>مصروفات</li>
                                                </ul>  --}}
                                            </div>
                                        </div>
                                      </div>



                              </div>
                            </div>
                          </div>
                          {{--  <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                          </div>  --}}
                        </div>
                      </div>
                    </div>


{{--  
                    <div >

                     
            
                  </div>



                  

           <!--.jstree-->  --}}


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
<script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
{!! $dataTable->scripts() !!}
@endpush 

@push('scripts')
				<script>
          	function createTree() {
							var ref = $('.jstree').jstree(true),
							sel = ref.get_selected();
							if(!sel.length) { return false; }
							sel = sel[0];
              sel = ref.create_node(sel, {"type":"default"});
							if(sel) {
								ref.edit(sel);
							}
            };
            
						function renameTree() {
							var ref = $('.jstree').jstree(true),
								sel = ref.get_selected();
							if(!sel.length) { return false; }
							sel = sel[0];
							ref.edit(sel);
            };
            
						function deleteTree() {
							var ref = $('.jstree').jstree(true),
								sel = ref.get_selected();
                                if($('.jstree').jstree(true).get_parent(sel) =='#')
                                    return false;
							if(!sel.length) { return false; }
							ref.delete_node(sel);
            };
            
						$(document).ready(function(){

              $('.jstree').jstree({
                "core" : {
                  "animation" : 0,
                  "check_callback" : true,
                  'force_text' : true,
                  "themes" : { "stripes" : true },
                  'data' :{!! json_encode(DB::table('trees')->select(['id','type','icon','parent','text'])->get()) !!}
                },
                "types" : {
                  "#" : { "max_children" : 1, "valid_children" : ["root"] },
                  "root" : { "icon" : "fa  fa-folder-o", "valid_children" : ["default"] },
                  "default" : { "valid_children" : ["default","file"],"icon" : "fa  fa-folder-o" },
                  "file" : { "icon" : "fa fa-file-o", "valid_children" : [] }
                },
                "plugins" : [ "search", "state", "types", "wholerow" ,'dnd']
              });


              $('.jstree').on('rename_node.jstree',function(e , node){
                var id = node['node']['id'];
                var text = node['text']
                  $.ajax({
                  type:'post',
                  url:"{{route('tree.update')}}",
                  data:{
                    id:id,
                    text:text
                  },
                  success:function(data){
                    console.log(data)
                  },
                  error:function(err)
                  {
                    console.log(err)
                  }
                })
              })
              $('.jstree').on('delete_node.jstree',function(e,node,parent){
                var id = node.node.id;
                 var ch = $('.jstree').jstree(true).get_json(node.node, {flat:true,no_state:true,no_id:false,no_children:false,no_li_attr:true,no_a_attr:true,no_data:true})
                 var ids =  ch.map(function(node){ return node = node.id})

                 $.ajax({
                  type:'post',
                  url:"{{route('tree.destroy')}}",
                  data:{
                    ids:ids
                  },
                  success:function(data){
                    console.log(data)
                  },
                  error:function(err)
                  {
                    console.log(err)
                  }
                })
              })
              $('.jstree').on('create_node.jstree', function (e, data) {
               var t = $('.jstree').jstree(true).get_json(data.node, {flat:true,no_state:true,no_id:false,no_children:false,no_li_attr:true,no_a_attr:true,no_data:false})
               var item = t[0]
              $.ajax({
                  type:'post',
                  url:"{{route('tree.store')}}",
                  data:{
                    item:item
                  },
                  success:function(data){
                  },
                  error:function(err)
                  {
                    console.log(err)
                  }
                })
              })

						    
	});
        </script>
@endpush