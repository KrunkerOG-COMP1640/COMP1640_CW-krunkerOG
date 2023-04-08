<?php
session_start();

require("krunkerideaconn.php");

if(!isset($_SESSION['role'])){
  header("Location: index.php");
  exit;
  
}

$id = $_GET['id'];
$ideaId = $_GET['idea'];

$sql = "UPDATE `idea_tbl` SET is_hidden = 1 WHERE UserId=$id AND IdeaId =$ideaId"; //hide idea
$result =mysqli_query($dbconn, $sql);

if($result){
    echo "<script>alert('Idea deleted successfully.')</script>";
    header("refresh:0; url=EditIdea.php?msg=Idea deleted successfully.");
}


?>