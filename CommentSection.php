<?php
session_start();
require("krunkerideaconn.php");
if(!isset($_SESSION["username"]) && !isset($_SESSION["userid"])) {
    header("Location: login.php");// Redirect to login page if not logged in
    exit;

}
$dbconn = mysqli_connect("localhost", "root", "", "krunkerideadb");


$id = $_GET['id']; // get ideaID
$sql = "SELECT idea_tbl.IdeaId, idea_tbl.IdeaTitle, category_tbl.CategoryTitle, user_tbl.Username, idea_tbl.DatePost, idea_tbl.IdeaDescription, idea_tbl.IdeaAnonymous from idea_tbl  
INNER JOIN user_tbl ON idea_tbl.UserId =user_tbl.UserId 
INNER JOIN category_tbl ON idea_tbl.CategoryId= category_tbl.CategoryId 
WHERE IdeaId=$id";
$result = mysqli_query($dbconn, $sql);

if(isset($_POST["submit_comment_post"])){

    $user_id = $_SESSION["userid"];
      $comment =  $_POST["CommentDetails"];
    $anonymous = isset($_POST["anonymous"]);
      mysqli_query($dbconn, "INSERT INTO comment_tbl (UserId, CommentDetails, CommentAnonymous, IdeaId) 
                              VALUES ('$user_id','$comment','$anonymous', '$id')");
    header("Location:CommentSection.php?id=".$id);
      exit();
  }
  
$show = "SELECT comment_tbl.IdeaId, comment_tbl.CommentDetails, comment_tbl.DateComment, comment_tbl.CommentAnonymous, user_tbl.Username from comment_tbl
INNER JOIN user_tbl ON comment_tbl.UserId = user_tbl.UserId
WHERE IdeaId=$id";
$showComment = mysqli_query($dbconn, $show);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Krunker Idea Portal 2023</title>
    <meta content="" name="description">
    <meta content="" name="keywords">


<!-- Font Awesome Kit CSS -->
<link rel="stylesheet" href="https://kit.fontawesome.com/bb8f73d07f.css" crossorigin="anonymous">
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

    <style>
        .pagination {
            text-align: center;
            display: inline;
            letter-spacing: 10px;
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

        <div class="search-bar">
            <form class="search-form d-flex align-items-center" method="POST" action="#">
                <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div><!-- End Search Bar -->

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

                <li class="nav-item dropdown">

                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-chat-left-text"></i>
                        <span class="badge bg-success badge-number">3</span>
                    </a><!-- End Messages Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                        <li class="dropdown-header">
                            You have 3 new messages
                            <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li class="message-item">
                            <a href="#">
                                <img src="assets/img/messages-1.jpg" alt="" class="rounded-circle">
                                <div>
                                    <h4>Maria Hudson</h4>
                                    <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                    <p>4 hrs. ago</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li class="message-item">
                            <a href="#">
                                <img src="assets/img/messages-2.jpg" alt="" class="rounded-circle">
                                <div>
                                    <h4>Anna Nelson</h4>
                                    <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                    <p>6 hrs. ago</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li class="message-item">
                            <a href="#">
                                <img src="assets/img/messages-3.jpg" alt="" class="rounded-circle">
                                <div>
                                    <h4>David Muldon</h4>
                                    <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                    <p>8 hrs. ago</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li class="dropdown-footer">
                            <a href="#">Show all messages</a>
                        </li>

                    </ul><!-- End Messages Dropdown Items -->

                </li><!-- End Messages Nav -->

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $_SESSION["username"]; ?></span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?php echo $_SESSION["username"]; ?></h6>
                            <span>Web Designer</span>
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

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#statistics-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-bar-chart"></i><span>Statistics</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="statistics-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="#">
                            <i class="bi bi-circle"></i><span>Charts</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Statistics Nav -->

            <li class="nav-heading">Pages</li>


            <li class="nav-item">
                <a class="nav-link collapsed" href="ManageUser_admin.php">
                    <i class="bi bi-people"></i>
                    <span>Manage User</span>
                </a>
            </li><!-- End Manage User Page Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="ManageIdea_admin.php">
                    <i class="bi bi-chat-left-text"></i>
                    <span>Manage Idea</span>
                </a>
            </li><!-- End Manage Idea Page Nav -->
        </ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Idea</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index_admin.php">Idea</a></li>
                    <li class="breadcrumb-item"><a href="CommentSection.php">Comments</a></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <!-- <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-lg-10">
                        <div class="card">
                            <div class="card-body">
                                    <div class="media-mt-10">
                                        <img class="align-self-start mr-3" src="assets/img/profile-img-64x64.jpg" alt="Generic placeholder image" />
                                        <div class="media-body">
                                            <h5 class="mt-0">User</h5>
                                            <p class="" float="right">#Facilites</p>
                                        </div>
                                    </div>
                                <p>Cleaness</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="container">
<?php
             
                while ($row = mysqli_fetch_assoc($result)) {

                    
                    ?>
                <div class="row">
                    <div class="col-12 col-md-8 col-lg-9 col-xl-9">
                        <div class="card border-0 mb-4">
                            <div class="card-body">
                                <div class="row align-items-center mb-3">
                                    <div class="col">
                                      <?= '<h1 class="card-title">' . $row['IdeaTitle'] . '</h1>';?>
                                    </div>
                                    <div class="col-auto">
                                        <a href="index.php"><i class="fa-regular fa-x fa-2x"></i></a>
                                    </div> 
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        <figure class="rounded pill">
                                            <img src="assets/img/profile-img-64x64.jpg" alt="">
                                        </figure>
                                    </div>
                                    <div class="col px-0">
                                        <p class="small text-secondary mb-0">Posted by</p>
                                       <?= '<h5 class="card-author">' . $row['Username'] . '</h5>'; ?>
                                        <!-- <p class="mb-0">User <small class="text-secondary">1 hr ago</small></p> -->
                                    </div>
                                    <div class="col-auto text-end">
                                        <p class="small text-secondary mb-0">Posted at</p>
                                        <?= '<h5 class="card-author">' . $row['DatePost'] . '</h5>'; ?>
                                    </div>
                                </div>
                                <?= '<p class="card-text">' . $row['IdeaDescription'] . '</p>'; ?>
                                <!-- Gallery -->
                                <?php
                                    $ideaid = $row['IdeaId'];
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

                                    ?>
                                    <?= '<h5 class="btn btn-primary rounded-pill">' . $row['CategoryTitle'] . '</h5>'; ?>
                                    
                                    <form action="" method="post">
                                        <!-- Gallery -->
                                        <p>
                                            <!-- <span class="btn btn-primary rounded-pill">Support</span> -->

                                            <hr>
                                    <div class="mb-3 form-check">
                                    <input type="checkbox" id="anonymous" name="anonymous" class="form-check-input" value="1">
                                    <label for="anonymous" class="form-check-label">Comment anonymously</label>
                                        </div>
                                </p>
                            </div>
                            
                     
                            <div class="card-footer">
                                <div class="input-group">
                                    <input type="text" required name ="CommentDetails" class="form-control border" placeholder="Your comment here..." >
                                    <button class="btn btn-light border" type="submit" name ="submit_comment_post">Comment</button>
                
                                </div>
                            </div>
                        </div>
                </form>
                    </div>
            
<?php
                }
?>

                    <h5 class="title">Comments</h5>
                    <?php
                    while ($shoCom = mysqli_fetch_assoc($showComment)) {
                        ?>
                    <div class="col-12 col-md-8 col-lg-9 col-xl-9">
                    <div class="card border-0 mb-4">
                        <div class="card-body">
                            <br>
                            <div class="row align-items-center mb-3">
                                <div class="col-auto">
                                    <figure class="rounded pill">
                                        <img src="assets/img/profile-img-64x64.jpg" alt="">
                                    </figure>
                                </div>
                                <div class="col px-0">
                                    <p class="small text-secondary mb-0">Commented by</p>
                                   <?php if($shoCom['CommentAnonymous'] == 0){
                                    
                                        echo '<p class="mb-0">' .$shoCom['Username'].'</p>'; 
                                    }
                                    
                                    else if($shoCom['CommentAnonymous'] == 1){
                                        echo '<p class="mb-0">Anonymous</h5>';
                                    }
?>

                                </div>
                                <div class="col-auto text-end">
                                    <p class="small text-secondary mb-0">Commented on</p>
                                    <?= '<p class="mb-0">'.$shoCom['DateComment'].'</p>'; ?>
                                </div>
                            </div>
                            <?= '<p class="text-secondary">'.$shoCom['CommentDetails'].'</p>'; ?>
                        </div>
                    </div>
                    </div>
                    
                    <?php
                }
?>
                 
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
<!-- Font Awesome Kit script -->
<script src="https://kit.fontawesome.com/bb8f73d07f.js" crossorigin="anonymous"></script>

</body>

</html>