<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{config('app.name')}}</title>
  
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('adminlte/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <link rel="shortcut icon" href="{{ asset('images/logo-2.png') }}" type="image/png">
  <!-- Theme style -->
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset('adminlte/dist/css/skins/_all-skins.min.css')}}">
  <link href="https://fonts.googleapis.com/css?family=Cairo" rel="stylesheet">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js')}}"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
  <![endif]-->

  <!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<link rel="stylesheet" href="{{asset('adminlte/bower_components/adminlte-rtl/dist/css/AdminLTE-rtl.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/bower_components/adminlte-rtl/dist/css/skins/_all-skins-rtl.min.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/bower_components/bootstrap-rtl/dist/css/bootstrap-rtl.min.css')}}">

  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
    <style>
      body{
        font-family: 'Cairo', sans-serif;
      }
      .login-page{
        background: url({{asset('images/login.jpeg')}});
        background-size: cover;
        background-repeat: no-repeat;
    }
    </style>

    <style>
    .overlay-loader {
    background: #fff;
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
    </style>

</head>

<body class="hold-transition login-page">

 
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


<div class="login-box">
  <div class="login-logo">
   <!--{{config('app.name')}}-->
   <img style="width: 250px;height: 100px;background-color: white;border-radius: 10px;" src="{{ asset('images/logo-2.png') }}" class="w3-center w3-round"  width="90px" >
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">تسجيل الدخول</p>
    
    <form action="{{route('login')}}" method="post">
    {{csrf_field()}}
        @include('flash::message')        
      <div class="form-group has-feedback {{$errors->has('username')?'has-error':''}}">
        <input type="text" name="username" class="form-control" placeholder="اسم المستخدم" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback  {{$errors->has('username')?'has-error':''}}">
        <input type="password" name="password" class="form-control" placeholder="كلمة المرور" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

      </div>
      <div class="row">
        <div class="col-xs-6">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember"> تذكرني
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <button type="submit" class="btn btn-primary btn-block btn-flat "> الدخول</button>
        </div>
        <!-- /.col -->
      </div>
    </form>


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<!-- jQuery 3 -->
<script src="{{asset('adminlte/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- jquery validate -->
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<!-- jquery validate localization -->
<script src="{{asset('js/messages_ar.js')}}"></script>
<script>
  $(function () {

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

  

        $('.loader').fadeOut(1000,function(){
          $('.overlay-loader').fadeOut(function(){
            $(this).remove();
          })
        });


    $('form').validate();
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
