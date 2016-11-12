<!DOCTYPE html>
<html>
<body>
<?php
// 1. Get the elective with the id from query string
$id = (isset($_GET["id"])) ? $_GET["id"] : '';

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
$elective_with_id_query = "SELECT * FROM electives WHERE id=:id";
$elective_with_id = $conn->prepare($elective_with_id_query);
$elective_with_id->bindParam(":id", $id);
$elective_with_id->execute();
$title = $lecturer = $description = "";
if($elective_with_id->rowCount() > 0)
{
	$elective = $elective_with_id->fetch(PDO::FETCH_ASSOC);
	$title = $elective["title"];
	$lecturer = $elective["lecturer"];
	$description = $elective["description"];
}
echo "<h1>".$title."</h1>";
echo "<h2>".$lecturer."</h2>";
echo "<p>".$description."<p>";
?>
<a href="../list.php">Back to list</a>
</body>
</html>