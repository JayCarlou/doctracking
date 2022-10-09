<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel DataTables Tutorial Example</title>
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    </head>

    <body>
        <div class="container">
            <br/>
            <h1 class="text-center">HDTuto - Laravel DataTables Tutorial Example</h1>
            <br/>

            <table class="table table-bordered" id="users-table">

                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>

                </thead>
                <tr>
                    <td>jay</td>
                    <td>asdf</td>
                    <td>asdf</td>
                    <td>asdf</td>
                    <td>asdf</td>
                </tr>
                <tr>
                    <td>asdf</td>
                    <td>asdf</td>
                    <td>asdf</td>
                    <td>asdf</td>
                    <td>asdf</td>
                </tr>
                <tr>
                    <td>asdf</td>
                    <td>asdf</td>
                    <td>asdf</td>
                    <td>asdf</td>
                    <td>asdf</td>
                </tr>
            </table>

        </div>

        <script src="//code.jquery.com/jquery.js"></script>

        <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

        <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

        <script>

        $(function() {

            $('#users-table').DataTable({

                
            });

        });

        </script>

        @stack('scripts')

    </body>

</html>