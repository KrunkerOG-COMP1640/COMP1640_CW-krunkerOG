<?php
require("krunkerideaconn.php");
session_start();

if(isset($_SESSION["username"])) {
    if($_SESSION["role"] == "Admin") {
        header("Location: index_admin.php");
        exit;
    }
    else if($_SESSION["role"] != "Admin") {
        header("Location: index.php");
        exit;
    }
}

if(isset($_POST["userlogin"])){

    $myemail = $_POST["useremail"];
    $mypassword = $_POST["userpassword"];

    $sql = "SELECT * FROM user_tbl WHERE UserEmail = '$myemail' AND UserPassword = '$mypassword'";
    $checkaccount = mysqli_query($dbconn,$sql );
    
    
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
            else if($_SESSION["role"] != "Admin") {
                header("Location: index.php");
                exit;
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
                <input type="Password" class="input" placeholder="Password" name="userpassword" id="">
                <i class='bx bx-lock-alt'></i>
            </div>

            <div class="input-field">
                <input type="submit" class="submit" value="Login" name="userlogin" id=""><br>  
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
