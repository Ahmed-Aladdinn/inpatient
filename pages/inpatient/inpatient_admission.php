<?php
session_start();
if (isset($_SESSION['admin'])):
    
    include_once "../../include/mysql_connection.php";

    if(isset($_POST['search-patient'])):
        if ( empty($_POST['search-patient']) ):
            // echo "<script> alert('search can't be empty.'); </script>";
        else: 
            $query = "SELECT name FROM patient WHERE name LIKE ? OR id = ?";
            $stmt = mysqli_stmt_init($connect);
            $stmt->prepare($query);
            $searchName = "%".strip_tags($_POST['search-patient'])."%";
            $searchID = strip_tags($_POST['search-patient']);
            $stmt->bind_param('ss', $searchName, $searchID);
            $stmt->execute();
            $result = $stmt->get_result();
            $patient = mysqli_fetch_assoc($result);
            if (mysqli_stmt_affected_rows($stmt) > 0):
                // getting TPA from patient table
                $Pid = $_SESSION['patient_id'];
                $stmt->prepare('SELECT tpa_id FROM patient WHERE id = ?');
                $stmt->bind_param('i', $Pid);
                $stmt->execute();
                $result = $stmt->get_result();
                $TPA = $result->fetch_assoc();
                $TPA = $TPA['tpa_id'];
                //getting currency limit from currency_setting table
                $stmt->prepare('SELECT curr_limit, symbol  FROM currency_setting');
                $stmt->execute();
                $result = $stmt->get_result();
                $currency = $result->fetch_assoc();
                $currency_limit = $currency['curr_limit'];
                $currency_symbol = $currency['symbol'];

                echo "<div class='row'>

                <!-- Appointment-date --!>
                <div class='col-sm-12'>

                    <!-- setting date picker -->
                    <script>
                        $( function() {
                            $( '#datepicker' ).datepicker({
                                format:'mm/dd/yyyy',
                                changeYear: false
                            }).datepicker('setDate','now');
                        } );
                    </script>
                    <div class='form-group'>
                        <label id = 'tstt'>Admission Date</label><small class='req'> *</small>

                        <input type='text' name = 'datepicker' id='datepicker' class = 'form-control'>
                        <br>
                        <input type = 'time' name = 'ad_time' value='now' id = 'myTime' class = 'form-control'>
                        <span class='text-danger'></span>
                    </div>
                    <!-- setting time picker -->
                    <script>
                        var d = new Date(),
                        h = d.getHours(),
                        m = d.getMinutes();
                        if (h < 10) h = '0' + h;
                        if (m < 10) m = '0' + m;
                        var n = h + ':' + m;
                        document.getElementById('myTime').defaultValue = n;
                    </script>

                </div>

                <!-- Case --!>
                <div class='col-sm-12'>
                    <div class='form-group'>
                        <label for='exampleInputFile'>Case</label>
                        <div>
                            <input class='form-control' type='text' name='case'>
                        </div>
                        <span class='text-danger'></span>
                    </div>
                </div>
                
                <!-- Casuality --!>
                <div class='col-sm-6'>
                    <div class='form-group'>
                        <label for='exampleInputFile'>Casualty</label>
                        <div>
                            <select name='casualty' id='casualty' class='form-control'>

                                <option value='true'>Yes</option>
                                <option selected='' value='false'>No</option>
                            </select>
                        </div>
                        <span class='text-danger'></span>
                    </div>
                </div> 

                <!-- OldPatient --!>
                <div class='col-sm-6'>
                    <div class='form-group'>
                        <label for='exampleInputFile'>Old Patient</label>
                        <div>
                            <select name='old_patient' class='form-control'>

                                <option value='true'>Yes</option>
                                <option selected='' value='false'>No</option>
                            </select>
                        </div>
                            <span class='text-danger'></span>
                    </div>
                </div>

                <!-- TPA --!>
                <div class='col-sm-6'>
                    <div class='form-group'>
                        <label for='exampleInputFile'>TPA</label>
                        <div>
                            <select class='form-control selectio2n-org' name='organisation' id='organisation'>
                                <option value='' disabled>Select</option>
                            ";
                            if (!empty($TPA))
                        echo "  <option value=$TPA>$TPA</option>";
                    echo "  </select>
                        </div>
                        <span class='text-danger'></span>
                    </div>
                </div>
                
                <!-- CreditLimit --!>
                <div class='col-sm-6'>
                    <div class='form-group'>
                        <label for='exampleInputFile'>Credit Limit ($currency_symbol)<small class='req'> *</small></label>
                        <div><input class='form-control' type='text' name='credit_limit' value='$currency_limit'>
                            <span class='text-danger'></span>
                        </div>
                    </div>
                </div>

                <!-- Reference --!>
                <div class='col-sm-6'>
                    <div class='form-group'>
                        <label for='exampleInputFile'>Reference</label>
                        <div>
                            <input class='form-control' type='text' name='reference'>
                        </div>
                        <span class='text-danger'></span>
                    </div>
                </div>

                <!-- Consultant Doctor --!>
                
                <div class='col-sm-6'>
                    <div class='form-group'>
                        <label for='exampleInputFile'>Consultant Doctor<small class='req'> *</small></label>
                        <div id = 'dddd'>
                            <select name='consultant_doctor' id='consultant_doctor' class = 'form-control selectio2n-org'>
                                <option value=''>Select</option>
                                  ";
                                $stmt->prepare("SELECT concat(firstname,' ',surname) as name, id FROM  doctors");
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while( $doc = $result->fetch_assoc() ) {
                                    echo "<option value = ".$doc['id']."> ".$doc['name']."</option>";
                                }
                    echo    "</select>
                        </div>
                        <span class='text-danger'></span>
                    </div>
                </div>
                

                <!-- Bed Group --!>
                <div class='col-sm-12'>
                    <div class='form-group'>
                        <label for='exampleInputFile'>Bed Group</label>
                        <div>
                            <select class='form-control selectio2n-org' name='bed_group' id = 'bed_group'>
                                <option value=''>Select</option>";
                                $stmt->prepare('SELECT bed_group FROM bed WHERE used = false');
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ( $bed = $result->fetch_assoc() ) {
                                    echo "<option value = ".$bed['bed_group']."> ".$bed['bed_group']."";
                                }
                    echo    "</select>
                        </div>
                    </div>
                </div>  

                <!-- AJAX PHP getting bed_no select2 -->
                    <script>
                        $('select#bed_group').on('change', function() {
                            var bed_group = $(this).val();
                            $.ajax({
                                method: 'POST',
                                url: 'bed_no.php',
                                data: ( { bed_group: bed_group } ),
                                success: function(response) {
                                    $('#bed_no').html(response);
                                },
                                error: function() {
                                    alert('Error');
                                }
                            });
                        });
                    </script>

                <!-- Bed Number --!>
                <div class='col-sm-12'>
                    <div class='form-group'>
                        <label for='exampleInputFile'>Bed Number</label>
                        <small class='req'> *</small> 
                        <div>
                            <select class='form-control selectio2n-org' name='bed_no' id='bed_no'>
                                <option value='' disabled>Select</option>
                            </select>
                        </div>
                        <span class='text-danger'></span>
                    </div>
                </div> 
                
                <!-- select2JS -->
                <script>
                $(document).ready(function() {
                    $('select.selectio2n-org').select2({
                      dropdownParent: $('#dddd'),
                      theme: 'bootstrap'
                    });
                  });
                </script>

                <!-- Live Consultation --!>
                <div class='col-sm-12'>
                    <div class='form-group'>
                        <label for='exampleInputFile'>Live Consultation</label>
                        <div>
                            <select name='live_consult' id='live_consult' class='form-control'>
                                <option value='false' selected=''>No</option>
                                <option value='true'>Yes</option>
                            </select>
                        </div>
                        <span class='text-danger'></span>
                    </div>
                </div>

                <!-- </div> -->

                </div>";
            endif;
        endif;
    endif;
else:
    header('Location: ../admin/login.php');
endif;
?>