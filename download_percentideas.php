<?php
require_once 'krunkerideaconn.php';

$percentideas = mysqli_query($dbconn, "SELECT D.DepartmentName, COUNT(I.IdeaId) AS Total_Ideas,
(SELECT COUNT(*) FROM idea_tbl) AS Total_Post
FROM user_tbl U, department_tbl D, idea_tbl I
WHERE U.DepartmentId = D.DepartmentId AND I.UserId = U.UserId
GROUP BY D.DepartmentName"
);

// Open a file handle for writing the CSV file
$fh = fopen('Ideas by each Department(%).csv', 'w');

// Write the CSV header row
fputcsv($fh, ['Department', 'Percentage (%)', 'Total Ideas', 'Total Post']);

// Loop through the query result and write each row to the CSV file
while ($row = mysqli_fetch_assoc($percentideas)) {
    $department = $row["DepartmentName"];
    $total_ideas = $row["Total_Ideas"];
    $total_post = $row["Total_Post"];
    $percentage = round(($row["Total_Ideas"] / $row["Total_Post"]) * 100, 2);
    fputcsv($fh, [$department, $percentage, $total_ideas, $total_post]);
}

// Close the file handle
fclose($fh);

// Download the CSV file
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="Ideas by each Department(%).csv"');
readfile('Ideas by each Department(%).csv');
?>
