<?php
    session_start();
   if( !(isset( $_SESSION['admin'] ) )):
        header('location: ../admin/login.php');
   else:

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIS inpatient</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="../../css/fontawesome/fontawesome.css" rel="stylesheet">
    <link href="../../css/fontawesome/brands.css" rel="stylesheet">
    <link href="../../css/fontawesome/solid.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../../js/jquery-ui-1.13.2/jquery-ui.css">
    <script src="../../js/jquery-3.6.1.js"></script>
    <script src="../../js/jquery-ui-1.13.2/jquery-ui.js"></script>

    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
    <script type="text/javascript" src="js/search_patient.js"></script>
    
    <!-- selection and textbox -->
    <link rel="stylesheet" href="../../css/select2.min.css">
    <link rel="stylesheet" href="../../css/select2.bootstrap.css">
    <script src="../../js/select2.min.js"></script>
    <script>
        $(document).on('select2:open', function(e) {
            document.querySelector(`[aria-controls="select2-${e.target.id}-results"]`).focus();
        });
    </script>
    
    <style>
        body{background-color: #eceff4;}
        .add-discharge-search{
            display:flex;
            justify-content: space-between;
            border-bottom:1px solid #c8c8c8;
            padding: 1%;
        }
        .inpatient-table{
            width:100%;
        }
        .inpatient {
            width: 80%;
            background-color: #ffffff;
            margin: 5% 0 5% 2%;
            padding: 0% 1% 1% 1%; 
        }
        .add-discharge{
            width: 25%;
            margin-left: 35%;
            display:flex;
            justify-content: space-between;
        }
        .patient-dialog{
            width: 80%;
            max-width: 90%;
        }
        .search-input{
            border-left: none;
            border-right: none;
            border-top: none;
        }
        #inpatient-search-details{
            width: 100%;
            margin-top: 2%;
            padding: 0 2% 2% 2%;
        }
        .modal-body label{
            display: inline-block;
            font-family: 'Roboto-Medium';
            font-weight: normal;
            max-width: 100%;
            margin-bottom: 5px;
            box-sizing: border-box;
            font-size: 10pt;
            line-height: 1.42857143;
            color: #333;
        }
        .inpatient-container {
            display: flex;
            justify-content: space-between;
        }
        #inpatient-admission {
            max-width: 40%;
            width: 39%;
            background-color: #f9f9f9;
            padding: 3%;
        }
        small.req{
            color: red;
        }
        .select2-container{
            width: 100%!important;
        }
        .select2-search--dropdown .select2-search__field {
            width: 98%;
        }
    </style>
</head>
<body>
    <?php
        include_once('../../include/admin_sidenav.php');
    ?>
    
    <div class="inpatient">
        <?php
            if( isset($_GET['error']) ):
        ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id = "err ">
            <strong>Error</strong> <?php echo $_GET['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php  
            endif;
        ?>
        <?php
            if( isset($_GET['success']) ):
        ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Successfully </strong>inserted inpatient.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php  
            endif;
        ?>
    
        <div class="add-discharge-search">
                <div class="searchipd">
                    <form method="post">
                    <div class="input-group rounded">
                        <input type="search" name = "search-ipd" class="form-control rounded search-input" placeholder="Search Inpatient" aria-label="Search" aria-describedby="search-addon" />
                        <span style = "background-color: #ffffff" class="input-group-text border-0" id="search-addon">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>    
                    </form>
                </div>

                <div class="add-discharge">
                    <button style = "background-color: #131517; " type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add Patient
                    </button>
                    <a href="#" class = "btn btn-primary" style = "background-color: #131517; margin-left:1%;">Discharged</a>
                
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog patient-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    
                                    <h5 class="modal-title" id="exampleModalLabel">Inpatient</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body" style = "padding: 0;">
                                    <div class="inpatient-container">

                                        <div id="inpatient-search-details">
                                            <!-- search -->
                                            <div style = "display:flex; justify-content: space-between; width: 50%">
                                                <div class="searchipd" style = "width: 70%;">
                                                    <form method="post" id = "search-patient" role = "form">
                                                        <div class="input-group rounded">
                                                            <input type="search" name = "search-patient" class="form-control rounded search-input" placeholder="Search Patient" aria-label="Search" aria-describedby="search-addon" />
                                                            <span style = "background-color: #ffffff" class="input-group-text border-0" id="search-addon">
                                                                <i class="fas fa-search"></i>
                                                            </span>
                                                        </div>    
                                                    </form>
                                                </div>
                                                <button class = "btn btn-primary" style = "font-size: 0.8rem;"><a href="add_patient.php" target="_blank" style = "color:white; style: none; text-decoration: none; display: inline-block">New Patient</a></button>
                                            </div>
                                            <!-- search result and some details to fill -->
                                            <form method='post' id = 'inpatientForm' action = 'add-inpatient.php' >
                                                <div style="display: flex;justify-content: space-between;">
                                                <div id="inpatient-details" style = "margin-top: 3%;width:50%">
                                                <?php
                                                if (!isset($_POST['search-patient']))
                                                    echo "
                                                </div>

                                                
                                            ";
                                                
                                                ?>
                                            <!-- admission -->
                                            <div id="inpatient-admission"
                                            <?php if (!isset($_POST['search-patient']))
                                                echo "style='display:none'";
                                            ?>
                                            >

                                            </div>

                                            </div>
                                    <?php if (!isset($_POST['search-patient']))
                                        echo "</div>";
                                    ?>
                                    </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <input type="submit" value="Add" name="submitInpatient" class="btn btn-primary" style="color: white;">
                                        </div>
                                    </form>
                                    </div>
                                </div>
                                

                            </div>
                        </div>
                    </div>

                
        </div>

        <div class="inpatient-table">
            <?php
                require_once('../../include/mysql_connection.php');
                $stmt = mysqli_stmt_init($connect);
                /////////////////// pagination ///////////////////////////////////
                $limit = 3; // objects limit
                $page = (isset($_GET['page'])) ? (int)$_GET['page']: 1 ; // getting page from post and default 1
                $page = ($page > 0 ) ? $page : 1; // in case entered wrong url get
                
                $query = "SELECT COUNT(*) AS total FROM inpatient_admission";
                $stmt->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                // total pages = ceil( num rows / limit )
                $Rows = $result->fetch_assoc();
                $totalRows = $Rows['total'];
                $totalPages = ceil($totalRows / $limit);
                
                // in case of entered wrong get in url 
                $page = ($page > $totalPages) ? $totalPages : $page;

                //start returning data at
                $startAt = $limit * ($page - 1); 

                $links = "";
                // if pages > ? limit showed page numbers 
                if ($totalPages > 10):
                    $limitPages = 5;
                    $avoidMinus = $limitPages - 2;
                    $awayFromCurrent = ceil($limitPages/2)-1 ;

                    // start navigation bug fixed
                    if ((int)$page < $avoidMinus ):
                        if ( (int)$page == 2 ):
                            $start  = 1;
                            $end    = $limitPages;
                        else:
                            $start  = (int)$page;
                            $end    = (int)$page + ($limitPages-1);
                        endif;
                    else:
                        $start = (int)$page - $awayFromCurrent; //startpage number
                        $end = (int)$page + $awayFromCurrent;
                    endif;

                    // end navigation bug fixed
                    if ( (int)$page == $totalPages-1 ):
                        $start  = (int)$page - (3) ;
                        $end    = $totalPages;
                    else:
                        if ( (int)$page == $totalPages  ):
                            $start = (int)$page - ($limitPages - 1);
                            $end = (int)$page;
                        endif;
                    endif;

                    for ( $i = $start; $i <= $end; $i++)
                        $links .= "
                            <li class = 'page-item'>
                                <a class = 'page-link' href= 'index.php?page=$i'>page $i</a>
                            </li>" ;

                else:
                    for ( $i = 1; $i <= $totalPages; $i++ )
                        $links .= 
                            "<li class = 'page-item'>
                                <a class = 'page-link' href = 'index.php?page=$i'>page $i</a>
                            </li>" ;
            endif;
            /////////////////////////////////////////////////////////////////////      
                $stmt->prepare('SELECT inpatient_id, in_case, doc_id, bed_id 
                                FROM inpatient_admission
                                ORDER BY admission_date
                                LIMIT ?, ?
                            ');
                $stmt->bind_param('ii', $startAt, $limit);
                $stmt->execute();
                $result = $stmt->get_result();
                
            ?>
            <table class="table">
                <thead>
                    <tr>
                    <th scope="col">IPD No</th>
                    <th scope="col">Case ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Consultant</th>
                    <th scope="col">Bed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while($in = $result->fetch_assoc()){
                            $stmt->prepare('SELECT p_id FROM inpatient WHERE id = ?');
                            $stmt->bind_param('i',$in['inpatient_id']);
                            $stmt->execute();
                            $res = $stmt->get_result();
                            $patient = $res->fetch_assoc();
                            $p_id = $patient['p_id'];

                            $stmt->prepare('SELECT name, gender, phone FROM patient WHERE id = ?');
                            $stmt->bind_param('i', $p_id);
                            $stmt->execute();
                            $res = $stmt->get_result();
                            $patient = $res->fetch_assoc();
                            $p_name = $patient['name'];
                            $p_gender = $patient['gender'];
                            $p_phone = $patient['phone'];

                            $stmt->prepare("SELECT CONCAT(firstname, ' ', surname) AS name FROM doctors WHERE id = ?");
                            $stmt->bind_param('i', $in['doc_id']);
                            $stmt->execute();
                            $res = $stmt->get_result();
                            $doc = $res->fetch_assoc();
                            $docName = $doc['name'];

                            $stmt->prepare("SELECT CONCAT( bed_type, ' ', bed_group, ' ', name) AS bedName FROM bed WHERE id = ?");
                            $stmt->bind_param('i', $in['bed_id']);
                            $stmt->execute();
                            $res = $stmt->get_result();
                            $bed = $res->fetch_assoc();
                            $bedName = $bed['bedName'];

                    ?>
                        <tr>
                        <th scope="row"><?php echo $in['inpatient_id']; ?></th>
                        <td><?php echo $in['in_case']; ?></td>
                        <td><?php echo $p_name; ?></td>
                        <td><?php echo $p_gender; ?></td>
                        <td><?php echo $p_phone; ?></td>
                        <td><?php echo $docName; ?></td>
                        <td><?php echo $bedName; ?></td>
                        </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php echo $links;?>
                </ul>
            </nav>
        </div>
    </div>
    <script src="../../js/bootstrap.min.js"></script>
</body>
</html>

<?php endif; ?>