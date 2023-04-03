<?php
require_once 'krunkerideaconn.php';

$ideascsv_query = "SELECT IdeaId, Username, UserRoleName, IdeaDescription, ViewCount, IdeaAnonymous, is_hidden, DatePost
FROM user_tbl U 
INNER JOIN idea_tbl I 
ON I.UserId = U.UserId";
$ideascsv_query_run = mysqli_query($dbconn, $ideascsv_query);

create_csv_file($ideascsv_query_run);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="Ideas.csv"');
readfile('Ideas.csv');
exit();

function create_csv_file($data) {
    $file = fopen('Ideas.csv', 'w');
    
    // Write headers
    fputcsv($file, array('Idea Id', 'Name', 'Role', 'Description', 'ViewCount', 'IdeaAnonymous', 'is_hidden', 'DatePost'));
    
    // Write data
    while ($row = mysqli_fetch_assoc($data)) {
        fputcsv($file, $row);
    }
    
    fclose($file);
}
?>