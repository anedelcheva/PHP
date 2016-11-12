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
$elective_with_id_query = "DELETE FROM electives WHERE id=:id";
$elective_with_id = $conn->prepare($elective_with_id_query);
$elective_with_id->bindParam(":id", $id);
$elective_with_id->execute();
header("Location:../list.php");
?>
</body>
</html>