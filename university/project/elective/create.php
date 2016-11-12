<!DOCTYPE html>
<html>
<head>
<title>Create elective</title>
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

$title = $lecturer = $description = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$title = modify_input($_POST["title"]);
	$lecturer = modify_input($_POST["lecturer"]);
	$description = modify_input($_POST["description"]);
	
	$errors = array();
	if(empty($title))
		array_push($errors, "Title cannot be empty");
	else if(strlen($title) > 150)
		array_push($errors, "Title cannot be more than 150 symbols");
	
	if(empty($lecturer))
		array_push($errors, "Lecturer name cannot be empty");
	else if(strlen($lecturer) > 150)
		array_push($errors, "Lecturer name cannot be more than 200 symbols");
	
	if(empty($description))
		array_push($errors, "Description cannot be empty");
	else if(strlen($description) < 10)
		array_push($errors, "Description cannot be fewer than 10 symbols");
	
	if(count($errors) > 0) {
		echo '<ul style="color: red;">';
		foreach($errors as $error)
			echo "<li>$error</li>";
		echo '</ul>';
	}
	else {
		$insert_elective_sql = "INSERT INTO electives (title, lecturer, description) VALUES (:title, :lecturer, :description)";
		$insert_elective = $conn->prepare($insert_elective_sql);
		$insert_elective->bindParam(":title", $title);
		$insert_elective->bindParam(":lecturer", $lecturer);
		$insert_elective->bindParam(":description", $description);
		$insert_elective->execute();
		header("Location: ../list.php");
	}
}

function modify_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<form action="create.php" method="POST">
Име на предмета:<br>
<input type="text" name="title"/>
<br><br>
Преподавател:<br>
<input type="text" name="lecturer"/>
<br><br>
Описание:<br>
<textarea name="description"></textarea>
<br><br>
<input type="submit" value="Добави"/>
</form>
<br>
<a href="../list.php">Back to list</a>
</body>
</html>