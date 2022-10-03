<?php 
    session_start();
    if ( isset($_SESSION['admin']) ):
        include_once "../../include/mysql_connection.php";
        if(isset($_POST['search-patient'])):
            if ( empty($_POST['search-patient']) ):
                echo "<script> alert('search can't be empty.'); </script>";
            else:
                $searchName = "%".strip_tags($_POST['search-patient'])."%";
                $searchID = strip_tags($_POST['search-patient']);
                $query = "SELECT name, id, gaurdian, gender, profile, blood, marital_status, 
                            TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) AS age,
                            phone, email, address, allergies, remarks, tpa_id, tpa_validity, national_num FROM patient 
                            WHERE (name LIKE ?) OR (id = ?)";
                $stmt = mysqli_stmt_init($connect);
                $stmt->prepare($query);
                $stmt->bind_param('ss', $searchName, $searchID);
                $stmt->execute();
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                if (mysqli_stmt_affected_rows($stmt) > 0):
                    $Pname = $row['name'];
                    $Pid = $row['id'];
                    $_SESSION['patient_id'] = $Pid;
                    $gaurdian = $row['gaurdian'];
                    $Pgender = $row['gender'];
                    $Pmarital = $row['marital_status'];
                    $Pblood = $row['blood'];
                    $age = $row['age'];
                    $phone = $row['phone'];
                    $email = $row['email'];
                    $address = $row['address'];
                    $allergies = $row['allergies'];
                    $remarks = $row['remarks'];
                    $tpa_id = $row['tpa_id'];
                    $tpa_validity = $row['tpa_validity'];
                    $national_num = $row['national_num'];
                    echo "
                    <h4>$Pname ($Pid)</h4>
                    <div>
                    <strong class = 'patient-details'>Gaurdian: </strong>
                    <span class = 'patient-details'>$gaurdian<span>
                    </div>
                    <div>
                    <strong class = 'patient-details'>Gender: </strong>
                    <span class = 'patient-details'>$Pgender<span>
                    <strong class = 'patient-details'>Marital: </strong>
                    <span class = 'patient-details'>$Pmarital<span>
                    <strong class = 'patient-details'>Blood: </strong>
                    <span class = 'patient-details'>$Pblood<span>
                    </div>
                    <div>
                    <strong class = 'patient-details'>Age: </strong>
                    <span class = 'patient-details'>$age<span>
                    </div>
                    <div>
                    <strong class = 'patient-details'>Phone: </strong>
                    <span class = 'patient-details'>$phone<span>
                    </div>
                    <div>
                    <strong class = 'patient-details'>Email: </strong>
                    <span class = 'patient-details'>$email<span>
                    </div>
                    <div>
                    <strong class = 'patient-details'>Address: </strong>
                    <span class = 'patient-details'>$address<span>
                    </div>
                    <div>
                    <strong class = 'patient-details'>Any Known Allergies: </strong>
                    <span class = 'patient-details'>$allergies<span>
                    </div>
                    <div>
                    <strong class = 'patient-details'>Remarks: </strong>
                    <span class = 'patient-details'>$remarks<span>
                    </div>
                    <div>
                    <strong class = 'patient-details'>TPA ID: </strong>
                    <span class = 'patient-details'>$tpa_id<span>
                    </div>
                    <div>
                    <strong class = 'patient-details'>TPA Validity: </strong>
                    <span class = 'patient-details'>$tpa_validity<span>
                    </div>
                    <div>
                    <strong class = 'patient-details'>National Identification Number: </strong>
                    <span class = 'patient-details'>$national_num<span>
                    </div>
                    <hr>";
                    echo "<div class='row'>
                            <div class='col-md-12'> 
                                <div class='dividerhr'></div>
                            </div>
                            <!--./col-md-12-->

                            <!-- Height -->
                            <div class='col-sm-2 col-xs-4'>
                                <div class='form-group'>
                                    <label for='pwd'>Height</label> 
                                    <input name='height' type='text' class='form-control' autocomplete='off'>
                                </div>
                            </div>

                            <!-- Weight -->
                            <div class='col-sm-2 col-xs-4'>
                                <div class='form-group'>
                                    <label for='pwd'>Weight</label> 
                                    <input name='weight' type='text' class='form-control' autocomplete=off>
                                </div>
                            </div>

                            <!-- BP -->
                            <div class='col-sm-2 col-xs-4'>
                                <div class='form-group'>
                                    <label for='pwd'>BP</label> 
                                    <input name='bp' type='text' class='form-control' autocomplete=off>
                                </div>
                            </div>

                            <!-- Pulse -->
                            <div class='col-sm-2 col-xs-4'>
                                <div class='form-group'>
                                    <label for='pwd'>Pulse</label> 
                                    <input name='pulse' type='text' class='form-control' autocomplete='off'>
                                </div>
                            </div>

                            <!-- Temperature -->
                            <div class='col-sm-2 col-xs-4'>
                                <div class='form-group'>
                                    <label for='pwd'>Temperature</label> 
                                    <input name='temperature' type='text' class='form-control' autocomplete=off>
                                </div>
                            </div>

                            <!-- Respiration -->
                            <div class='col-sm-2 col-xs-4'>
                                <div class='form-group'>
                                    <label for='pwd'>Respiration</label> 
                                    <input name='respiration' type='text' class='form-control' autocomplete=off>
                                </div>
                            </div>

                            <div class='col-sm-3 col-xs-4'>
                                
                                <!-- Symptoms Type -->
                                <div class='form-group'>
                                        <label for='exampleInputFile'>Symptoms Type</label>
                                        <div>
                                            <select name='symptoms_type' id='symptom-type' class='form-control selectio2n-org' style='width:100%'>
                                                <option value=''>Select</option>";  
                                                $stmt->prepare('SELECT DISTINCT type FROM symptoms');
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                while ($symptom = $result->fetch_assoc()) {
                                                    echo "
                                                        <option value = ".$symptom['type']." >".$symptom['type']."</option>
                                                    ";
                                                }
                                                echo "
                                            </select>
                                            <!-- AJAX PHP getting symptom select2 -->
                                            <script>
                                                $('select#symptom-type').on('change', function() {
                                                    var symptom = $(this).val();
                                                    $.ajax({
                                                        method: 'POST',
                                                        url: 'symptoms_title_selector.php',
                                                        data: ( { symptom: symptom } ),
                                                        success: function(response) {
                                                            $('#symptom-title').html(response);
                                                        },
                                                        error: function() {
                                                            alert('Error');
                                                        }
                                                    });
                                                });
                                            </script>

                                        </div>
                                        <span class='text-danger'></span>
                                    </div>
                                </div>

                                <!-- Symptoms Title -->
                                <div class='col-sm-3 col-xs-4'>
                                    <label for='exampleInputFile'>Symptoms Title</label>
                                        <select name='symptoms_title' id='symptom-title' class='form-control selectio2n-org' style='width:100%'>
                                            <option value=''>Select</option>
                                        </select>
                                </div>

                                <!-- Symptoms Description -->
                                <div class='col-sm-6 col-xs-4'>
                                    <div class='form-group'>
                                        <label>Symptoms Description</label>
                                        <textarea class='form-control' id='symptoms_description' name='symptoms_description'></textarea> 
                                    </div> 
                                </div>
                                
                                <!-- Note -->
                                <div class='col-sm-12'>
                                    <div class='form-group'>
                                        <label for='pwd'>Note</label> 
                                        <textarea name='note' rows='3' class='form-control'></textarea>
                                    </div>
                                </div>

                                <div class=''></div>      
                            </div>
                    ";
                else:
                    echo "<script> alert('not found');
                        $('#inpatient-admission').css('display', 'none');
                    </script>";
                endif;
            endif;
        endif;
    else:
        header('Location: ../admin/login.php');
    endif;
?>