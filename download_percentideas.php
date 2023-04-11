<?php
require_once 'krunkerideaconn.php';

$percentideas = mysqli_query($dbconn, "SELECT D.DepartmentName, COUNT(I.IdeaId) AS 'Idea Count',
(COUNT(I.IdeaId) / (SELECT COUNT(*) FROM idea_tbl I)) * 100 AS 'Percentage'
FROM department_tbl D
INNER JOIN user_tbl U ON D.DepartmentId = U.DepartmentId
INNER JOIN idea_tbl I ON U.UserId = I.UserId
GROUP BY D.DepartmentName
ORDER BY COUNT(I.IdeaId) DESC"
);

// Open a file handle for writing the CSV file
$fh = fopen('Ideas by each Department(%).csv', 'w');

// Write the CSV header row
fputcsv($fh, ['Department', 'Idea Count', 'Percentage (%)']);

// Loop through the query result and write each row to the CSV file
while ($row = mysqli_fetch_assoc($percentideas)) {
    $department = $row["DepartmentName"];
    $total_ideas = $row["Idea Count"];
    $percentage = $row["Percentage"];
    fputcsv($fh, [$department, $total_ideas, $percentage]);
}

// Close the file handle
fclose($fh);

// Download the CSV file
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="Ideas by each Department(%).csv"');
readfile('Ideas by each Department(%).csv');
?>
