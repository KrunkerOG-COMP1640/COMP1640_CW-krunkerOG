<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["username"]) && !isset($_SESSION["userid"])) {
  header("Location: login.php"); // Redirect to login page if not logged in
  exit;
}
// Connect to MySQL database
$dbconn = mysqli_connect("localhost", "root", "", "krunkerideadb");
// Insert new post into database
if (isset($_POST["submit_post"])) {
  $title = strip_tags(mysqli_real_escape_string($dbconn, $_POST["title"]));
  $desc = strip_tags(mysqli_real_escape_string($dbconn, $_POST["description"]));
  $category = mysqli_real_escape_string($dbconn, $_POST["category"]);
  $anon = isset($_POST["anonymous"]);
  $user_id = $_SESSION["userid"];

  // Get category closure date
  $getClosure = "SELECT DateClosure from user_tbl WHERE UserId = $user_id";
  $closureResult = mysqli_query($dbconn, $getClosure);
  $closureRow = mysqli_fetch_assoc($closureResult);
  $closureDate = $closureRow['DateClosure'];
  if(date('Y-m-d') >= $closureDate){
  echo "<script>alert('Sorry, idea submissions are temporarily closed.')</script>";
  header("refresh:0; url=index.php");
  exit();
  }
  else{

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
  $countfiles = count($_FILES['ideaimage']['name']);
  if (!empty(array_filter($imagefiles['name']))) {
    foreach ($imagefiles['tmp_name'] as $key => $tmp_name) {
      if ($countfiles > $max_image_count) {
        $sqldeleteidea = "DELETE FROM idea_tbl WHERE IdeaId = '$ideaid'";
        mysqli_query($dbconn, $sqldeleteidea);

        $errormessage = "Cannot upload more than 5 images";
        echo "<script>alert('$errormessage'); window.location.href='index.php';</script>";
        exit();
      } else if ($countfiles < $max_image_count) {
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
          $image_target_file = $image_target_dir . uniqid() . "_" . basename($ideaimage);
          move_uploaded_file($ideaimage_tmp, $image_target_file);

          // Insert image details
          $sql_insertimage = "INSERT INTO ideamedia_tbl (IdeaId, IdeaImage) VALUES ('$ideaid', '$image_target_file')";
          mysqli_query($dbconn, $sql_insertimage);
          $uploaded_image_count++;
          header("Location: index.php?msg=Idea and image successfully post");
        } else {
          $sqldeleteidea = "DELETE FROM idea_tbl WHERE IdeaId = '$ideaid'";
          mysqli_query($dbconn, $sqldeleteidea);

          $errormsg = "File type not allowed or file size more than 2gb"; // error message to display
          echo "<script>alert('$errormsg'); window.location.href='index.php';</script>";
          $image_error = true;

          break; // stop processing additional files
        }
      }
    }
  } else {
    //find coordinator email
    $sqlC = mysqli_query($dbconn, "SELECT DepartmentId from user_tbl WHERE UserId=$user_id");
    $strD = $sqlC->fetch_array()[0] ?? ''; //get single value n convert to string 
    $sqlCor = "SELECT UserEmail from user_tbl WHERE DepartmentId = $strD AND UserRoleName ='QA Coordinator'";
    $resulCor = mysqli_query($dbconn, $sqlCor); //get email
    $strresulCor = $resulCor->fetch_array()[0] ?? ''; //conver email to single value

    $sqlUsername = mysqli_query($dbconn, "SELECT Username from user_tbl WHERE UserId= $user_id");
    $strUserName = $sqlUsername->fetch_array()[0] ?? '';

    // send email to coordinator
    $to      = $strresulCor;
    $subject = 'Notifications Krunker Idea Portal';
    $message = "You got a new message from Krunker Idea Portal : \r\n\n";
    $message .= "$strUserName posted a new idea. \r\n\n\n";
    $message .= "Warm regards, \r\n\n";
    $message .= "Krunker Idea Portal \r\n";
    $headers = 'From:krunkerog06@gmail.com' . "\r\n" .
      'Reply-To: krunkerog6@gmail.com' . "\r\n" .
      'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
    header("Location: index.php?msg=Idea successfully post");
    exit();
  }
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


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->

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
    .card {
      margin-top: 5%;
    }
  </style>
</head>

<body class="toggle-sidebar">

<header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
  <a href="index.php" class="logo d-flex align-items-center">
    <img src="assets/img/logo.png" alt="">
    <span class="d-none d-lg-block">Krunker Idea Portal</span>
  </a>
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
          <label for="terms" class="form-check-label">I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModalCenter"> terms and conditions</a></label>
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
  </footer>
  <!-- End Footer -->

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><strong>Terms and Conditions</strong></h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <h5>1. Users must ensure that their idea is original and not copied or stolen from any other source.</h5>
      </br>
      <h5>2. Users must conduct themselves in a respectful and professional manner when submitting and discussing their ideas.</h5>
      </br>
      <h5>3. By submitting an idea, users acknowledge that they own the rights to the idea and agree to grant the portal the non-exclusive right to use, modify, and distribute the idea.</h5>
      </br>
      <h5>4. Users must not submit any confidential or proprietary information as part of their idea.</h5>
      </br>
      <h5>5. The portal does not guarantee that any idea submitted will be reviewed or acted upon, and is not responsible for any loss or damage resulting from the submission or use of any idea.</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

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
