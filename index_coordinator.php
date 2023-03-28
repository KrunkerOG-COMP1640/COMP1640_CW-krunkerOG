<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();
require("krunkerideaconn.php");
if($_SESSION["role"] != "QA Coordinator") {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION["userid"];

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
  <!--JQuery Library-->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
      position: sticky;
      left: 50%;
      bottom: 1%;
      display: inline;
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
                <a class="nav-link collapsed" href="staff_details.php">
                  <i class="bi bi-person-check"></i><span>Staff Details</span>
                </a>
            </li><!-- End Staff Details Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="closure_date.php">
                  <i class="bi bi-calendar4-week"></i><span>Closure Date</span>
                </a>
            </li><!-- End Closure Date Nav -->
            <!--Category filter-->
            <li class="nav-item">
              <a class="nav-link collapsed" data-bs-target="#category-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-bar-chart"></i><span>Category</span><i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="category-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              </ul>
            </li>
 
            <?php
              echo '<li class="nav-item">';
              echo '<a href="EditIdea.php?id=' .$user_id.'" class="nav-link collapsed" data-bs-target="#statistics-nav;">';
              echo '<i class="bi bi-bar-chart"></i><span>Edit Idea</span>';
              echo '</a>';
              echo '</li>';
              ?>
 

            <?php
                if($_SESSION['role'] == "Admin"){ //QA Coordinator cannot see this
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
                }
            ?>
            
        </ul>

    </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Idea</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index_coordinator.php">Idea</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      

        <!-- Left side columns -->
          <div class="row">
            

            <div class="card-body">
                <div class="row align-items-center">
                <div class="col" id="sorting-btn">
                    <button id="latest_ideas" data-sorting="latest_ideas" class="btn btn-primary"><i class="bi bi-lightbulb"></i> Latest Ideas</button>
                    <button id="most_popular" data-sorting="most_popular" class="btn btn-primary"><i class="bi bi-star"></i> Most Popular</button>
                    <button id="most_viewed" data-sorting="most_viewed" class="btn btn-primary"><i class="bi bi-eye"></i> Most Viewed</button>
                    <button id="latest_comment" data-sorting="latest_comment" class="btn btn-primary"><i class="bi bi-chat-text"></i> Latest Comments</button>
                    <a href="submit_idea.php" class="btn btn-primary" style="background-color:#4CAF50; border-color:#4CAF50; float: right;"><i class="bi bi-file-earmark-text"></i> Submit Idea</a>
                  </div>
                </div>
              </div>
              
              <div id="posts-container">

              </div>  
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
  <script>
    $(document).ready(function(){
      updateCategoryFilter();

      function updateCategoryFilter(){
          $.ajax({
              url : 'get_categories.php',
              type : 'GET',
              success : function(response){
                  $('#category-nav').html(response);
              }
          });
      }
    });
  </script>
  <script>
    let page = 1;
    let category = 'All';
    let sorting = 'latest_ideas';

    function updateCategoryFilter(){
        $.ajax({
            url : 'get_categories.php',
            type : 'GET',
            success : function(response){
                $('#category-nav').html(response);
                loadPosts();
            }
        });
    }

    //function to load posts using AJAX
    function loadPosts(){
      console.log(
        'category', category
      );
      console.log(
        'sorting', sorting
      );
      console.log(
        'Page', page
      );
      $.ajax({
            url : `filter_sorting_post.php?page=${page}&category=${category}&sorting=${sorting}`,
            type : 'GET',
            data: {category : category, sorting: sorting, page: page},
            success : function(response){
              $('#posts-container').html(response);
            }
           
      });
    }

    //handle category click event
    $('#category-nav').on('click', '.category-link', function(e){
      e.preventDefault();
      //get option
      var category = $(this).data('category');
      loadPosts();
    });

    //handle sorting click event
    $('#sorting-btn').on('click', 'button.btn-primary', function(e){
      e.preventDefault();
      //get option
      var sorting = $(this).data('sorting');
      loadPosts();
    });
      
    function loadPage(pageNo){
        page = pageNo;
        loadPosts();
    }

    $(document).ready(function(){
    //get default options
    updateCategoryFilter();
    });
  </script>
</body>

</html>