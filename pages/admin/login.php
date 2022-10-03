<?php
    session_start();
    if ( !(isset($_SESSION['admin'])) ):
        if ( isset($_POST['login']) )
            if ( empty($_POST['username'] ) )
                $error = 'empty username';
            else if ( empty($_POST['password'] ) )
                $error = 'empty password';
            else {
                include_once('../../include/mysql_connection.php');
                $user = $_POST['username'];
                $pass = $_POST['password'];
                $stmt = mysqli_prepare($connect ,"SELECT id FROM admin WHERE username = ? and password = ?");
                mysqli_stmt_bind_param($stmt, 'ss', $user, $pass);
                mysqli_stmt_execute($stmt);

                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);

                if (mysqli_stmt_affected_rows($stmt) > 0):
                    $_SESSION['adminID'] = $row['id'];
                    $_SESSION['admin'] = $user;
                    header('location: index.php');
                    exit;
                else:
                    $error = 'wrong email or password';
                endif;
            }
?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../../css/bootstrap.min.css">
            <title>HIS Admin Login</title>
            <style>
                html ,body{
                    height: 100%;
                }
                div.center{
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100%;
                    background-color: #212529;
                }
                div.form-outlinee{
                    border: 1px solid #eed;
                    padding: 20%;
                    height: 50%;
                    display: flex;
                    flex-direction: column;
                    /* align-items: center; */
                    justify-content: center;
                    background-color: #e7eeed;
                }
            </style>
        </head>
        <body>
            <div class="center">
                <div class="form-outlinee">
                    <?php
                    if (isset($error))
                        echo "
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            <strong>Error</strong> ".$error."
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
                    ?>
                    <form method = "post">
                    <!-- Email input -->
                    <div class="form-outline mb-4" style = "margin-bottom: 12% !important;">
                        <input autocomplete = "off" type="text" name = 'username' class="form-control" placeholder = "Email address" />
                    </div>
                    
                    
                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <input type="password" name = 'password' class="form-control" placeholder = "Password" />
                    </div>

                    <!-- 2 column grid layout for inline styling -->
                    <div class="row mb-4">
                        <div class="col d-flex justify-content-center">
                        <!-- Checkbox -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
                            <label class="form-check-label" for="form2Example31"> Remember me </label>
                        </div>
                        </div>

                        <div class="col">
                        <!-- Simple link -->
                        <a href="#!">Forgot password?</a>
                        </div>
                    </div>

                    <!-- Submit button -->
                        <input type="submit" style = "width: 100%;" name = "login" value = "Sign in" class="btn btn-primary btn-block mb-4" />
                    <!-- Register buttons -->
                    <div class="text-center">
                        <p>Not a member? <a href="#!">Register</a></p>
                    </div>
                </div>
                </form>
            </div>
        <script src = "../../js/bootstrap.min.js"></script>
        </body>
        </html>
<?php
    else:
        header('location: index.php');
    endif;
?>