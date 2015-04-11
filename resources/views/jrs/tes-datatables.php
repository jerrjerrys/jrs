<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

    </head>
    <body>   
        <table id="users" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th class="col-md-3">Username</th>
                    <th class="col-md-3">Email</th>
                    <th class="col-md-3">Created At</th>                                        
                </tr>
            </thead>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {
                oTable = $('#users').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": "/auth/data?app=jrs_auth_db",
                    "columns": [
                        {data: 'username', name: 'username'},
                        {data: 'email', name: 'email'},
                        {data: 'created_at', name: 'created_at'},                        
                    ]
                });
            });
        </script>
    </body>
</html>