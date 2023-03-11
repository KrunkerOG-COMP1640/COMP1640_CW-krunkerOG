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

$sqlhiden = "SELECT * FROM `idea_tbl` WHERE is_hidden=0 AND IdeaId= $id"; //check if idea is hidden first
$resulthiden = mysqli_query($dbconn, $sqlhiden);

$sql = "UPDATE `idea_tbl` SET is_hidden = 0 WHERE IdeaId =$id";
$result =mysqli_query($dbconn, $sql);


if(mysqli_num_rows($resulthiden) > 0){
    header("Location: ManageIdea_admin.php?msg=Post is already visible");
  }
  else if($result){
    header("Location: ManageIdea_admin.php?msg=Post is now visible");
  }

?>