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

$page = isset($_GET['page'])?$_GET['page']:1;
//determine the number of data per page
$rows_per_page = 10;

// Determine the starting row number for the current page
$start= ($page-1)*$rows_per_page;
?>

<?php
  $user_id = $_SESSION["userid"];
  $select_sql = "SELECT * FROM user_tbl WHERE UserId = $user_id";
  $result_User = mysqli_query($dbconn, $select_sql);  
  $row_User = mysqli_fetch_assoc($result_User);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Krunker Idea Portal 2023</title>
  <meta content="" name="description">
  <meta content="" name="keywords"> 

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.5.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <style>
    .pagination{
      justify-content: center;
        display: flex;
        letter-spacing:10px;
    }
   
</style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index_admin.php" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">Krunker Idea Portal</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $row_User['Username'] ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $row_User['Username'] ?></h6>
              <span><?php echo $row_User['UserRoleName'] ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
                <a class="dropdown-item d-flex align-items-center" href="staff_profile.php">
                  <i class="bi bi-person"></i>
                  <span>My Profile</span>
                </a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
              <a class="nav-link collapsed" href="index_admin.php">
                  <i class="bi bi-grid"></i><span>Idea</span>
              </a>
            </li><!-- End Idea Nav -->

            <?php
              echo '<li class="nav-item">';
              echo '<a href="EditIdea.php?id=' .$user_id.'" class="nav-link collapsed" data-bs-target="#statistics-nav;">';
              echo '<i class="bi bi-pencil"></i><span>Edit Idea</span>';
              echo '</a>';
              echo '</li>';
            ?>

              <?php
                if($_SESSION['role'] == "Admin"){ //staff cannot see this
                  echo'<li class="nav-heading">Pages</li>';

                  echo'<li class="nav-item">';
                      echo '<a class="nav-link collapsed" href="ManageUser_admin.php">';
                          echo '<i class="bi bi-people"></i>';
                          echo '<span>Manage User</span>';
                      echo '</a>';
                  echo '</li><!-- End Manage User Page Nav -->';

                  echo '<li class="nav-item">';
                      echo '<a class="nav-link collapsed" href="ManageIdea_admin.php">';
                          echo '<i class="bi bi-chat-left-text"></i>';
                          echo '<span>Manage Idea</span>';
                      echo '</a>';
                  echo '</li><!-- End Manage Idea Page Nav -->';
                  
                  echo '<li class="nav-item">';
                  echo '<a class="nav-link collapsed" href="closure_date.php">';
                  echo '<i class="bi bi-calendar4-week"></i><span>Closure Dates</span>';
                  echo '</a>';
                  echo '</li>';
                  
                  echo '<li class="nav-item">';
                    echo '<a class="nav-link collapsed" href="ManageComment_admin.php">';
                      echo '<i class="bi bi-chat-left-text"></i>';
                      echo '<span>Manage Comment</span>';
                    echo '</a>';
                  echo '</li>';
                }
            ?>
            
        </ul>

    </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Manage Comments</h1>
      <nav>
        <ol class="breadcrumb">
          <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index_admin.php">Admin</a></li>
          <li class="breadcrumb-item"><a href="ManageIdea_admin.php">Manage Comments</a></li>
        </ol>

        </ol>
        
      </nav>
    </div><!-- End Page Title -->

    <div class="container">
    <?php
                if(isset($_GET['msg'])){
                  $msg = $_GET['msg'];
                  echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                  '.$msg.'
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        
                  </button>
                </div>';
                }
                ?>
    </div>

    <section class="section dashboard">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Uploaded Comments</h4>
            </div>
            <!-- End Header Name -->
            <div class="card-body">
              <table class="table table-bordered text-center">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Idea Title</th>
                    <th>Comments</th>
                    <th>Status</th>
                    <th>Hide Post</th>
                    <th>Show Post</th>
                  </tr>
                </thead>
                <tbody>
                     
                <?php
                    include "krunkerideaconn.php";

                    $sql = "SELECT user_tbl.Username, idea_tbl.IdeaTitle, comment_tbl.CommentDetails, comment_tbl.comment_hidden, comment_tbl.CommentId from comment_tbl
                    INNER JOIN user_tbl ON user_tbl.UserId = comment_tbl.UserId
                    INNER JOIN idea_tbl ON idea_tbl.IdeaId = comment_tbl.IdeaId LIMIT $start,$rows_per_page";
                    
                    $result = mysqli_query($dbconn,$sql);
                    while($row = mysqli_fetch_assoc($result)){

                        ?>
                         <tr>
                         <td><?php echo $row['Username']?></td>
                        <td><?php echo $row['IdeaTitle']?></td>
                        <td><?php echo $row['CommentDetails']?></td>

                        <td>
                          <?php if($row['comment_hidden']== 0 ){
                          echo "Visible";
                          
                        }
                        elseif ($row['comment_hidden']== 1 ){
                          echo "Hidden";
                        }
                        ?>
                        </td>
                        <td><a href="HideComment.php?id=<?php echo $row['CommentId'] ?>" class="btn btn-danger">Hide Comment</a></td>
                        <td><a href="ShowComment.php?id=<?php echo $row['CommentId'] ?>" class="btn btn-success">Show Comment</a></td>
                </tr>
                <?php
                    }

                 ?>
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
      <div class = "pagination">
      <?php
			$sql_page = "SELECT COUNT(*) AS count FROM comment_tbl";
			$page_count = mysqli_query($dbconn, $sql_page);
			$row_count = mysqli_fetch_assoc($page_count);
			$total_rows = $row_count['count'];
			$total_pages = ceil($total_rows / $rows_per_page);
   
			for ($i = 1; $i <= $total_pages; $i++){
				echo'<a href="?page='.$i.'">'.$i.'</a>';
			}
		?>
    </div>

    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Krunker Idea Portal 2023</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>