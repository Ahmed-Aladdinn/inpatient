<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../js/jquery-ui-1.13.2/jquery-ui.css">
    <script src="../../js/jquery-3.6.1.js"></script>
    <script src="../../js/jquery-ui-1.13.2/jquery-ui.js"></script>
    <title>Document</title>
    <script>
        $( function() {
            $( '#datepicker' ).datepicker({
                format:'mm/dd/yyyy',
                changeYear: false
            }).datepicker('setDate','now');
        } );
    </script>
</head>
<body>
    <!-- <form method="post"> -->
        <form>
            <input type='text' name = 'datepicker' id='datepicker' class = 'form-control'>
            <input type="submit" value="">
        </form>
    <!-- </form> -->
    <?php
        function yesNo($YN){
            switch ($YN) {
                case 'true':
                    return true;
                    break;
                
                case 'false':
                    return true;
                    break;
                
                default:
                   return false;
                    break;
            }
        }

        $t = 'true';

        if (yesNo($t) == true)
            echo 'true';

        echo intval(true);
    ?>
</body>
</html>