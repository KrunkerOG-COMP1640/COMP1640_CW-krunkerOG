<?php
require_once 'krunkerideaconn.php';

$contributorscsv_query = "SELECT D.DepartmentName, U.Username, U.UserRoleName, U.UserEmail from user_tbl U
INNER JOIN department_tbl D 
ON U.DepartmentId = D.DepartmentId
WHERE D.DepartmentId IN ('1','2','3','4')";
$contributorscsv_query_run = mysqli_query($dbconn, $contributorscsv_query);

create_csv_file($contributorscsv_query_run);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="Contributors.csv"');
readfile('Contributors.csv');
exit();

function create_csv_file($data) {
    $file = fopen('Contributors.csv', 'w');
    
    // Write headers
    fputcsv($file, array('Department Name', 'Username', 'UserRoleName', 'UserEmail'));
    
    // Write data
    while ($row = mysqli_fetch_assoc($data)) {
        fputcsv($file, $row);
    }
    
    fclose($file);
}
?>