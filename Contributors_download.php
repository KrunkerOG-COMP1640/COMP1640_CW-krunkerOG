<?php
require_once 'krunkerideaconn.php';

$contributorscsv_query = "SELECT C.CategoryTitle, D.DepartmentName, COUNT(DISTINCT I.UserId) AS Total_Contributor
FROM department_tbl D
LEFT JOIN user_tbl U
ON U.DepartmentId = D.DepartmentId
RIGHT JOIN idea_tbl I
ON I.UserId = U.UserId
INNER JOIN category_tbl C
ON I.CategoryId = C.CategoryId
WHERE D.DepartmentId IN ('1','2','3','4')
GROUP BY C.CategoryTitle";
$contributorscsv_query_run = mysqli_query($dbconn, $contributorscsv_query);

create_csv_file($contributorscsv_query_run);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="Contributors.csv"');
readfile('Contributors.csv');
exit();

function create_csv_file($data) {
    $file = fopen('Contributors.csv', 'w');
    
    // Write headers
    fputcsv($file, array('Category Title', 'Department Name', 'Total_Contributor'));
    
    // Write data
    while ($row = mysqli_fetch_assoc($data)) {
        fputcsv($file, $row);
    }
    
    fclose($file);
}
?>