<?php
    session_start();
    if (isset($_SESSION['admin'])):
        if ( isset($_POST['submitInpatient']) ):
            // height validation
            $height = strip_tags($_POST['height']);
            if ( is_numeric($height) ):
                if ( strlen((string)$height) < 4 ):
                    // weight validation
                    $weight = strip_tags($_POST['weight']);
                    if (is_numeric($weight)):
                        if ( strlen((string)$weight) < 4 ):
                            // BP validation
                            $BP = strip_tags($_POST['bp']);
                            if (strlen($BP) < 10):
                                // pulse validation
                                $pulse = strip_tags($_POST['pulse']);
                                if (is_numeric($pulse)):
                                    // temperature validation
                                    $temperature = strip_tags($_POST['temperature']);
                                    if (is_numeric($temperature)):
                                        // respiration validation
                                        $respiration = strip_tags($_POST['respiration']);
                                        if (is_numeric($respiration)):

                                            // get symptom id description
                                            $symptom_desc = strip_tags($_POST['symptoms_description']);

                                            require_once "../../include/mysql_connection.php";
                                            $stmt = mysqli_stmt_init($connect);

                                            $sym_type = strip_tags($_POST['symptoms_type']);
                                            $sym_title = strip_tags($_POST['symptoms_title']);
                                            if (!empty($sym_type) && !empty($sym_title) ):
                                                $stmt->prepare('SELECT id FROM symptoms WHERE type = ? AND title = ?');
                                                $stmt->bind_param('ss', $sym_type, $sym_title);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $symptom = $result->fetch_assoc();
                                                $symptom_id = $symptom['id'];
                                            endif;

                                            $note = strip_tags($_POST['note']);
                                            //getting date and time for admission date
                                            $ad_date = strip_tags($_POST['datepicker']);
                                            if ( empty($ad_date) ):
                                                $e = "empty";
                                            else:
                                                
                                            endif;
                                            //validate date
                                            $test_arr  = explode('/', $ad_date);
                                            if (checkdate( (int)$test_arr[0], (int)$test_arr[1], (int)$test_arr[2]) ) :
                                                $ad_time = strip_tags($_POST['ad_time']);
                                                //validate time
                                                if (preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $ad_time)):

                                                    // datetime
                                                    $ad_datetime = $ad_date . ' ' . $ad_time;
                                                    
                                                    $case = strip_tags($_POST['case']);

                                                    $casualty = strip_tags($_POST['casualty']);
                                                    $old_patient = strip_tags($_POST['old_patient']);
                                                    $live_consultation = strip_tags($_POST['live_consult']);

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

                                                    if ( yesNo($casualty) && yesNo($old_patient) && yesNo($live_consultation) ):
                                                        $tpa = strip_tags($_POST['organisation']);
                                                        $credit_limit = strip_tags($_POST['credit_limit']);
                                                        $reference = strip_tags($_POST['reference']);
                                                        $doc_id = strip_tags($_POST['consultant_doctor']);
                                                        if ( !empty($doc_id) ):
                                                            $bed_id = strip_tags($_POST['bed_no']);
                                                            if ( empty($bed_id) ):
                                                                $error = "Check bed";
                                                            else:
                                                                
                                                                // validation done 
                                                                //      now
                                                                //  sending data to mysql db
                                                                // 1- insert into inpatient schema and getting inpatient_id
                                                                $patient_id = $_SESSION['patient_id'];
                                                                unset($_SESSION['patient_id']);
                                                                $query = "
                                                                    INSERT INTO 
                                                                    inpatient(
                                                                        p_id, height, weight,
                                                                        BP, pulse, temperature,
                                                                        respiration, symptom_id, symptom_description, note
                                                                        ) 
                                                                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
                                                                ";
                                                                $stmt->prepare($query);
                                                                $stmt->bind_param(
                                                                    'iiisidiiss',
                                                                    $patient_id, $height, $weight,
                                                                    $BP, $pulse, $temperature,
                                                                    $respiration, $symptom_id, $symptom_desc, $note
                                                                );
                                                                $stmt->execute();
                                                                if ( mysqli_stmt_affected_rows($stmt) == 0):
                                                                    $error = "Error during insertion, try again.";
                                                                else:
                                                                    // continue inpatient_admission
                                                                    //      inpatient_id, admission_date, in_case,
                                                                    //      casualty, old_patient, credit_limit,
                                                                    //      tpa, reference, doc_id
                                                                    //      bed_id, live_consultation


                                                                    // string to boolean to tinyint

                                                                    // string to boolean
                                                                    function s_to_b($YN){
                                                                        switch ($YN) {
                                                                            case 'true':
                                                                                return true;
                                                                                break;
                                                                            
                                                                            case 'false':
                                                                                return false;
                                                                                break;
                                                                            
                                                                            default:
                                                                            return false;
                                                                                break;
                                                                        }
                                                                    }

                                                                    $casualty = s_to_b($casualty);
                                                                    $old_patient = s_to_b($old_patient);
                                                                    $live_consultation = s_to_b($live_consultation);

                                                                    // boolean to tinyint
                                                                    $casualty = intval($casualty);
                                                                    $old_patient = intval($old_patient);
                                                                    $live_consultation = intval($casualty);


                                                                    //inpatient id
                                                                    // max inpatient id where p_id = patient_id
                                                                    // max to get last inserted one
                                                                    $query = "SELECT MAX(id) AS id FROM inpatient WHERE p_id = ?";
                                                                    $stmt->prepare($query);
                                                                    $stmt->bind_param('i', $patient_id);
                                                                    $stmt->execute();
                                                                    $result = $stmt->get_result();
                                                                    $inpatient = $result->fetch_assoc();
                                                                    $inpatientId = $inpatient['id'];

                                                                    // inserting rest inpatient_admission
                                                                    $query = "INSERT INTO inpatient_admission values 
                                                                    (   
                                                                        ?, STR_TO_DATE(?, '%m/%d/%Y %H:%i'), ?,
                                                                        ?, ?, ?,
                                                                        ?, ?, ?,
                                                                        ?, ?
                                                                    )
                                                                    ";
                                                                    $stmt->prepare($query);
                                                                    $stmt->bind_param('issiiiisiii',
                                                                        $inpatientId, $ad_datetime, $case,
                                                                        $casualty, $old_patient, $credit_limit,
                                                                        $tpa, $reference, $doc_id,
                                                                        $bed_id, $live_consultation
                                                                    );
                                                                    $stmt->execute();
                                                                    if ( mysqli_stmt_affected_rows($stmt) == 0 ):

                                                                        $query = "DELETE FROM inpatient WHERE id = (SELECT MAX(id) FROM inpatient WHERE p_id = ?)";
                                                                        $stmt->prepare($query);
                                                                        $stmt->bind_param('i', $patient_id);
                                                                        $stmt->execute();

                                                                        $error = 'Error during insertion, try again.';
                                                                    else:
                                                                        $query = "UPDATE bed SET used = 1 WHERE id = ?";
                                                                        $stmt->prepare($query);
                                                                        $stmt->bind_param('i', $bed_id);
                                                                        $stmt->execute();
                                                                        header('Location: index.php?success');
                                                                        exit();

                                                                    endif;
                                                                endif;
                                                                
                                                            endif;
                                                        else:
                                                            $error = 'Check doctor';
                                                        endif;
                                                    else:

                                                        $error = 'Check Yes/No Questions';

                                                    endif;
                                                else:
                                                    $error = 'Check admission time';
                                                endif;
                                                
                                            else:
                                                $error = 'Check admission date';
                                            endif;

                                        else:
                                            $error = 'Respiration should be numeric and non-floating point.';
                                        endif;
                                    else:
                                        $error = 'Temperature should be numeric' ;
                                    endif;
                                else:
                                    $error = 'Pulse should be numeric and non-floating point.';
                                endif;
                            else:
                                $error = 'BP should be maximum 9 char including spaces like (xxx / xxx)';
                            endif;
                        else:
                            $error = 'Weight should be 3 digits (in meter)';
                        endif;
                    else:
                        $error = 'Weight should be numeric and non-floating point.';
                    endif;
                else:
                    $error = 'Height should be 3 digits (in meter)';
                endif;
            else:
                $error = 'Height should be numeric and non-floating point.';
            endif;
        endif;
        header("Location: index.php?error=$error");
    else:
        header('Location: ../admin/login.php');
    endif;
?>