<?php
    require("krunkerideaconn.php");
    session_start();
?>

<?php
    if(isset($_POST["forgotPassword"])){
        $user_email = $_POST["useremail"];
        $newPassword = $_POST["newPassword"];
        $hashedPassword = md5($newPassword);
        $sql = "SELECT * FROM user_tbl WHERE UserEmail = '$user_email'";
        $checkaccount = mysqli_query($dbconn,$sql);

        if(mysqli_num_rows($checkaccount) > 0){
            $userrow = mysqli_fetch_array($checkaccount);

            if(!preg_match('/^[a-zA-Z0-9_@.!]+$/', $user_email)) {

                header("Location: forgot_password.php");

                echo '<script>';
                echo 'alert("Do Not Insert Special Character")';
                echo '</script>';
            }elseif (!preg_match("/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^';]{8,})$/", $newPassword)) {
                $errormsg = "Invalid Password";
                echo '<script>alert("' . $errormsg . '"); window.location.href="forgot_password.php";</script>';
            }elseif(!empty($newPassword)) {
                $newPassword = strip_tags(mysqli_real_escape_string($dbconn, $_POST["newPassword"]));
                mysqli_query($dbconn, "UPDATE user_tbl 
                                        SET UserPassword = '$hashedPassword' 
                                        WHERE UserEmail = '$user_email'");
                echo "<script>alert('Your password has changed!'); window.location.href='login.php';</script>";
                exit();
            }else{
                echo "<script>alert('Password change failed. Please try again'); window.location.href='forgot_password.php';</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style_login.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        <title>Krunker Idea Portal | Forgot Password</title>

    </head>
    <body>
        <div class="box">
            <div class="container">
                <div>
                    <header>Forgot Password</header><br><br>
                </div>

                <form action="" method="post">
                    <div class="input-field">
                        <input type="email" class="input" placeholder="Email"  name="useremail" autofocus required>
                        <i class='bx bx-user' ></i>
                    </div>

                    <div class="input-field">
                        <input type="password" class="input" placeholder="Password" name="newPassword" minlength="8" required>
                        <i class='bx bx-lock-alt'></i>
                    </div>

                    <?php
                        if(isset($_POST["forgotPassword"])){
                            $userEmail = $_POST["useremail"];
                            $userPassword = $_POST["newPassword"];

                            if(empty($userEmail) || empty($userPassword)){
                                echo'<div class="input-field" style="color:Red;">';
                                    echo'<p>*Please fill in both email and password.</p>';
                                echo'</div>';
                            }
                            
                            if (!empty($_POST["useremail"]) && !empty($_POST["newPassword"])){
                                echo'<div class="input-field" style="color:Red;">';
                                    echo'<p>*Incorrect email address</p>';
                                echo'</div>';
                            }
                        }
                    ?>

                    <div class="input-field">
                        <input type="submit" class="submit" value="Submit" name="forgotPassword"><br>  
                    </div>

                    <div class="two-col">
                        <div class="one">
                        </div>
                        <div class="two">
                            <label><a href="login.php">Sign in?</a></label>
                        </div>
                    </div>    
                </form>
                
            </div>
        </div>  
    </body>
</html>