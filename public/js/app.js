$(document).ready(function(){
    
 
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    $('.file-upload').file_upload();
    $(".fancybox").fancybox();

    $( document ).ajaxSend(function() {
        $('.btn .fa-check').parent().prop('disabled',true)
        $('[type=submit]').prop('disabled',true);
      });

      $( document ).ajaxComplete(function() {
        $('.btn .fa-check').parent().prop('disabled',false)
        $('[type=submit]').prop('disabled',false);
      });
      
  $('.validate').on('submit',function(e){
         if($(this).valid() &&  !$(this).validate().pendingRequest ){
           $('[type=submit]').prop('disabled',true);
         } else {
           $('[type=submit]').prop('disabled',false);
         }
       })

    $.validator.setDefaults({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error').removeClass('has-success');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });


    //triger datepicker plugin
    $('.date').datepicker({
        autoclose: true,
        todayHighlight: true,
        format:'yyyy-mm-dd',
        rtl:true,
        orientation: "bottom",
        language:'ar'
    });


    $('.date-min-view').datepicker({
        autoclose: true,
        todayHighlight: true,
        minViewMode:1,
        format:'yyyy-mm',
        rtl:true,
        language:'ar'
    });



    // datatables
    /*
    $('.data-tables').each(function(index , item){
        var table = $(item).DataTable({
            // language :{
                    //   url: '/vendor/datatables/arabic.json'
            // },
            buttons: [
                'copy', 'excel','colvis',
                {
                    extend:'print',
                    title:function(){
                        return 'بن عبدالمعبود';
                    },
                    customize:function(win){
                        $(win.document.body).append(`
                        <footer style="text-align:center">
                             جميع الحقوق محفوظة لشركة سفنكس للتكنولوجيا المتقدمة 01000122247
                        </footer>
                        `)
                    }
                }
            ]
        });
    
         table.buttons().container().appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );
    });
  
    $(".dataTable").DataTable({
             language :{
                       url: '/vendor/datatables/arabic.json'
             },
            buttons: [
                'copy', 'excel','colvis',
                {
                    extend:'print'
                }
            ]
        });
    */
    
    $('select').select2({width:'100%',dir:'rtl'});


    $(document).on('click','.confirm',function(e){
        e.preventDefault();
        var that = $(this);
        $.confirm({
            icon:'',
            draggable:false,
            theme: 'modern',
            title: '',
            content: 'هل انت متاكد من حذف هذا العنصر',
            type: 'red',
            typeAnimated: true,
            buttons: {
                ok: {
                    text: 'موافق',
                    btnClass: 'btn-red',
                    action: function(){
                        that.parent('form').unbind('submit').submit();
                    }
                },
                close: {
                    text: 'الغاء',
                    action: function(){
                        that.parent('form').unbind('submit');
                    }
                }
            }
        });
    })


    // var viewportHeight= $(window).height();
    // $("body").slimScroll({
    //     size: '8px', 
    //     width: '100%', 
    //     height: '100%', 
    //     color: '#ff4800', 
    //     allowPageScroll: true, 
    //     alwaysVisible: true     
    //   });


$('.datatable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "order": [[0, "asc"]]
    }); 
$('.data-tables').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "order": [[0, "asc"]]
    });
     
$('.datatable-no-paging').DataTable({
        paging: false,
        serverSide: false,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "order": [[0, "asc"]]
    }); 
    
    
})