<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();


$dbconn = mysqli_connect("localhost", "root", "", "krunkerideadb");

$user_id = $_SESSION["userid"];
if (isset($_POST['submit'])) {
  if (isset($_POST['UserEmail']))
  $check_email=$_POST['UserEmail'];
  $sql_email_check = "SELECT UserEmail FROM user_tbl WHERE UserEmail='$check_email' AND NOT UserId='$user_id' ";
  $result_email = mysqli_query($dbconn, $sql_email_check);
  $count = mysqli_num_rows($result_email);
  if($count > 0)
  {
  }
  else
  { 
    $username = strip_tags($_POST['Username']);
    $email = strip_tags($_POST['UserEmail']);
    $address = strip_tags($_POST['UserAddress']);
    $contact = strip_tags($_POST['UserContactNo']);

    $sql = "UPDATE `user_tbl` SET `Username`='$username',`UserEmail`='$email',`UserAddress`='$address',`UserContactNo`='$contact' 
    WHERE UserId = $user_id";
    $result = mysqli_query($dbconn, $sql);

    if ($result) {
      header("Location: staff_profile.php?msg=Data updated successfully");
    } else {
      echo "Failed: " . mysqli_error($dbconn);
    }
  }
}

?>

<?php
  $user_id = $_SESSION["userid"];
  $select_sql = "SELECT * FROM user_tbl WHERE UserId = $user_id";
  $result_User = mysqli_query($dbconn, $select_sql);  
  $row_User = mysqli_fetch_assoc($result_User);
?>

<?php
  $user_id_password = $_SESSION["userid"];
  $select_sql_password = "SELECT * FROM user_tbl WHERE UserId = $user_id_password";
  $result_password = mysqli_query($dbconn, $select_sql_password);  
  $row_password = mysqli_fetch_assoc($result_password);

  if (isset($_POST["submit_new_password"])) {
    $newPassword = strip_tags(mysqli_real_escape_string($dbconn, $_POST["newPassword"]));
    $comfirmNewPassword = strip_tags(mysqli_real_escape_string($dbconn, $_POST["comfirmNewPassword"]));
    if (!empty($newPassword) && !empty($comfirmNewPassword)) {
      if ($newPassword == $comfirmNewPassword) {
        mysqli_query($dbconn, "UPDATE user_tbl 
                                  SET UserPassword = '$newPassword' 
                                WHERE UserId = '$user_id'");
        header("Location: staff_profile.php");
        exit();
      }
    }
    else{
      echo '<script>alert("Error: Invalid Password"); window.location.href = "staff_profile.php";</script>';
    }
  }	
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
        display: inline;
        letter-spacing:10px;
    }
</style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
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
              <a class="nav-link collapsed" href="index.php">
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
                }
            ?>
            
        </ul>

    </aside><!-- End Sidebar-->


  <main id="main" class="main">

    <div class="pagetitle">
      <h1>My Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">My Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
              <h2><?php echo $row_User['Username'] ?></h2>
              <h3><?php echo $row_User['UserRoleName'] ?></h3>
              <div class="social-links mt-2">
                <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                  <form action="" method="post">
                    <h5 class="card-title">Profile Details</h5>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label ">Username</div>
                      <div class="col-lg-9 col-md-8" name="Username"><?php echo $row_User['Username'] ?></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Email</div>
                      <div class="col-lg-9 col-md-8" name="UserEmail"><?php echo $row_User['UserEmail'] ?></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Address</div>
                      <div class="col-lg-9 col-md-8" name="UserAddress"><?php echo $row_User['UserAddress'] ?></div>
                    </div>

                    <div class="row">
                      <div class="col-lg-3 col-md-4 label">Phone</div>
                      <div class="col-lg-9 col-md-8" name="UserContactNo"><?php echo $row_User['UserContactNo'] ?></div>
                    </div>
                  </form>

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->

                  <form action="" method="post">
                    
                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Username</label>
                      <div class="col-md-8 col-lg-9">
                        <input type="text" name="Username" value ="<?php echo $row_User['Username'] ?>"  class="form-control">
                      </div>
                    </div>
                    
                 
                    <div class="row mb-3">
                      <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email</label>
                      <div class="col-md-8 col-lg-9">
                        <input type="email" name="UserEmail" value ="<?php echo $row_User['UserEmail'] ?>" class="form-control">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="company" class="col-md-4 col-lg-3 col-form-label">Address</label>
                      <div class="col-md-8 col-lg-9">
                        <input type="text" name="UserAddress" value ="<?php echo $row_User['UserAddress'] ?>" class="form-control">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="Job" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                      <div class="col-md-8 col-lg-9">
                        <input type="text" name="UserContactNo" value ="<?php echo $row_User['UserContactNo'] ?>" class="form-control">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-primary" name="submit">Save Changes</button>
                      <a href="staff_profile.php" class="btn btn-danger" >Cancel</a>
                    </div>
                  </form>
                  <!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  
                  <!-- Change Password Form -->
                  <form action="" method="post" enctype="multipart/form-data">

                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newPassword" type="password" class="form-control" id="newPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="comfirmNewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="comfirmNewPassword" type="password" class="form-control" id="comfirmNewPassword">
                      </div>
                    </div>

                    <?php
                        if(isset($_POST["submit_new_password"])){

                            $userNewPassword = $_POST["newPassword"];
                            $userComfirmNewPassword = $_POST["comfirmNewPassword"];

                            if($userNewPassword != $userComfirmNewPassword){
                              echo '<script>alert("*New Password and Re-enter New Password does not match.")</script>';
                            }
                        }
                    ?>

                    <div class="text-center">
                      <button type="submit" name="submit_new_password" class="btn btn-primary">Change Password</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

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