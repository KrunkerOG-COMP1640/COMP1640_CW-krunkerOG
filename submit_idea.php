<?php
	session_start();
	
	// Check if user is logged in
if(!isset($_SESSION["username"]) && !isset($_SESSION["userid"])) {
    header("Location: login.php");// Redirect to login page if not logged in
    exit;
}
// Connect to MySQL database
$dbconn = mysqli_connect("localhost", "root", "", "krunkerideadb");
// Insert new post into database
if(isset($_POST["submit_post"])){
	$title = mysqli_real_escape_string($dbconn, $_POST["title"]);
	$desc = mysqli_real_escape_string($dbconn, $_POST["description"]);
	$category = mysqli_real_escape_string($dbconn, $_POST["category"]);
	$anon = isset($_POST["anonymous"]);
	$user_id = $_SESSION["userid"];
  $sql_insertpost = "INSERT INTO idea_tbl (CategoryId, UserId, IdeaTitle, IdeaDescription,IdeaAnonymous) 
  VALUES ('$category','$user_id','$title', '$desc','$anon')";
	mysqli_query($dbconn, $sql_insertpost);
  $ideaid = mysqli_insert_id($dbconn);

  //upload image
  $imagefiles = $_FILES['ideaimage'];
  $max_image_size = 2 * 1024 * 1024 * 1024; // 2 GB
  $max_image_count = 5;
  $uploaded_image_count = 0;
  $image_error = false;
  foreach ($imagefiles['tmp_name'] as $key => $tmp_name) {
    if ($uploaded_image_count < $max_image_count) {
      $ideaimage = $imagefiles['name'][$key];
      $ideaimage_tmp = $imagefiles['tmp_name'][$key];
      $ideaimage_size = $imagefiles['size'][$key];
      $ideaimage_type = $imagefiles['type'][$key];
      $ideaimage_error = $imagefiles['error'][$key];

      // Check if file type is allowed
      $allowed_image_types = array('png', 'jpg', 'jpeg');
      $image_ext_arr = explode('.', $ideaimage);
      $image_ext = strtolower(end($image_ext_arr));
      if (in_array($image_ext, $allowed_image_types) && $ideaimage_size <= $max_image_size) {
        // Move uploaded file to a permanent location
        $image_target_dir = "assets/img/";
        $image_target_file = $image_target_dir .uniqid()."_". basename($ideaimage);
        move_uploaded_file($ideaimage_tmp, $image_target_file);

        // Insert image details
        $sql_insertimage = "INSERT INTO ideamedia_tbl (IdeaId, IdeaImage) VALUES ('$ideaid', '$image_target_file')";
        mysqli_query($dbconn, $sql_insertimage);
        $uploaded_image_count++;
      }else {
        $errormsg = "File type not allowed or file size more than 2gb"; // error message to display
        echo "<script>alert('$errormsg'); window.location.href='index.php';</script>";
        $image_error = true;
        
        break; // stop processing additional files
      }
    } else {
      $errormsg = "Only 5 Image can be upload"; // error message to display
      echo "<script>alert('$errormsg'); window.location.href='index.php';</script>";
      $image_error = true;
      
      break; // stop processing additional files
    }
  }

  // Redirect to the homepage
  if (!$image_error) {
    header("Location: index.php");
    exit();
  }
}	

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Staff Dashboard - COMP1640_EWSD</title>
  <meta content="" name="description">
  <meta content="" name="keywords">


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

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
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">


  <style>
    /* Center the card horizontally and vertically */
    .card {
      position: absolute;
      top: 50%;
      left: 50%;
      margin-top: 30px;
      transform: translate(-50%, -50%);
    }
  </style>
</head>

<body class="toggle-sidebar">

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="StaffPage.html" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">Krunker Idea Portal</span>
      </a>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Atque rerum nesciunt</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sit rerum fuga</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>Dicta reprehenderit</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>4 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="d-none d-md-block dropdown-toggle ps-2">Krunker</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>Krunker</h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->


  <div class="card mx-auto" style="max-width: 600px;">
    <div class="card-header">
      Submit Idea
    </div>
    <div class="card-body">
      <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="title" class="form-label">Title:</label>
          <input type="text" id="title" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Description:</label>
          <textarea id="description" name="description" class="form-control" rows="4" cols="50"  required></textarea>
        </div>
        <div class="mb-3">
          <label for="category" class="form-label">Choose category:</label>
          <select id="category" name="category" class="form-control" required>
          <?php
           $sql = "SELECT CategoryId, CategoryTitle FROM category_tbl";
           $result = mysqli_query($dbconn, $sql); 
           while ($row = mysqli_fetch_assoc($result)) {

           echo '<option value="'. $row['CategoryId'].'">'. $row['CategoryTitle']. '</option>';
        
            }
          ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="file" class="form-label">Upload file:</label>
          <input type="file" id="file" name="ideaimage[]" class="form-control" accept="image/jpeg, image/png, image/jpg" multiple>
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" id="anonymous" name="anonymous" class="form-check-input" value= 1>
          <label for="anonymous" class="form-check-label">Post anonymously</label>
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" id="terms" name="terms" class="form-check-input" required>
          <label for="terms" class="form-check-label">I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#viewUserIdea"> terms and conditions</a></label>
        </div>
        <div class="d-grid">
          <button type="submit" name="submit_post" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
  

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Krunker Ideas Portal 2023</span></strong>. All Rights Reserved
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