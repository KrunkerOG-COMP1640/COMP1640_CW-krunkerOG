<?php
require("krunkerideaconn.php");

session_start();


$user_id = $_SESSION["userid"];

$page = isset($_GET['page']) ? $_GET['page'] : 1;
//determine the number of data per page
$rows_per_page = 5;

// Determine the starting row number for the current page
$start = ($page - 1) * $rows_per_page;

//handle category
$category = isset($_GET['category']) ? $_GET['category'] : 'All';
$category_sql = ' ';
if ($category != 'All') {
  $category_sql = "WHERE idea_tbl.CategoryId = '$category' AND is_hidden=0 ";
} else {
  $category_sql = 'WHERE is_hidden=0';
}


//handle sorting
$sort_by = isset($_GET['sorting']) ? $_GET['sorting'] : 'latest_ideas';
$sorting_sql = '';
if ($sort_by == 'most_viewed') {
  $sorting_sql = 'ORDER BY idea_tbl.ViewCount DESC';
} else if ($sort_by == 'most_popular') {
  $sorting_sql = 'ORDER BY COUNT(like_dislikepost_tbl.LikeStatus) DESC';
} else if ($sort_by == 'latest_comment') {
  $sorting_sql = 'ORDER BY comment_tbl.CommentId DESC';
} else {
  $sorting_sql = 'ORDER BY idea_tbl.IdeaId DESC';
}



$sql = "SELECT idea_tbl.IdeaId, idea_tbl.IdeaTitle, category_tbl.CategoryTitle, user_tbl.Username, idea_tbl.DatePost, idea_tbl.IdeaDescription, idea_tbl.IdeaAnonymous, idea_tbl.ViewCount from idea_tbl 
        INNER JOIN user_tbl ON idea_tbl.UserId =user_tbl.UserId 
        INNER JOIN category_tbl ON idea_tbl.CategoryId= category_tbl.CategoryId
        LEFT OUTER JOIN comment_tbl ON idea_tbl.ideaId = comment_tbl.ideaId
        LEFT OUTER JOIN like_dislikepost_tbl ON idea_tbl.ideaId = like_dislikepost_tbl.ideaId
        $category_sql
        GROUP BY idea_tbl.IdeaId
        $sorting_sql
        LIMIT $start, $rows_per_page";

$result = mysqli_query($dbconn, $sql);
//displaying every ideas from database
while ($row = mysqli_fetch_assoc($result)) {
  echo '<div class="card">';
  echo '<div class="card-body">';
  echo '<h1 class="card-title">' . $row['IdeaTitle'] . '</h1>';
  if ($row['IdeaAnonymous'] == 0) {
    echo '<h5 class="card-author">' . $row['Username'] . '</h5>';
  } else if ($row['IdeaAnonymous'] == 1) {
    echo '<h5 class="card-author">Anonymous</h5>';
  }

  echo '<h5 class="card-category">' . $row['CategoryTitle'] . '</h5>';
  echo '<p class="card-text">' . $row['IdeaDescription'] . '</p>';


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
        echo '         <img src="' . $imageidea_path . '"  alt="idea image" class="shadow-1-strong rounded mb-4" style="width: 200px; height: 200px; object-fit: contain;">';
        echo '   </div>';
      }
    }
    echo '               </div>';
    echo '            </div>';
    echo '        </section>';
    echo '    </div>';
    echo '</section>';
  }

  $likeidea_query = "SELECT COUNT(*) AS 'Like_Count' FROM like_dislikepost_tbl 
                    WHERE IdeaId = $ideaid AND LikeStatus = 1;";
  $likeidea_result = mysqli_query($dbconn, $likeidea_query);
  $likeidea_row = mysqli_fetch_assoc($likeidea_result);

  $dislikeidea_query = "SELECT COUNT(*) AS 'DisLike_Count' FROM like_dislikepost_tbl 
                    WHERE IdeaId = $ideaid AND DislikeStatus = 1;";
  $dislikeidea_result = mysqli_query($dbconn, $dislikeidea_query);
  $dislikeidea_row = mysqli_fetch_assoc($dislikeidea_result);
  echo '
            <form action="filter_sorting_post.php" method="post" enctype="multipart/form-data" style="margin-bottom:10px;">
              <input type="hidden" name="ideaid" value="' . $ideaid . '">
              
              <button type="submit" name="submit_like" class="btn btn-primary" style="background-color: darkcyan; margin:0 10px 0 0;"><i class="bi bi-hand-thumbs-up"></i></button>
              <span>' . $likeidea_row['Like_Count'] . '</span>
              
              <button type="submit" name="submit_dislike" class="btn btn-primary" style="background-color: darkcyan; margin: 0 10px 0 10px;"><i class="bi bi-hand-thumbs-down"></i></button>
              <span>' . $dislikeidea_row['DisLike_Count'] . '</span>
            </form>
           <a href="CommentSection.php?id=' . $ideaid . '" class="btn btn-primary" ">See More</a>
  ';

  echo '
     </div>
  </div>';
}

if (isset($_POST["submit_like"])  || isset($_POST["submit_dislike"])) {

  $likedislikeidea_query = "SELECT * FROM `like_dislikepost_tbl` WHERE IdeaId = $ideaid AND UserId = $user_id;";
  $likedislikeidea_result = mysqli_query($dbconn, $likedislikeidea_query);
  $likedislikeidea_count = mysqli_num_rows($likedislikeidea_result);

  $ideaid = $_POST['ideaid'];
  $likestat = isset($_POST["submit_like"]) ? 1 : 0;
  $dislikestat = isset($_POST["submit_dislike"])  ? 1 : 0;
  $sql_insertlikedislike = "INSERT INTO like_dislikepost_tbl (IdeaId, UserId, LikeStatus, DislikeStatus) 
                            SELECT '$ideaid','$user_id','$likestat', '$dislikestat'
                            WHERE NOT EXISTS (
                            SELECT * FROM like_dislikepost_tbl WHERE IdeaId = $ideaid AND UserId = $user_id
                            )";
  mysqli_query($dbconn, $sql_insertlikedislike);

  $sql_updatelikedislike = "UPDATE like_dislikepost_tbl SET LikeStatus = $likestat, DislikeStatus = $dislikestat
                            WHERE IdeaId = $ideaid AND UserId = $user_id;";
  mysqli_query($dbconn, $sql_updatelikedislike);

  echo "<script>window.location.href='index.php';</script>";
  exit();

}

$sql_page = "SELECT COUNT(*) AS count FROM idea_tbl $category_sql $sorting_sql";
$page_count = mysqli_query($dbconn, $sql_page);
$row_count = mysqli_fetch_assoc($page_count);
$total_rows = $row_count['count'];
$total_pages = ceil($total_rows / $rows_per_page);

echo '<div class="pagination">';

  if ($page > 1) {
    $prev = $page - 1;
    echo "<a href ='javascript:loadPage($prev)' style='padding = right: 20px;'>Prev</a>";
  }
  for ($i = 1; $i <= $total_pages; $i++){ 
      if ($page == $i){
          echo "<a class='active' href ='#'>$i</a>";
      }else{
          echo "<a href ='javascript:loadPage($i)'>$i</a>";
      }
//				  echo'<a href="?page='.$i.'&category='.$category.'&sorting='.$sort_by.'">'.$i.'</a>';
  }
  if($page<$total_pages){
    $next = $page+1;
    echo "<a href ='javascript:loadPage($next)' >Next</a>";
  }
echo '</div>';