<!DOCTYPE html>
<html>
<body>

<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "university";
$conn = null;
try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
	die("Connection failed: ".$e->getMessage());
}
$subject = $lecturer = $description = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$subject = modify_input($_POST["subject"]);
	$lecturer = modify_input($_POST["lecturer"]);
	$description = modify_input($_POST["description"]);
	$errors = array();
	if(empty($subject))
		array_push($errors, "Subject is required");
	elseif(strlen($subject) > 150)
		array_push($errors, "Subject should be fewer than 150 symbols");
		
	if(empty($lecturer))
		array_push($errors, "Lecturer is required");
	elseif(strlen($lecturer) > 200)
		array_push($errors, "Lecturer should be fewer than 200 symbols");
	
	if(empty($description))
		array_push($errors, "Description is required");
	elseif(strlen($description) < 10)
		array_push($errors, "Description should be at least 10 symbols");
	
	if (count($errors) > 0) {
		echo '<ul style="color: red;">';
		foreach ($errors as $error)
			echo "<li>$error</li>";
		echo '</ul>';
	}
	else {
		$add_column_created_at = 
		"ALTER TABLE `electives` 
		ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP 
		AFTER `lecturer`;";
		$conn->query($add_column_created_at);
		$insertElectiveQuery = 
		$conn->prepare("INSERT INTO 
		electives`(`title`, `description`, `lecturer`) 
		VALUES (:subject, :description, :lecturer)");
		$insertElectiveQuery->bindParam(':subject', $subject);
		$insertElectiveQuery->bindParam(':description', $description);
		$insertElectiveQuery->bindParam(':lecturer', $lecturer);
		$insertElectiveQuery->execute();
	}
}

function modify_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
<form action="add_elective.php" method="POST">
	Име на предмета:<br/>
	<input type="text" name="subject" size="40" autofocus>
	<br/><br/>
	Преподавател: <br/>
	<input type="text" name="lecturer" size="40">
	<br/><br/>
	Описание: <br/>
	<textarea name="description" rows="5" cols="30"></textarea>
	<br/><br/>
	<input type="submit" value="Добави">
</form>
</body>
</html>