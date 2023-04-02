<?php
session_start();

require("krunkerideaconn.php");

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

$sqlhiden = "SELECT * FROM `comment_tbl` WHERE comment_hidden=0 AND CommentId= $id"; //check if idea is hidden first
$resulthiden = mysqli_query($dbconn, $sqlhiden);

$sql = "UPDATE `comment_tbl` SET comment_hidden = 0 WHERE CommentId =$id";
$result =mysqli_query($dbconn, $sql);


if(mysqli_num_rows($resulthiden) > 0){
    header("Location: ManageComment_admin.php?msg=Comment is already visible");
  }
  else if($result){
    header("Location: ManageComment_admin.php?msg=Comment is now visible");
  }

?>