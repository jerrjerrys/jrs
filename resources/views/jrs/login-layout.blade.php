<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">        

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">               
        <link href="{!! asset('assets/admin-lte/dist/css/AdminLTE.min.css') !!}" rel="stylesheet" type="text/css" />

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>        

    </head>
    <body class="login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="../../index2.html"><b>Admin</b>LTE</a>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                @include('jrs.notify.alert')                
                @if(!empty($form))                
                {!! form($form) !!}
                @endif                 
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->        
    </body>
</html>
