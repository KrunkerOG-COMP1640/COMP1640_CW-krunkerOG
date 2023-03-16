<?php
session_start();

require("krunkerideaconn.php");

if(!isset($_SESSION['role'])){
  header("Location: index.php");
  exit;
  
}
else{
  if($_SESSION['role'] != "QA Manager"){ //staff cannot access admin page
      header("Location: index.php");
      // exit;
  }
}
$id = $_GET['id']; //get the category
$check = "SELECT CategoryId from category_tbl WHERE CategoryId=$id";
// $check = "SELECT idea_tbl.CategoryId from idea_tbl INNER JOIN category_tbl ON category_tbl.CategoryId = idea_tbl.CategoryId";
$resultcheck =mysqli_query($dbconn, $check);


try {
  $sql = "DELETE FROM `category_tbl` WHERE CategoryId =$id";
  $result =mysqli_query($dbconn, $sql);
  header("Location: ManageCategory_manager.php?msg=Category deleted successfully");
}

//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
  header("Location: ManageCategory_manager.php?msg=Can't delete because there is posts under this category.");
}


?>
