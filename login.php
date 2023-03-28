<?php
require("krunkerideaconn.php");

session_start();

//cannot go back to login page if user already login
if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] == "Admin") {
        header("Location: index_admin.php");
        exit;
    } else if ($_SESSION["role"] == "Staff") {
        header("Location: index.php");
        exit;
    }
    else if($_SESSION["role"] == "QA Manager") {
        header("Location: index_manager.php");
        exit;
    }
    else if($_SESSION["role"] == "QA Coordinator") {
        header("Location: index_coordinator.php");
        exit;
    }
    
}

if(isset($_POST["userlogin"])){

    $myemail = $_POST["useremail"];
    $mypassword = $_POST["userpassword"];
    $sql = "SELECT * FROM user_tbl WHERE UserEmail = '$myemail' AND UserPassword = '$mypassword'";
    $checkaccount = mysqli_query($dbconn,$sql);

    if(mysqli_num_rows($checkaccount) > 0){
        $userrow = mysqli_fetch_array($checkaccount);
    
        if(!preg_match('/^[a-zA-Z0-9_@.!]+$/', $myemail)) {

            header("Location: login.php");

            echo '<script>';
            echo 'alert("Do Not Insert Special Character")';
            echo '</script>';
        }
        else{
            if(is_array($userrow)) {
                $_SESSION["userid"] = $userrow["UserId"];
                $_SESSION["username"] = $userrow["Username"];
                $_SESSION["useremail"] = $userrow["UserEmail"];
                $_SESSION["userpassword"] = $userrow["UserPassword"];
                $_SESSION["role"] = $userrow["UserRoleName"];
                $_SESSION["useraddress"] = $userrow["UserAddress"];
                $_SESSION["usercontactno"] = $userrow["UserContactNo"];

            }
            else{
                header("Location: login.php");
            }
            if(isset($_SESSION["username"]) 
            && isset($_SESSION["userid"]) 
            && isset($_SESSION["role"])) {
                if($_SESSION["role"] == "Admin") {
                    header("Location: index_admin.php");
                    exit;
                }
                else if($_SESSION["role"] == "Staff") {
                    header("Location: index.php");
                    exit;
                }
                else if($_SESSION["role"] == "QA Manager") {
                    header("Location: index_manager.php");
                    exit;
                }
                else if($_SESSION["role"] == "QA Coordinator") {
                    header("Location: index_coordinator.php");
                    exit;
                }
                
            }
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

    <title>Krunker Idea Portal | Login</title>

</head>
<body>
   <div class="box">
    <div class="container">

        <div>
            <header>Krunker Idea Portal</header><br><br>
        </div>

        <form action="" method="post">
            <div class="input-field">
                <input type="email" class="input" placeholder="Email"  name="useremail" id="">
                <i class='bx bx-user' ></i>
            </div>

            <div class="input-field">
                <input type="password" class="input" placeholder="Password" name="userpassword" id="">
                <i class='bx bx-lock-alt'></i>
            </div>

            <?php
                if(isset($_POST["userlogin"])){

                    $userEmail = $_POST["useremail"];
                    $userPassword = $_POST["userpassword"];
                    $usersql = "SELECT * FROM user_tbl WHERE UserEmail = '$myemail' AND UserPassword = '$mypassword'";
                    $userResult = mysqli_query($dbconn,$usersql);

                    if(empty($userEmail) || empty($userPassword)){
                        echo'<div class="input-field" style="color:Red;">';
                            echo'<p>*Please fill in both email and password.</p>';
                        echo'</div>';
                    }
                }
            ?>

            <?php
                if(!empty($_POST["useremail"]) && !empty($_POST["userpassword"])){
                    echo'<div class="input-field" style="color:Red;">';
                        echo'<p>*Incorrect email address or password.</p>';
                    echo'</div>';
                }
            ?>

            <div class="input-field">
                <input type="submit" class="submit" value="Login" name="userlogin"><br>  
            </div>

            <div class="two-col">
                <div class="one">
                    <input type="checkbox" name="" id="check">
                    <label for="check"> Remember Me</label>
                </div>
            <div class="two">
                <label><a href="#">Forgot password?</a></label>
            </div>
        </form>
        
    </div>
</div>  
</body>
</html>