<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">        

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">                

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>        

    </head>
    <body>        
        <div class="container-fluid">
            <br/>
            <blockquote>
                <p>Jrs Library Development Phase</p>
                <footer><cite title="Development Phase">Development Phase</cite></footer>
            </blockquote>     

            @include('jrs.menu.menu')

            <hr/>            

            @if(!empty($form))
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-6">

                    @include('jrs.notify.alert')

                    <div class="panel panel-info">
                        <div class="panel-heading">Form</div>
                        <div class="panel-body">
                            {!! form($form) !!}                                                        
                        </div>                
                    </div>
                </div>
            </div>
            @endif    



            @if(!empty($table))

            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="panel panel-info">
                        <div class="panel-heading">Table</div>
                        <div class="panel-body">
                            @include('jrs.table.new-table',['table'=>$table])
                        </div>                
                    </div>   
                </div>
            </div>
            @endif

            <hr/>

            <pre>@if(Session::has('db_connection')) You're logged in @else You're not logged in @endif</pre>
        </div>                
    </body>
</html>
