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
$error = "";
if(isset($_POST['submit'])){

  $dateclosure= $_POST['DateClosure'];
  $datefinal= $_POST['DateFinal'];

  if($datefinal <= $dateclosure){
      $error="The final closure date must be more than the closure date.";
  }
  else if( $dateclosure >= $datefinal){
    $error="The date closure date cannot be more than the final closure date.";
  }
  else{

  $sql = "UPDATE `user_tbl` SET `DateClosure`='$dateclosure',`DateFinal`='$datefinal' 
          WHERE 1 ";
  
  $result = mysqli_query($dbconn,$sql);
  
  if($result){
      header("Location: closure_date.php?msg = Closure Date Updated");
  
  }
  else{
      echo "Failed: " .mysqli_error($dbconn);
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
      <h1>Closure Date</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="closure_date.php">Closure Date</a></li>
         
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
          <div class="card text">
            <div class="card-header">
              <h4>Change Closure Date</h4>
            </div>
            <!-- End Header Name -->
            <div class="card-body">

<?php
$sql = "SELECT DateClosure, DateFinal FROM user_tbl";
$result = mysqli_query($dbconn, $sql);  
$row = mysqli_fetch_assoc($result);

$years = mysqli_query($dbconn, "SELECT DISTINCT YEAR(DatePost) AS Years FROM idea_tbl");
$year_list='';
$default_year = date('Y');

foreach ($years as $year){
  $selected = ($year['Years'] == $default_year) ? 'selected' : ''; 
  $year_list.='<option value="'.$year['Years'].'"'.$selected.'>'.$year['Years'].'</option>';
}
?>
              <form action="" method="post">
                    <div class="col-md-12 mb-3">
                      <span>Closure Year</span><br/>
                      <select name="dropdown" class="closure_year" id="closure_year">
                      <?php echo $year_list; ?>
                      </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Date Closure</label>
                        <br>
                        <input type="date" class="form-control" name="DateClosure"  min="<?php echo $default_year; ?>-01-01" max="<?php echo $default_year; ?>-12-31"  value ="<?php echo $row['DateClosure'] ?>" autofocus >
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Final Closure Date</label>
                        <br>
                        <input type="date" class="form-control" name="DateFinal"  min="<?php echo $default_year; ?>-01-01" max="<?php echo $default_year; ?>-12-31"  value ="<?php echo $row['DateFinal'] ?>" >
                    </div>
                   
                    <div class="col-md-12 mb-3">
                      <button type="submit" class="btn btn-primary" name="submit">Update closure date</button>
                      <a href="closure_date.php" class="btn btn-danger" >Cancel</a>
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
  <script>
  closureYearSelect = document.getElementById('closure_year');
  dateClosureInput = document.getElementsByName('DateClosure')[0];
  dateFinalInput = document.getElementsByName('DateFinal')[0];
  
  closureYearSelect.addEventListener('change', function() {
    selectedYear = this.value;
    dateClosureInput.min = selectedYear + '-01-01';
    dateClosureInput.max = selectedYear + '-12-31';
    dateFinalInput.min = selectedYear + '-01-01';
    dateFinalInput.max = selectedYear + '-12-31';
  });
</script>
</body>

</html>
