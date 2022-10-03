<?php
    session_start();
    if (isset($_SESSION['admin'])):

        if(isset($_POST['symptom'])):
            $symptom_type = strip_tags($_POST['symptom']);
            require_once "../../include/mysql_connection.php";

            $stmt = mysqli_stmt_init($connect);
            $stmt->prepare('SELECT title FROM symptoms WHERE type = ?');
            $stmt->bind_param('s', $symptom_type);
            $stmt->execute();
            $result = $stmt->get_result();
            while ( $symptom_title = $result->fetch_assoc() ) {
                echo "
                    <option value = ".$symptom_title['title'].">".$symptom_title['title']."</option>
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