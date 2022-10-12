<?php
    session_start();
    if ( isset($_SESSION['admin']) ):
        if ( isset($_GET['search-bed-status']) ):
            $bed_name = strip_tags($_GET['search-bed-status']);

            require_once('../../../include/mysql_connection.php');
            $stmt = mysqli_stmt_init($connect);

            // getting all floors floor = $bed_group_arr['bed_group']
            $stmt->prepare("SELECT name, floor FROM bed_group");
            $stmt->execute();
            $result = $stmt->get_result();
            if (mysqli_stmt_affected_rows($stmt) > 0):
                $bed_group_arr = [];

                while ($bed_group = $result->fetch_assoc()){
                    $bed_group_arr[$bed_group['name']] = $bed_group['floor'];
                }

                $stmt->prepare("SELECT name, bed_type, bed_group FROM bed WHERE ( name LIKE ? ) AND ( used = 0 ) ");
                $bed_name = '%'.$bed_name.'%';
                $stmt->bind_param('s', $bed_name);
                $stmt->execute();
                $bed_result = $stmt->get_result();
                $notFound = 1;
                if (mysqli_stmt_affected_rows($stmt) > 0):
                    $notFound = 0;
                   
                    while ( $bed = $bed_result->fetch_assoc() ) {
                        echo "
                        <tr style = ' background-color: #e1fde1; '>
                            <th scope='row'>".$bed['name']."</th>
                            <td>".$bed['bed_type']."</td>
                            <td>".$bed['bed_group']."</td>
                            <td>".$bed_group_arr[$bed['bed_group']]."</td>
                            <td>Available</td>
                        </tr>";
                    }
                endif;

                $stmt->prepare("SELECT name, bed_type, bed_group FROM bed WHERE ( name LIKE ? ) AND ( used = 1 ) ");
                $stmt->bind_param('s', $bed_name);
                $stmt->execute();
                $bed_result = $stmt->get_result();
                if (mysqli_stmt_affected_rows($stmt) == 0 && $notFound == 1):
                    echo "<script> alert('No bed with name like that.'); </script>";
                else:
                    while ( $bed = $bed_result->fetch_assoc() ) {
                        echo "
                        <tr style = ' background-color: #ffe4e4; '>
                            <th scope='row'>".$bed['name']."</th>
                            <td>".$bed['bed_type']."</td>
                            <td>".$bed['bed_group']."</td>
                            <td>".$bed_group_arr[$bed['bed_group']]."</td>
                            <td>Not available</td>
                        </tr>";
                    }
                endif;
            else:
                echo "<script> alert('No bed groups found.'); </script>";
            endif;
        else:
            header("Location: index.php");
        endif;
    else:
        header("Location: ../../admin/login.php");
    endif;
?>