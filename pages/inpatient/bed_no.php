<?php
    session_start();
    if (isset($_SESSION['admin'])):

        if(isset($_POST['bed_group'])):
            $bed_group = strip_tags($_POST['bed_group']);
            require_once "../../include/mysql_connection.php";

            $stmt = mysqli_stmt_init($connect);
            $stmt->prepare('SELECT id, name, bed_group FROM bed WHERE bed_group = ? AND used = 0');
            $stmt->bind_param('s', $bed_group);
            $stmt->execute();
            $result = $stmt->get_result();
            echo "<option value = ''> Select</option>";
            while ( $bed = $result->fetch_assoc() ) {
                echo "
                    <option value = ".$bed['id'].">".$bed['name']."</option>
                ";
            }
        else:
            header('location: ../admin/login.php');
            exit();
        endif;

    else:
        header('Location: ../admin/login.php');
        exit();
    endif;
?>