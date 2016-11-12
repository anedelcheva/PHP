<!DOCTYPE html>
<html>
<head>
<title>Electives list</title>
</head>
<body>
<?php
$servername = "localhost:3306";
$dbname = "university";
$username = "root";
$password = "";
$conn = null;
try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
	die("Connection failed: ".$e->getMessage());
}

echo '<a href="elective/create.php">Add elective</a>';
$get_electives_sql = "SELECT * FROM electives";
$electives = $conn->query($get_electives_sql);
if($electives->rowCount() > 0)
{
	echo "<table>";
	while($elective = $electives->fetch(PDO::FETCH_ASSOC)) {
		echo 
		"<tr>
		<td>".$elective["title"]."</td>
		<td><a href='elective/edit.php?id=".$elective["id"]."'>Edit</a></td>
		<td><a href='elective/details.php?id=".$elective["id"]."'>Details</a></td>
		<td><a href='elective/delete.php?id=".$elective["id"]."'>Delete</a></td>
		</tr>";
	}
	echo "</table>";
}
?>
</body>
</html>