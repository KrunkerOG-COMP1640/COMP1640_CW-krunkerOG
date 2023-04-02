<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();


$dbconn = mysqli_connect("localhost", "root", "", "krunkerideadb");
//determine current page
$page = isset($_GET['page'])?$_GET['page']:1;
//determine the number of data per page
$rows_per_page = 5;
$user_id = $_SESSION["userid"];

// Determine the starting row number for the current page
$start= ($page-1)*$rows_per_page;

$sql = "SELECT idea_tbl.IdeaId, idea_tbl.IdeaTitle, category_tbl.CategoryTitle, idea_tbl.DatePost, idea_tbl.IdeaDescription, idea_tbl.UserId  from idea_tbl 
INNER JOIN category_tbl ON idea_tbl.CategoryId= category_tbl.CategoryId 
WHERE UserId = $user_id AND is_hidden=0 ORDER BY idea_tbl.IdeaId DESC LIMIT $start,$rows_per_page";

$result= mysqli_query($dbconn, $sql);

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
                  
                  echo '<li class="nav-item">';
                    echo '<a class="nav-link collapsed" href="ManageComment_admin.php">';
                      echo '<i class="bi bi-chat-left-text"></i>';
                      echo '<span>Manage Comment</span>';
                    echo '</a>';
                  echo '</li>';
                }
            ?>

            <?php
              if($_SESSION['role'] == "QA Coordinator"){
                echo'<li class="nav-heading">Pages</li>';

                echo'<li class="nav-item">';
                    echo '<a class="nav-link collapsed" href="staff_details.php">';
                        echo '<i class="bi bi-person-check"></i>';
                        echo '<span>Staff Details</span>';
                    echo '</a>';
                echo '</li><!-- End Staff Details Nav -->';
              }
            ?>

            <?php
              if($_SESSION['role'] == "QA Manager"){
                echo'<li class="nav-heading">Pages</li>';

                echo'<li class="nav-item">';
                    echo '<a class="nav-link collapsed" href="ManageCategory_manager.php">';
                        echo '<i class="bi bi-grid"></i>';
                        echo '<span>Add a new Category</span>';
                    echo '</a>';
                echo '</li><!-- End Category Page Nav -->';

                echo'<li class="nav-item">';
                    echo '<a class="nav-link collapsed" href="report_manager.php">';
                        echo '<i class="bi bi-bar-chart-line"></i>';
                        echo '<span>Reports</span>';
                    echo '</a>';
                echo '</li><!-- End Report Page Nav -->';
              }
            ?>
            
        </ul>

    </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Edit Idea</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Idea</a></li>
          <li class="breadcrumb-item active">Edit Idea</li>
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

        <!-- Left side columns -->
          <div class="row">
            <div class="card-body">

            <div class="card-body">
                <div class="row align-items-center">
                  <div class="col">
                    
                  </div>
                </div>
              </div>

              <?php
            //displaying every ideas from database
            while ($row = mysqli_fetch_assoc($result)) {
              echo '<div class="card">';
              echo '<div class="card-body">';
              echo '<h1 class="card-title">' . $row['IdeaTitle'] . '</h1>';
             

              echo '<h5 class="card-category">' . $row['CategoryTitle'] . '</h5>';
              echo '<p class="card-text">' . $row['IdeaDescription'] . '</p>';


              $ideaid = $row['IdeaId'];
              echo '<a href="HideIdea.php?id='.$user_id. '&idea=' .$ideaid.'">'; echo '<i class="fa-regular fa-x fa-1x btn btn-primary position-absolute top-0 end-0" style="margin:10px 10px 0 0;">'; echo '</i>'; echo '</a>';

              $imageidea_query = "SELECT IdeaImage FROM ideamedia_tbl WHERE IdeaId=$ideaid";
              $imageidea_result = mysqli_query($dbconn, $imageidea_query);
              $imageidea_count = mysqli_num_rows($imageidea_result);
              if ($imageidea_count > 0) {
                echo '<section class="pb-4">';
                echo '    <div class="bg-white border rounded-5">';
                echo '        <section class="p-4 d-flex justify-content-center text-center w-100">';
                echo '            <div class="lightbox" data-mdb-zoom-level="0.25" data-id="lightbox-8e0in48hs">';
                echo '                <div class="row">';
                while ($imageidea_row = mysqli_fetch_assoc($imageidea_result)) {
                  $imageidea_path = '' . $imageidea_row['IdeaImage'];
                  if (file_exists($imageidea_path)) {
                    echo '   <div class="col-lg-4 mb-4">';
                    echo '         <img src="' . $imageidea_path . '"  alt="idea image" class="shadow-1-strong rounded mb-4" style="width: 150px; height: 150px; object-fit: contain;">';
                    echo '   </div>';
                  }
                }
                echo '               </div>';
                echo '            </div>';
                echo '        </section>';
                echo '    </div>';
                echo '</section>';
              }



              echo '<a href="EditIdea_user.php?id=' .$ideaid. '" class="btn btn-primary" style="margin-right: 10px;">Edit Idea</a>';
              echo '<a href="#" class="btn btn-primary" style="background-color: darkcyan; margin-right: 10px;"><i class="bi bi-hand-thumbs-up"></i></a>';
              echo '<a href="#" class="btn btn-primary" style="background-color: darkcyan; margin-right: 10px;"><i class="bi bi-hand-thumbs-down"></i></a>';
              echo '</div>';
              echo '</div>';
            }
            ?>                   
                <!--
                  <form method="POST">
						        <input type="submit" class="like_btn" name="like_btn" value="Like" />
						        <input type="hidden" name="counter" value="<?php echo $_SESSION['counter']; ?>" />
						        <br/><?php echo $_SESSION['counter']; ?>
                    <input type="submit" class="dislike_btn" name="dislike_btn" value="Dislike" />
						        <input type="hidden" name="counter" value="<?php echo $_SESSION['counter']; ?>" />
						        <br/><?php echo $_SESSION['counter']; ?>
				          </form>  
  -->
        <!-- End Left side columns -->
      </div>

      <div class="pagination">
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
  <script src="https://kit.fontawesome.com/bb8f73d07f.js" crossorigin="anonymous"></script>

</body>

</html>