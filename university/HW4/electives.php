<!DOCTYPE html>
<html>
<head>
<title>Edit elective</title>
</head>
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
$elective_with_id_query = 
"SELECT title, description, lecturer 
FROM electives WHERE id=:id";
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
//
function modify_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// validation
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$title = modify_input($_POST["title"]);
	$lecturer = modify_input($_POST["lecturer"]);
	$description = modify_input($_POST["description"]);
	$errors = array();
	if(empty($title))
		array_push($errors, "Title is required");
	else if(strlen($title) > 150)
		array_push($errors, "Title cannot be more than 150 symbols");
	
	if(empty($lecturer))
		array_push($errors, "Lecturer is required");
	else if(strlen($lecturer) > 200)
		array_push($errors, "Lecturer's name cannot be more than 200 symbols");

	if(empty($description))
		array_push($errors, "Description is required");
	else if(strlen($description) < 10)
		array_push($errors, "Description cannot be less than 10 symbols");
	if(count($errors) > 0)
	{
		echo "<ul style='color: red;'>";
		foreach($errors as $error)
			echo "<li>$error</li>";
		echo "</ul>";
	} else {
		$insert_elective_query = 
		"UPDATE electives 
		SET title=:title, description=:description, lecturer=:lecturer";
		$insert_elective = $conn->prepare($insert_elective_query);
		$insert_elective->bindParam(":title", $title);
		$insert_elective->bindParam(":description", $description);
		$insert_elective->bindParam(":lecturer", $lecturer);
		$insert_elective->execute();
		header("Location:electives.php");
	}
}
?>
<!--form for editing elective in database-->
<form action="electives.php" method="POST">
Име на предмета:<br>
<input type="text" name="title" value="<?php echo $title; ?>" autofocus/>
<br><br>
Преподавател:<br>
<input type="text" name="lecturer" value="<?php echo $lecturer; ?>"/>
<br><br>
Описание:<br>
<textarea name="description"><?php echo $description; ?></textarea>
<br><br>
<input type="submit" value="Редактирай"/>
</form>
</body>
</html>