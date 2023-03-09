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

$sql = "DELETE FROM `idea_tbl` WHERE IdeaId =$id";
$result =mysqli_query($dbconn, $sql);

  if($result){
    header("Location: ManageIdea_admin.php?msg=Post deleted successfully");
  }
  else{
    echo "Failed: " .mysqli_error($dbconn);
  }

?>