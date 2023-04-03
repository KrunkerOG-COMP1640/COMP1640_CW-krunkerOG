<?php
require_once 'krunkerideaconn.php';

$likecsv_query = "SELECT LD.LikeStatus, I.IdeaId, I.IdeaTitle, U.Username, U.UserRoleName, D.DepartmentName, COUNT(I.IdeaId) as Total_Likes
FROM  idea_tbl I 
LEFT JOIN like_dislikepost_tbl LD
ON I.IdeaId = LD.IdeaId
RIGHT JOIN user_tbl U
ON I.UserId = U.UserId
RIGHT JOIN department_tbl D
ON U.DepartmentId = D.DepartmentId
WHERE LD.LikeDislikePostId
GROUP BY LD.LikeStatus";

$likecsv_query_run = mysqli_query($dbconn, $likecsv_query);

create_csv_file($likecsv_query_run);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="LikeIdeas.csv"');
readfile('LikeIdeas.csv');
exit();

function create_csv_file($data) {
    $file = fopen('LikeIdeas.csv', 'w');
    
    // Write headers
    fputcsv($file, array('Like Status', 'Idea Id', 'Idea Title', 'User Name', 'Role Name ', 'Department Name', 'Total likes'));
    
    // Write data
    while ($row = mysqli_fetch_assoc($data)) {
        fputcsv($file, $row);
    }
    
    fclose($file);
}
?>
