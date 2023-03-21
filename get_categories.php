<?php
$dbconn = mysqli_connect("localhost", "root", "", "krunkerideadb");
$categories = mysqli_query($dbconn, "SELECT * FROM category_tbl");

$html='';
$html.='<li><a href="#" class="category-link" data-category="All">All</a></li>';
foreach ($categories as $category){
    $html.='<li><a href="#" class="category-link" data-category="'.$category['CategoryId'].'">'.$category['CategoryTitle'].'</a></li>';
}
echo $html;
?>