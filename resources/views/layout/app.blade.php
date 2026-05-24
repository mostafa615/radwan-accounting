<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{config('app.name')}}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    @include('partials.styles')
    @stack('styles')
    <style>

        .tooltip-inner {
            font-size: 15px !important;
            font-family: 'Cairo';
        }
        .overlay-loader {
            background: white;
            display: block;
            margin: auto;
            width: 90px;
            height: 100%;
            z-index: 99999;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .loader {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            width: 90px;
            height: 90px;
            animation-name: rotateAnim;
            -o-animation-name: rotateAnim;
            -ms-animation-name: rotateAnim;
            -webkit-animation-name: rotateAnim;
            -moz-animation-name: rotateAnim;
            animation-duration: 0.3175s;
            -o-animation-duration: 0.3175s;
            -ms-animation-duration: 0.3175s;
            -webkit-animation-duration: 0.3175s;
            -moz-animation-duration: 0.3175s;
            animation-iteration-count: infinite;
            -o-animation-iteration-count: infinite;
            -ms-animation-iteration-count: infinite;
            -webkit-animation-iteration-count: infinite;
            -moz-animation-iteration-count: infinite;
            animation-timing-function: linear;
            -o-animation-timing-function: linear;
            -ms-animation-timing-function: linear;
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
        }

        .loader div {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            border: 1px solid #3c8dbc;
            position: absolute;
            top: 2px;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
        }

        .loader div:nth-child(odd) {
            border-top: none;
            border-left: none;
        }

        .loader div:nth-child(even) {
            border-bottom: none;
            border-right: none;
        }

        .loader div:nth-child(2) {
            border-width: 2px;
            left: 0px;
            top: -4px;
            width: 11px;
            height: 11px;
        }

        .loader div:nth-child(3) {
            border-width: 2px;
            left: -1px;
            top: 3px;
            width: 16px;
            height: 16px;
        }

        .loader div:nth-child(4) {
            border-width: 3px;
            left: -1px;
            top: -4px;
            width: 22px;
            height: 22px;
        }

        .loader div:nth-child(5) {
            border-width: 3px;
            left: -1px;
            top: 4px;
            width: 29px;
            height: 29px;
        }

        .loader div:nth-child(6) {
            border-width: 4px;
            left: 0px;
            top: -4px;
            width: 36px;
            height: 36px;
        }

        .loader div:nth-child(7) {
            border-width: 4px;
            left: 0px;
            top: 5px;
            width: 45px;
            height: 45px;
        }

        @keyframes rotateAnim {
            from {
                transform: rotate(360deg);
            }
            to {
                transform: rotate(0deg);
            }
        }

        @-o-keyframes rotateAnim {
            from {
                -o-transform: rotate(360deg);
            }
            to {
                -o-transform: rotate(0deg);
            }
        }

        @-ms-keyframes rotateAnim {
            from {
                -ms-transform: rotate(360deg);
            }
            to {
                -ms-transform: rotate(0deg);
            }
        }

        @-webkit-keyframes rotateAnim {
            from {
                -webkit-transform: rotate(360deg);
            }
            to {
                -webkit-transform: rotate(0deg);
            }
        }

        @-moz-keyframes rotateAnim {
            from {
                -moz-transform: rotate(360deg);
            }
            to {
                -moz-transform: rotate(0deg);
            }
        }

        /*loader in modal*/
        .box .overlay, .overlay-wrapper .overlay {
            z-index: 50;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 3px;
            position: absolute;
            height: 100%;
            width: 100%;
        }
    </style>
    <script src="https://js.pusher.com/4.3/pusher.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body id="body" class="hold-transition skin-blue fixed  sidebar-mini sidebar-mini-expand-feature ">
<div class="overlay-loader">
    <div class="loader">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
<span class="wrapper">
  <header class="main-header">
    <a href="{{route('home')}}" class="logo">
      <span class="logo-mini">S</span>
      <span class="logo-lg"><b>Steel</b></span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            
            @if($perms->contains('invoices_requests'))
                <li data-toggle="tooltip" data-placement="bottom" title="طلبات الفواتير" class="dropdown">
                    <a href="{{route('pending-orders.index')}}">
                    <i class="fa fa-bell"></i>
                    <span class="label label-success">{{\App\Models\Order::where('status', 'pending')->where('branch_id', auth()->user()->branch_id)->count()}}</span>
                    </a>
                </li>
            @endif

            {{-- @if( $perms->contains('store_responsible'))
            <li data-toggle="tooltip" data-placement="bottom" title="مسئوول مخزن" class="dropdown">
                <a href="{{route('pending-load.index')}}">
                    <i class="fa fa-truck"></i>
                    @if(!auth()->user()->hasRole('admin'))
                    <span class="label label-success">{{\App\Models\Load::whereHas('loadDetails', function ($query) {
                        $query->where('pending', 1);
                    })->where('to_id', auth()->user()->store_id)->count()}}</span>
                    @else
                    <span class="label label-success">{{\App\Models\Load::whereHas('loadDetails', function ($query) {
                        $query->where('pending', 1);
                    })->count()}}</span>
                    @endif
                </a>
            </li>
            @endif --}}
            
            @if($perms->contains('materials_transfer_requests'))
            <li data-toggle="tooltip" data-placement="bottom" title="التحميل بين المخازن" class="dropdown">
                <a href="{{route('loads.pending.index')}}">
                    <i class="fa fa-truck"></i>
                    @if(!auth()->user()->hasRole('admin'))
                    <span class="label label-success">{{\App\Models\Load::where('status', 'accepted')->whereHas('loadDetails', function ($query) {
                        $query->where('status', 'pending');
                    })->where('to_id', auth()->user()->store_id)->count()}}</span>
                    @else
                    <span class="label label-success">{{\App\Models\Load::where('status', 'accepted')->whereHas('loadDetails', function ($query) {
                        $query->where('status', 'pending');
                    })->count()}}</span>
                    @endif
                </a>
            </li>
            @endif

            @if($perms->contains('check_materials_transfer_requests'))
            <li data-toggle="tooltip" data-placement="bottom" title="فحص التحميل بين المخازن" class="dropdown">
                <a href="{{route('loads.check.index')}}">
                    <i class="fa fa-check-square-o"></i>
                    <span class="label label-success">{{\App\Models\Load::where('status', 'pending')->whereHas('loadDetails', function ($query) {
                        $query->where('status', 'pending');
                    })->where('from_id', auth()->user()->store_id)->count()}}</span>
                </a>
            </li>
            @endif
            
            @if($perms->contains('reposite_responsible'))
                <li data-toggle="tooltip" data-placement="bottom" title="مسئوول خزنة" class="dropdown">
            <a href="{{route('pending-pays.index')}}" class="dropdown-toggle">
              <i class="fa fa-money"></i>
              <span class="label label-success">{{$pendingAccounts}}</span>
            </a>
          </li>
            @endif
            @if($perms->contains('can_accept_price_requests'))
                <li data-toggle="tooltip" data-placement="bottom" title="طلبات الاسعار" class="dropdown">
            <a href="{{route('pending-price.index')}}">
              <i class="fa fa-lock"></i>
              <span class="label label-success">{{$pendingPrices}}</span>
            </a>
          </li>
            @endif
            <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{asset('images/logo-2.png')}}" class="user-image" alt="User Image" style="background-color: white;">
              <span class="hidden-xs">{{auth()->user()->name}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="{{asset('images/logo-2.png')}}" class="img-circle" alt="User Image" style="background-color: white;">
                <p>
                  {{auth()->user()->name}} - {{optional(auth()->user()->type)->display_name}}
                </p>
              </li>
              <li class="user-footer">
                  <div class="pull-right">
                    <form action="{{route('logout')}}" method="post">
                            {{csrf_field()}}
                        <button type="submit" class="btn btn-default btn-flat">تسجيل الخروج
                    </button>
                    </form>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-right image">
          <img src="{{asset('images/logo-2.png')}}" class="img-circle" alt="User Image" style="background-color: white;">
        </div>
        <div class="pull-left info">
          <p>{{auth()->user()->name}}
         
          </p>
          <a href="#"><i class="fa fa-circle text-success"></i> {{optional(auth()->user()->type)->display_name}}</a>
        </div>
      </div>
        @include('partials.side-bar')
    </section>
  </aside>
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        @yield('title')
          <small>@yield('sub-title')</small>
      </h1>
    </section>
    <section class="content">
        @include('flash::message')
        @yield('content')
    </section>
  </div>
  <footer class="main-footer">
      <!-- <span>
        <img class="img-circle sphinxat" src="{{ asset('images/logo.gif') }}">
      </span>
      <span>
          جميع الحقوق محفوظة لشركة <strong>سفنكس</strong> للتكنولوجيا المتقدمة 01000122247
      </span> -->
    </footer>
  <div class="control-sidebar-bg"></div>
    @include('partials.scripts')
    @stack('scripts')
    @yield('footer')
    <script>
  $(document).ready(function () {
      $('[data-toggle="tooltip"]').tooltip();
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': '{{csrf_token()}}'
          }
      });
      $('.loader').fadeOut(500, function () {
          $('.overlay-loader').fadeOut(function () {
              $(this).remove();
          });
      });
  });
  Pusher.logToConsole = true;
  var pusher = new Pusher('e75d58425f4b10f93cfb', {
      cluster: 'eu',
      forceTLS: true
  });
  var audio = new Audio('{{url('audio/')}}'+'/notification.mp3');
  var channel = pusher.subscribe('my-channel');
  channel.bind('my-event', function (data) {
      if ('{{Auth()->user()->id}}' == data.message.user_id) {
          audio.play();
          swal(data.message.message);
            //   .then(function () {
            //      window.location=data.message.url;
            //   });

      }
  });
  var channel2 = pusher.subscribe('my-channel2');
  channel2.bind('my-event2', function (data) {
    if('{{Auth()->user()->id}}' == data.message.user_id) {
        audio.play();
        swal(data.message.message).then(function () {
            window.location.reload();
        });
    }
  });
  
  $(document).ready(function () {
      $('#example_1').dataTable({
          dom: 'Bfrtip',
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
          paging:false
      });
      $('.select_2').select2();
  });

</script>
    <script>
    $('#example_2').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        paging:false
    });
    $('#example_3').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        paging:false
    });
    $('#example_4').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        paging:false
    });
    $('#example_5').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        paging:false
    });
    $('#example_6').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        paging:false
    });
    $('#example_7').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        paging:false
    });
    $('#example_8').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        paging:false
    });
    $('#example_9').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        paging:false
    });
    $('#example_10').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        paging:false
    });
    $('#example_15').dataTable({
        dom: 'rtp',
        paging:false
    });
</script>
@yield("scripts")
</body>
</html>
