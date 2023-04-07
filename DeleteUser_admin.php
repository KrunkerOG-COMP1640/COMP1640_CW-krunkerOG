<?php
session_start();

require("krunkerideaconn.php");

$user_id = $_SESSION["userid"];

if(!isset($_SESSION['role'])){
  header("Location: index.php");
  exit;
  
}
else{
  if($_SESSION['role'] != "Admin"){ //staff cannot access admin page
      header("Location: index.php");
      // exit;
  }
}
$id = $_GET['id'];

$sql = "DELETE FROM `user_tbl` WHERE UserId =$id AND NOT UserId='$user_id' ";
$result =mysqli_query($dbconn, $sql);

  if($result){
    header("Location: ManageUser_admin.php?msg=User deleted successfully");
  }
  else{
    echo "Failed: " .mysqli_error($dbconn);
  }

?>