<?php
session_start();

require("krunkerideaconn.php");
if (!isset($_SESSION['role'])) {
  header("Location: index.php");
  exit;
} else {
  if ($_SESSION['role'] != "Admin") { //staff cannot access admin page
    header("Location: index.php");
    // exit;
  }
}
$error = "";
if (isset($_POST['submit'])) {
  $username = strip_tags($_POST['Username']);
  $password = strip_tags($_POST['UserPassword']);
  $hashedPassword = md5($password);
  $contact = strip_tags($_POST['UserContactNo']);
  $address = htmlentities($_POST['UserAddress']);
  $email = strip_tags($_POST['UserEmail']);
  $role = $_POST['UserRoleName'];
  $department = $_POST['DepartmentId'];

  $check_email = mysqli_query($dbconn, "SELECT * FROM user_tbl WHERE UserEmail = '$email'");

  if (empty($username) || !preg_match('/^[a-zA-Z0-9_@.!]+$/', $username)) {
    $error = "Enter valid an Username";
  } else if (empty($password)|| !preg_match("/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^';]{8,})$/", $password)) {
    $error = "Enter valid a Password";
  } else if (mysqli_num_rows($check_email) > 0) {
    $error = "Email address already exist";
  }elseif (empty($email)||!preg_match('/^[a-zA-Z0-9_@.!]+$/', $email) ) {
    $error = "Enter valid an email";
  }else{
          $sql = "INSERT INTO `user_tbl`(`DepartmentId`, `UserRoleName`, `Username`, `UserPassword`, `UserEmail`, `UserContactNo`, `UserAddress`) 
                VALUES ('$department','$role','$username','$hashedPassword','$email','$contact','$address')";

          $result = mysqli_query($dbconn, $sql);
          header("Location: ManageUser_admin.php?msg=New user added successfully");
  }
}

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
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlentities($row_User['Username']) ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo htmlentities($row_User['Username']) ?></h6>
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
      <h1>Add User</h1>
      <nav>
        <ol class="breadcrumb">
          <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index_admin.php">Admin</a></li>
          <li class="breadcrumb-item"><a href="ManageUser_admin.php">Manage User</a></li>
          <li class="breadcrumb-item"><a href="#">Add User</a></li>
        </ol>

        </ol>
        
      </nav>
    </div><!-- End Page Title -->
    <div class="container">
    <?php
      if ($error){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        '.$error.'
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
              <h4>Add User</h4>
            </div>
            <!-- End Header Name -->
            <div class="card-body">

              <form action="" method="post">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="">Username</label>
                        <input type="text" name="Username" class="form-control" autofocus>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Password</label>
                        <input type="password" name="UserPassword" class="form-control" minlength="8" required>
                    </div>
                 
                    <div class="col-md-12 mb-3">
                        <label for="">Email</label>
                        <input type="email" name="UserEmail" class="form-control" placeholder="name@example.com" required>
                    </div>
 
                    <div class="col-md-12 mb-3">
                        <label for="">Contact No</label>
                        <input type="text" name="UserContactNo" class="form-control">
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="">Address</label>
                        <input type="text" name="UserAddress" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Role Name</label>
                        <select name="UserRoleName" id="" required class="form-control">
                          <option value="Staff">Staff</option>
                          <option value="QA Coordinator">QA Coordinator</option>
                          <option value="QA Manager">QA Manager</option>
                          <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Department</label>
                        <select name="DepartmentId" id="" required class="form-control">
                          <option value="1">Information Technology</option>
                          <option value="2">Human Resource</option>
                          <option value="3">Business & Marketing</option>
                          <option value="4">Accounting</option>

                        </select>
                    </div>
                    <div>
                      <button type="submit" class="btn btn-primary" name = "submit">Add User</button>
                      <a href="ManageUser_admin.php" class="btn btn-danger" >Cancel</a>

                    </div>
        
          
                </div>
              </form>

            </div>
          </div>
        </div>
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
