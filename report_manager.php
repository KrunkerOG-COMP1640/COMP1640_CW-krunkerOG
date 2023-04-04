<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();
require("krunkerideaconn.php");
if($_SESSION["role"] != "QA Manager") {
  header("Location: login.php");
  exit;
}

  $user_id = $_SESSION["userid"];
  $select_sql = "SELECT * FROM user_tbl WHERE UserId = $user_id";
  $result_User = mysqli_query($dbconn, $select_sql);  
  $row_User = mysqli_fetch_assoc($result_User);

//Get total department data
$departmentquery = mysqli_query($dbconn, "SELECT D.DepartmentName, COUNT(I.IdeaId) as Total_Ideas 
FROM user_tbl U
RIGHT JOIN department_tbl D
ON U.DepartmentId = D.DepartmentId
LEFT JOIN idea_tbl I
ON I.UserId = U.UserId
WHERE D.DepartmentId IN ('1','2','3','4')
GROUP BY D.DepartmentName"
);
$departmentresult = $departmentquery;
$departmentdata = array();

while($row = $departmentresult ->fetch_assoc()) {
  $departmentdata[] = array('label' => $row['DepartmentName'], 'value' => $row['Total_Ideas']);
}
//echo json_encode($departmentdata);

//Get Contributor data
$contributorquery = mysqli_query($dbconn, "SELECT C.CategoryTitle, D.DepartmentName, COUNT(DISTINCT I.UserId) AS Total_Contributor
FROM department_tbl D
LEFT JOIN user_tbl U
ON U.DepartmentId = D.DepartmentId
RIGHT JOIN idea_tbl I
ON I.UserId = U.UserId
INNER JOIN category_tbl C
ON I.CategoryId = C.CategoryId
WHERE D.DepartmentId IN ('1','2','3','4')
GROUP BY C.CategoryTitle"
);
$contributorresult = $contributorquery;
$contributordata = array();

while($row = $contributorresult ->fetch_assoc()) {
   $contributordata[] = array('label' => $row['DepartmentName'], 'value' => $row['Total_Contributor']);
}
//echo json_encode($contributordata);

//Get Percentage for each department ideas
$percentideas = mysqli_query($dbconn, "SELECT D.DepartmentName, COUNT(I.IdeaId) AS Total_Ideas,
(SELECT COUNT(*) FROM idea_tbl) AS Total_Post
FROM user_tbl U, department_tbl D, idea_tbl I
WHERE U.DepartmentId = D.DepartmentId AND I.UserId = U.UserId
GROUP BY D.DepartmentName"
);
$percentideas_department = array();
$percentideas_data = array();

while ($row = mysqli_fetch_assoc($percentideas)) {
  $percentideas_department[] = $row["DepartmentName"];
  $percentideas_data[] = round(($row["Total_Ideas"] / $row["Total_Post"]) * 100, 2);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Krunker Idea Portal 2023 | Manager</title>
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
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- Include the Chart.js library -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.5.0/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.min.js"></script>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Department', 'Percentage'],
      <?php for ($i = 0; $i < count($percentideas_department); $i++) { ?>
        ['<?php echo $percentideas_department[$i]; ?>', <?php echo $percentideas_data[$i]; ?>],
      <?php } ?>
    ]);

    var options = {
      legend: {
        top: '5%',
        left: 'left'
      },
      height: 300,
      //chartArea: {width: '90%', height: '70%'},
      avoidLabelOverlap:false,
      pieHole: 1
    };
        

    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
    }
</script>

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index_manager.php" class="logo d-flex align-items-center">
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
        <a class="nav-link collapsed" href="index_manager.php">
          <i class="bi bi-grid"></i><span>Idea</span>
        </a>
      </li><!-- End Idea Nav -->

            <?php
              echo '<li class="nav-item">';
              echo '<a href="EditIdea.php?id=' .$user_id.'" class="nav-link collapsed" data-bs-target="#statistics-nav;">';
              echo '<i class="bi bi-pencil"></i>Edit Idea</span>';
              echo '</a>';
              echo '</li>';
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
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index_manager.php">Idea</a></li>
          <li class="breadcrumb-item active">Reports</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
          <!--No of Department Card -->
          <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
            <div class="card info-card department-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></a>
                <ul class="dropdown-menu text-center">
                  <li><a class="icon" href="department_download.php">Download</a></li>
                </ul>
              </div>
              <div class="card-body">
                <h5 class="card-title">No of Departments</h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-building"></i>
                  </div>
                  <div class="ps-3">
                    <?php
                    $dash_department_query = "SELECT * from department_tbl WHERE DepartmentId IN ('1','2','3','4')";
                    $dash_department_query_run = mysqli_query($dbconn, $dash_department_query);

                    if($department_total = mysqli_num_rows($dash_department_query_run))
                    {
                      echo '<h4 class="mb-0"> '.$department_total.'</h4>';
                    }
                    else
                    {
                      echo '<h4 class="mb-0">No Data</h4>';
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End No of Department Card -->

          <!--Total Contributors Card -->
          <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
            <div class="card info-card staff-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></a>
                <ul class="dropdown-menu text-center">
                  <li><a class="icon" href="Contributors_download.php">Download</a></li>
                </ul>
              </div>
              <div class="card-body">
                <h5 class="card-title">Total Contributors</h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-people"></i>
                  </div>
                  <div class="ps-3">
                    <?php
                    $dash_user_query = "SELECT D.DepartmentName, U.Username, U.UserRoleName, U.UserEmail from user_tbl U
                    INNER JOIN department_tbl D 
                    ON U.DepartmentId = D.DepartmentId
                    WHERE D.DepartmentId IN ('1','2','3','4')";
                    $dash_user_query_run = mysqli_query($dbconn, $dash_user_query);

                    if($user_total = mysqli_num_rows($dash_user_query_run))
                    {
                      echo '<h4 class="mb-0"> '.$user_total.'</h4>';
                    }
                    else
                    {
                      echo '<h4 class="mb-0">No Data</h4>';
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--End Total Contributors Card -->

          <!--% Idea by Departments Card -->
          <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
            <div class="card info-card idea-card">
            <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></a>
                  <ul class="dropdown-menu text-center">
                    <li><a class="icon" href="Ideas_download.php">Download</a></li>
                  </ul>
                </div>
              <div class="card-body">
                <h5 class="card-title">Ideas by Departments</h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-lightbulb"></i>
                  </div>
                  <div class="ps-3">
                    <?php
                    $dash_idea_query = "SELECT * from idea_tbl";
                    $dash_idea_query_run = mysqli_query($dbconn, $dash_idea_query);

                    if($idea_total = mysqli_num_rows($dash_idea_query_run))
                    {
                      echo '<h4 class="mb-0"> '.$idea_total.'</h4>';
                    }
                    else
                    {
                      echo '<h4 class="mb-0">No Data</h4>';
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--End % Idea by Departments Card -->

          <!--Like Ideas by Departments Card -->
          <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
            <div class="card info-card likeidea-card">
            <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></a>
                  <ul class="dropdown-menu text-center">
                    <li><a class="icon" href="LikeIdeas_download.php">Download</a></li>
                  </ul>
                </div>
              <div class="card-body">
                <h5 class="card-title">Total Likes </h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-hand-thumbs-up"></i>
                  </div>
                  <div class="ps-3">
                    <?php
                    $dash_likeideas_query = "SELECT LD.LikeStatus, COUNT(I.IdeaId) AS Total_Ideas, 
                    (SELECT COUNT(LikeStatus) FROM like_dislikepost_tbl LD) AS Total_Post
                    FROM like_dislikepost_tbl LD, user_tbl U, idea_tbl I
                    WHERE LD.IdeaId = I.IdeaId AND I.UserId = U.UserId
                    GROUP BY LD.LikeDislikePostId";

                    $dash_likeideas_query_run = mysqli_query($dbconn, $dash_likeideas_query);

                    if($likeideas_total = mysqli_num_rows($dash_likeideas_query_run))
                    {
                      echo '<h4 class="mb-0"> '.$likeideas_total.'</h4>';
                    }
                    else
                    {
                      echo '<h4 class="mb-0">No Data</h4>';
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--End Like Ideas by Departments Card -->

          <div class="row">
            <!-- Idea by Departments Reports -->
            <div class="col-lg-7">
              <div class="card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow text-center">
                    <li><a class="icon" onclick="downloadCSV()"> Download</a></li>
                  </ul>
                </div>
                <div class="card-body pt-0">
                  <h5 class="card-title">Idea by Departments</h5>
                  <!-- Create a canvas element for the chart -->
                  <canvas id="BarChart"></canvas>
                  
                  <script>
                  // Get the data from PHP
                  var ctx = document.getElementById('BarChart').getContext('2d');
                  var chartData = <?php echo json_encode($departmentdata); ?>;

                  // Initialize the chart

                  var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                      labels: chartData.map(data => data.label),
                      datasets: [{
                        label: 'Number of ideas per department',
                        data: chartData.map(data => data.value),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                      }]
                    },
                    options: {
                      scales: {
                        yAxes: [{
                          ticks: {
                            beginAtZero: true
                          }
                        }]
                      }
                    }
                  });
                  // Function to download CSV file
                  function downloadCSV() {
                    // Create CSV data from chart data
                    var csvData = '';
                    chartData.forEach(function(data) {
                      csvData += data.label + ',' + data.value + '\n';
                    });
                    // Create new CSV file and download it
                    var link = document.createElement('a');
                    link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csvData));
                    link.setAttribute('download', 'Idea_by_Departments.csv');
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                  }
                  </script>
                  </div>
                </div>
              </div>
              <!-- End Idea by Departments Reports -->

              <!-- Percentage of ideas by each Department -->
              <div class="col-lg-5">
                <div class="card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow text-center">
                    <li><a class="icon" onclick="location.href='download_percentideas.php'"> Download</a></li>
                  </ul>
                </div>
                  <div class="card-body pt-0">
                    <h5 class="card-title">Percentage of ideas by each Department</h5>
                    
                    <!-- Create a canvas element for the chart -->
                    <div id="chart_div"></div>
                  </div>
                </div>
              </div>
              <!-- End Percentage of ideas by each Department -->
          </div>
          <!-- End Row -->

          <!-- Start Row -->
          <div class="row">

            <!-- Number of Contributors each Department Reports -->
            <div class="col-lg-8">
              <div class="card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow text-center">
                    <li><a class="icon" onclick="ContributordownloadCSV()"> Download</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Number of Contributors each Department</h5>
                  
                  <!-- Create a canvas element for the chart -->
                  <canvas id="myChart"></canvas>
                  
                  <script>
                  // Get the data from PHP
                  var ctx = document.getElementById('myChart').getContext('2d');
                  var chartData = <?php echo json_encode($contributordata); ?>;

                  // Initialize the chart
                  var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                      labels: chartData.map(data => data.label),
                      datasets: [{
                        label: 'Total Contributors',
                        data: chartData.map(data => data.value),
                        backgroundColor: 'rgba(136, 31, 222, 0.5)',
                        borderColor: 'rgba(137, 0, 250, 1)',
                        borderWidth: 1
                      }]
                    },
                    options: {
                      scales: {
                        yAxes: [{
                          ticks: {
                            beginAtZero: true
                          }
                        }]
                      },
                    }
                  });
                  function ContributorCSV(csv, filename) {
                    var csvFile;
                    var downloadLink;

                    // CSV file
                    csvFile = new Blob([csv], {type: "text/csv"});

                    // Download link
                    downloadLink = document.createElement("a");

                    // File name
                    downloadLink.download = filename;

                    // We create a link to the file
                    downloadLink.href = window.URL.createObjectURL(csvFile);

                    // We add the link to the DOM so it becomes clickable
                    document.body.appendChild(downloadLink);

                    // We trigger the click event to start the download
                    downloadLink.click();
                  }

                  function ContributordownloadCSV() {
                      var csv = 'Department,Contributors\n';
                      <?php foreach($contributordata as $data): ?>
                          csv += '<?php echo $data['label'];?>,' + <?php echo $data['value'];?> + '\n';
                      <?php endforeach; ?>

                      ContributorCSV(csv, 'Contributors_by_department.csv');
                  }
                  </script>
                  </div>
                </div>
              </div>
              <!-- End Number of Contributors each Department Reports -->

          </div>
          <!-- End Row -->

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