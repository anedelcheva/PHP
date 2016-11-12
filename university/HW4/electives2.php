<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
    border: 1px solid black;
}
</style>
</head>
<body>
<?php
// 1. Get the elective with the id from query string
$lecturer_name = (isset($_GET["lecturer"])) ? $_GET["lecturer"] : '';
$lecturer_name = str_replace('"', "", $lecturer_name);
$lecturer_name = str_replace("'", "", $lecturer_name);

// 2. Connect to database
$servername = "localhost:3306";
$dbname = "university";
$username = "root";
$password = "";
$conn = null;
try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
	die("Connection failed: ". $e->getMessage());
}

// 3. Get elective from the database with the id in the query string
$lecturer_query = "SELECT title, description, lecturer FROM electives WHERE lecturer = :lecturer_name";
$elective_with_lecturer = $conn->prepare($lecturer_query);
$elective_with_lecturer->bindParam(":lecturer_name", $lecturer_name);
$elective_with_lecturer->execute();
$title = $lecturer = $description = "";
if($elective_with_lecturer->rowCount() > 0)
{
	echo '<table style="width:100%">';
	echo "<tr>";
	echo "<th>Title</th>";
	echo "<th>Lecturer</th>";
	echo "<th>Description</th>";
	echo "</tr>";
	while($elective = $elective_with_lecturer->fetch(PDO::FETCH_ASSOC))
	{
		$title = $elective["title"];
		$lecturer = $elective["lecturer"];
		$description = $elective["description"];
		echo "<tr>";
		echo "<td>".$title."</td>";
		echo "<td>".$lecturer."</td>";
		echo "<td>".$description. "</td>";
		echo "</tr>";
	}
	echo "</table>";
}
?>
</body>
</html>