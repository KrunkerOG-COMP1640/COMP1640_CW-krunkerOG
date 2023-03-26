<?php
    require("krunkerideaconn.php");
    session_start();
?>

<?php
    if(isset($_POST["forgotPassword"])){
        $myemail = $_POST["useremail"];
        $sql = "SELECT * FROM user_tbl WHERE UserEmail = '$myemail'";
        $result = mysqli_query($dbconn,$sql);

        if(mysqli_num_rows($result) > 0){
            $userrow = mysqli_fetch_array($result);

            if(!preg_match('/^[a-zA-Z0-9_@.!]+$/', $myemail)) {

                header("Location: forgot_password.php");

                echo '<script>';
                echo 'alert("Do Not Insert Special Character")';
                echo '</script>';
            }
            else{
                header("Location: login.php");
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
                        <input type="email" class="input" placeholder="Email"  name="useremail">
                        <i class='bx bx-user' ></i>
                    </div>

                    <?php
                        if(!empty($_POST["useremail"])){
                            echo'<div class="input-field" style="color:Red;">';
                                echo'<p>*Incorrect email address.</p>';
                            echo'</div>';
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