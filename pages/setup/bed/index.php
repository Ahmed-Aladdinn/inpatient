<?php
    session_start();
    if (!isset($_SESSION['admin'])):
        header("Location: ../../admin/login.php");
    else:
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIS Bed</title>
    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
    <link href="../../../css/fontawesome/fontawesome.css" rel="stylesheet">
    <link href="../../../css/fontawesome/brands.css" rel="stylesheet">
    <link href="../../../css/fontawesome/solid.css" rel="stylesheet">

    <script src="../../../js/bootstrap.min.js"></script>
    <script src="../../../js/jquery-3.6.1.js"></script>
    <script src="js/ajax.js"></script>

    <style>
        body {
            background-color: #eceff4;
        }
        .bed{
            width: 80%;
            background-color: #ffffff;
            margin: 5% 0 5% 2%;
            padding: 3% 1% 1% 3%;
        }
        
        .bed-menu{
            height: 80%;
            margin-right: 10%;
        }
        .bed-content{
            height: 80%;
            width: 70%;
        }
        .nav-pills .nav-link.active {
            background-color: #212529;
        }
        .bed .nav-link{
            color :#464646;
        }
        .search-input{
            border-left:none;
            border-right:none;
            border-top:none;
        }
        .search {
            width: 35%;
            margin-bottom: 2%;
        }
    </style>
</head>
<body>
    <?php
        include_once('../../../include/admin_sidenav.php');
    ?>
    <div class="bed">
        <div class="d-flex align-items-start">
            <div class="nav flex-column nav-pills  bed-menu" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-bed-status-tab" data-bs-toggle="pill" data-bs-target="#v-pills-bed-status" type="button" role="tab" aria-controls="v-pills-bed-status" aria-selected="true">Bed status</button>
                <button class="nav-link" id="v-pills-bed-tab" data-bs-toggle="pill" data-bs-target="#v-pills-bed" type="button" role="tab" aria-controls="v-pills-bed" aria-selected="false">Bed</button>
                <button class="nav-link" id="v-pills-bed-group-tab" data-bs-toggle="pill" data-bs-target="#v-pills-bed-group" type="button" role="tab" aria-controls="v-pills-bed-group" aria-selected="false">Bed group</button>
                <button class="nav-link" id="v-pills-bed-type-tab" data-bs-toggle="pill" data-bs-target="#v-pills-bed-type" type="button" role="tab" aria-controls="v-pills-bed-type" aria-selected="false">Bed type</button>
                <button class="nav-link" id="v-pills-Floor-tab" data-bs-toggle="pill" data-bs-target="#v-pills-Floor" type="button" role="tab" aria-controls="v-pills-Floor" aria-selected="false">Floor</button>
            </div>
            <div class="tab-content bed-content" id="v-pills-tabContent">
                
                <!-- bed status -->
                <div class="tab-pane fade show active" id="v-pills-bed-status" role="tabpanel" aria-labelledby="v-pills-bed-status-tab">
                    <div class="search">
                        <form id = 'search-bed-status'>
                            <div class="input-group rounded">
                                <input type="search" name = "search-bed-status" class="form-control rounded search-input" placeholder="Search Inpatient" aria-label="Search" aria-describedby="search-addon" />
                                
                                <span style = "background-color: #ffffff" class="input-group-text border-0" id="search-addon">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>    
                        </form>
                    </div>
                    
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Bed Type</th>
                            <th scope="col">Bed Group</th>
                            <th scope="col">Floor</th>
                            <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id = "bed-status-table">
                            <?php 
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
                                    // can do pagination like inpatient index.php
                                    $stmt->prepare("SELECT name, bed_type, bed_group FROM bed WHERE used = 0");
                                    $stmt->execute();
                                    $bed_result = $stmt->get_result();
                                    $notFound = 1;
                                    if (mysqli_stmt_affected_rows($stmt) > 0):
                                        $notFound = 0;
                                        // function translating 0,1 to Available , not 
                                        function available($used){
                                            return ($used == 0 ) ? 'Available' : 'Not available' ;
                                        }
                                        function trColor($availability){
                                            return ( $availability == 1 ) ? '' : '';
                                        }
                                        while ( $bed = $bed_result->fetch_assoc() ) {
                                            echo "
                                            <tr style = ' background-color:  #e1fde1;'>
                                                <th scope='row'>".$bed['name']."</th>
                                                <td>".$bed['bed_type']."</td>
                                                <td>".$bed['bed_group']."</td>
                                                <td>".$bed_group_arr[$bed['bed_group']]."</td>
                                                <td>Available</td>
                                            </tr>";
                                        }
                                    endif;

                                    $stmt->prepare("SELECT name, bed_type, bed_group, used FROM bed WHERE used = 1");
                                    $stmt->execute();
                                    $bed_result = $stmt->get_result();
                                    if (mysqli_stmt_affected_rows($stmt) == 0 && $notFound == 1 ):
                                        echo "<script> alert('No beds found.'); </script>";
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
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="tab-pane fade" id="v-pills-bed" role="tabpanel" aria-labelledby="v-pills-bed-tab">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-dark" style = "float:right;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add bed
                    </button>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
    
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
    
                                <div class="modal-body">
                                    ...
                                </div>
    
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            </tr>
                            <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                            </tr>
                            <tr>
                            <th scope="row">3</th>
                            <td colspan="2">Larry the Bird</td>
                            <td>@twitter</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="v-pills-bed-group" role="tabpanel" aria-labelledby="v-pills-bed-group-tab">
                    ...
                </div>
                
                <div class="tab-pane fade" id="v-pills-bed-type" role="tabpanel" aria-labelledby="v-pills-bed-type-tab">
                    ...
                </div>
            
                <div class="tab-pane fade" id="v-pills-Floor" role="tabpanel" aria-labelledby="v-pills-Floor-tab">
                    ...
                </div>

            </div>
        </div>

    </div>

</body>
</html>
<?php endif; ?>