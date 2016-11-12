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
$elective_with_id_query = "SELECT title, description, lecturer FROM electives WHERE id=:id";
$elective_with_id = $conn->prepare($elective_with_id_query);
$elective_with_id->bindParam(":id", $id);
$elective_with_id->execute();
<<<<<<< HEAD
$title = $lecturer = $description = $id_db = "";
>>>>>>> 1ca20a61db8a65ce15a3290fb81f67848fb85205
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
<<<<<<< HEAD
	$id_db = modify_input($_POST["id"]);
>>>>>>> 1ca20a61db8a65ce15a3290fb81f67848fb85205
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
<<<<<<< HEAD
		$insert_elective_query = "UPDATE electives SET title = :title, description = :description, lecturer = :lecturer WHERE id = :id";
>>>>>>> 1ca20a61db8a65ce15a3290fb81f67848fb85205
		$insert_elective = $conn->prepare($insert_elective_query);
		$insert_elective->bindParam(":title", $title);
		$insert_elective->bindParam(":description", $description);
		$insert_elective->bindParam(":lecturer", $lecturer);
<<<<<<< HEAD
		$insert_elective->bindParam(":id", $id_db);
>>>>>>> 1ca20a61db8a65ce15a3290fb81f67848fb85205
		$insert_elective->execute();
		header("Location:electives.php");
	}
}
?>
<!--form for editing elective in database-->
<form action="electives.php" method="POST">
<<<<<<< HEAD
<input type="hidden" name="id" value="<?php echo $id; ?>"/>
>>>>>>> 1ca20a61db8a65ce15a3290fb81f67848fb85205
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