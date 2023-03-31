<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

if($_SESSION["role"] != "QA Coordinator") {
  header("Location: login.php");
  exit;
}
$dbconn = mysqli_connect("localhost", "root", "", "krunkerideadb");
$page = isset($_GET['page'])?$_GET['page']:1;
$rows_per_page = 5;
$start= ($page-1)*$rows_per_page;

$user_id = $_SESSION["userid"];

$sqlD = "SELECT DepartmentId from user_tbl WHERE UserId=$user_id";
$resultD = mysqli_query($dbconn,$sqlD);
$strD = $resultD->fetch_array()[0] ?? ''; //get single value n convert to string 

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
  <script src="https://use.fontawesome.com/fe459689b4.js"></script>

  <style>
    .pagination{
        text-align:center;
        display: block;
        letter-spacing:10px;
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index_coordinator.php" class="logo d-flex align-items-center">
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

            <?php
                if($_SESSION['role'] == "Staff"){ //Only Staff can see this
                    echo'<li>';
                        echo'<a class="dropdown-item d-flex align-items-center" href="staff_profile.php">';
                            echo'<i class="bi bi-person"></i>';
                            echo'<span>My Profile</span>';
                        echo'</a>';
                    echo'</li>';
                    echo'<li>';
                        echo'<hr class="dropdown-divider">';
                    echo'</li>';
                }
            ?>

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
                <a class="nav-link collapsed" data-bs-target="#idea-nav" data-bs-toggle="collapse" href="index_admin.html">
                    <i class="bi bi-grid"></i><span>Idea</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="idea-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="list_of_category_admin.html">
                            <i class="bi bi-list-nested" style="font-size:18px"></i><span>List of Category</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Idea Nav -->

            <li class="nav-heading">Pages</li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="staff_details.php">
                  <i class="bi bi-person-check"></i><span>Staff Details</span>
                </a>
            </li><!-- End Staff Details Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="closure_date.php">
                  <i class="bi bi-calendar4-week"></i><span>Closure Date</span>
                </a>
            </li><!-- End Closure Date Nav -->
               
            <?php
              echo '<li class="nav-item">';
              echo '<a href="EditIdea.php?id=' .$user_id.'" class="nav-link collapsed" data-bs-target="#statistics-nav;">';
              echo '<i class="bi bi-bar-chart"></i><span>Edit Idea</span>';
              echo '</a>';
              echo '</li>';
              ?>

        </ul>

    </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Staff Details</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="staff_details.php">Staff Details</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      
      <div class="row">
          <div class="card-body">
            <section class="section dashboard">
              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h4>Number of Idea Posted</h4>
                    </div>
                    <!-- End Header Name -->
                    <div class="card-body">
                      <table class="table table-bordered text-center">
                        <thead>
                          <tr>
                            <th>Username</th>
                            <th>Number of ideas posted</th>
                            <th>Department Name</th>
                            <th>Category</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php
                            include "krunkerideaconn.php";
                                                 
                            $sql = "SELECT idea_tbl.IdeaId, user_tbl.Username, COUNT(idea_tbl.UserId) AS IdeaPosted, department_tbl.DepartmentName, category_tbl.CategoryTitle from idea_tbl 
                            INNER JOIN user_tbl ON idea_tbl.UserId =user_tbl.UserId
                            INNER JOIN department_tbl ON user_tbl.DepartmentId =department_tbl.DepartmentId 
                            INNER JOIN category_tbl ON idea_tbl.CategoryId= category_tbl.CategoryId 
                            WHERE department_tbl.DepartmentId = $strD
                            GROUP BY idea_tbl.UserId ORDER BY idea_tbl.UserId DESC";

                            $result = mysqli_query($dbconn,$sql); 
                            
                            if(mysqli_num_rows($result) >0){
                              foreach($result as $row){
                                ?>
                                <tr>
                                <td><?php echo $row['Username']?></td>
                                <td><?php echo $row['IdeaPosted']?></td>
                                <td><?php echo $row['DepartmentName']?></td>
                                <td><?php echo $row['CategoryTitle']?></td>
                              </tr>
                              <?php
                              }
                            }
                            else{
                              ?>
                              <tr>
                                <td colspan="6">No record found</td>
                            
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
                  $sql_page = "SELECT COUNT(*) AS count FROM idea_tbl";
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
  </footer>
  <!-- End Footer -->

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