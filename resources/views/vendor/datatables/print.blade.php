<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{config('app.name')}} </title>
        <meta charset="UTF-8">
        <meta name=description content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap CSS -->
        <link href="https://fonts.googleapis.com/css?family=Cairo" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{asset('adminlte/bower_components/bootstrap-rtl/dist/css/bootstrap-rtl.min.css')}}">
        <style>
            body {
                margin: 20px;
                font-family: 'Cairo', sans-serif;
            }
        </style>
    </head>
    <body>
    @if(count($data))    
        <table class="table table-bordered table-condensed table-striped">
                    <tr>
                        @foreach($data[0] as $key => $value)
                            <th>{!! $key !!}</th>
                        @endforeach
                    </tr>
                <tr>
                 @foreach($data as $row)
                    @foreach($row as $key => $value)
                        @if(is_string($value) || is_numeric($value))
                            <td>{!! $value !!}</td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
        @endif
        <footer style="text-align:center">
                             جميع الحقوق محفوظة لشركة سفنكس للتكنولوجيا المتقدمة 01000122247
        </footer>
        <script>
                window.onload = function(){ print() }
        </script>
    </body>
</html>
