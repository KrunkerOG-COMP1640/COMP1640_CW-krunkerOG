<?php
require_once 'krunkerideaconn.php';

$departmentcsv_query = "SELECT * FROM department_tbl WHERE DepartmentId IN ('1','2','3','4')";
$departmentcsv_query_run = mysqli_query($dbconn, $departmentcsv_query);

create_csv_file($departmentcsv_query_run);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="Departments.csv"');
readfile('Departments.csv');
exit();

function create_csv_file($data) {
    $file = fopen('Departments.csv', 'w');
    
    // Write headers
    fputcsv($file, array('Department Id', 'Department Name'));
    
    // Write data
    while ($row = mysqli_fetch_assoc($data)) {
        fputcsv($file, $row);
    }
    
    fclose($file);
}
?>