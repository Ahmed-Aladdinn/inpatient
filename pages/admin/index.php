<?php
    session_start();
   if( !(isset( $_SESSION['admin'] ) )):
        header('location: login.php');
   else:

?>
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link rel="stylesheet" href="../../css/bootstrap.min.css">
            <link href="../../css/fontawesome/fontawesome.css" rel="stylesheet">
            <link href="../../css/fontawesome/brands.css" rel="stylesheet">
            <link href="../../css/fontawesome/solid.css" rel="stylesheet">
            <title>HIS Admin</title>
        </head>
        <body>
            <?php include_once('../../include/admin_sidenav.php') ?>
            <div class="col py-3">
                Content area...
                <hr>
                
            </div>

            <script src="../../js/bootstrap.min.js"></script>
        </body>
        </html>
<?php
    endif;
?>