<?php
include("../cookies.php");
$role = 2; // for teacher
if(!isset($_COOKIE["email"]) || !isset($_COOKIE["roleID"]) || $_COOKIE["roleID"] != $role)
	header("Location: ../login.php");

// set cookie every time the user visits the page (the user is active)
if(isset($_COOKIE["email"]))
	setCookiesForUser($_COOKIE["email"], $role, $seconds);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Редактирай дисциплина</title>
	<link rel="stylesheet" type="text/css" href="../css/register_login.css"/>
</head>
<body>
<?php
// 1. Get the elective with the id from query string
$id = (isset($_GET["id"])) ? $_GET["id"] : '';

// 2. Connect to database
include("../configDB.php");
include("../validate.php");

// 3. Get elective from the database with the id in the query string
$elective_with_id_query = "SELECT * FROM elective WHERE id=:id";
$elective_with_id = $conn->prepare($elective_with_id_query);
$elective_with_id->bindParam(":id", $id);
$elective_with_id->execute();

$name = $description = $qualification = $courseID = $this_course = $semester = $credits = $categoryID = $this_category = $typeID = $this_type = $id_db = $createdBy = "";
if($elective_with_id->rowCount() > 0)
{
	$elective = $elective_with_id->fetch(PDO::FETCH_ASSOC);
	$name = $elective["name"];
	$description = $elective["description"];
	$qualification = $elective["qualification"];
	$courseID = $elective["courseID"];
	$semester = $elective["semester"];
	$credits = $elective["credits"];
	$categoryID = $elective["categoryID"];
	$typeID = $elective["typeID"];
	$createdBy = $elective["createdBy"];
}
// validation
$name_check = $description_check = $qualification_check = $semester_check = $course_check = $credits_check = $category_check = $type_check = $entry_check = $createdByID = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$id_db = modify_input($_POST["id"]);
	$createdByID = modify_input($_POST["createdBy"]);
	
	if(isset($_POST["name"]))
		$name = modify_input($_POST["name"]);
	if(isset($_POST["description"]))
		$description = modify_input($_POST["description"]);
	
	if(isset($_POST['credits']))
		$credits = doubleval(modify_input($_POST['credits']));
	
	if(isset($_POST['category']))
		$categoryID = intval(modify_input($_POST['category']));
	
	if(isset($_POST['type']))
		$typeID = intval(modify_input($_POST['type']));
	
	if(isset($_POST['qualification']))
		$qualification = intval(modify_input($_POST['qualification']));
	
	if($qualification == 0)
		if(isset($_POST['bachelor_courses']))
			$courseID = intval(modify_input($_POST['bachelor_courses']));
	else
		if(isset($_POST['master_courses']))
			$courseID = intval(modify_input($_POST['master_courses']));
	
	if(isset($_POST['semester']))
		$semester = intval(modify_input($_POST['semester']));
	
	// check if an elective with this name, category and type already exists in DB
	$get_elective_sql = "SELECT * FROM elective WHERE name='$name' AND typeID='$typeID' AND categoryID='$categoryID'";
	$get_elective = $conn->query($get_elective_sql);

	if($get_elective->rowCount() > 0) {
		$entry_check = "Дисциплина с това име, тип и категория вече съществува.";
	}
		
	
	$name_check = checkElectiveName($name);
	$description_check = checkElectiveDescription($description);
	$credits_check = checkElectiveCredits($credits);
	$semester_check = ifEmpty($semester, "Изберете семестър.");
	$qualification_check = ifEmpty($qualification, "Изберете ОКС.");
	$course_check = ifEmpty($courseID, "Курсът не е наличен.");
	$category_check = ifEmpty($categoryID, "Категорията не е налична.");
	$type_check = ifEmpty($typeID, "Типът не е наличен.");
	
	if(empty($name_check) && empty($description_check) && empty($credits_check) && 
	   empty($semester_check) && empty($qualification_check) && empty($course_check) && 
	   empty($category_check) && empty($type_check) && empty($entry_check)) {
		$update_elective_query = "UPDATE elective SET name = :name, description = :description, 
								  qualification = :qualification, courseID = :courseID, semester = :semester,
								  credits = :credits, categoryID = :categoryID, typeID = :typeID, createdBy = :createdBy WHERE id = :id";
		$update_elective = $conn->prepare($update_elective_query);
		$update_elective->bindParam(":name", $name);
		$update_elective->bindParam(":description", $description);
		$update_elective->bindParam(":id", $id_db);
		$update_elective->bindParam(":qualification", $qualification);
		$update_elective->bindParam(":courseID", $courseID);
		$update_elective->bindParam(":semester", $semester);
		$update_elective->bindParam(":credits", $credits);
		$update_elective->bindParam(":categoryID", $categoryID);
		$update_elective->bindParam(":typeID", $typeID);
		$update_elective->bindParam(":createdBy", $createdByID);
		$update_elective->execute();
		header("Location: list.php");
   }
}
?>
<!-- start header-->
<div id="header_options">
	<a href="add3.php" id="href_btn">Добави дисциплина</a>
	<a href="list.php" id="href_btn">Виж свои дисциплини</a>
	<a href="list_all.php" id="href_btn">Виж всички дисциплини</a>
	<a href="../change_lecturer_password.php" id="href_btn">Промени паролата</a>
</div>
<div id="header_logout">
Добре дошли, 
<?php
$email = "";
if(isset($_COOKIE["email"]))
	$email = $_COOKIE["email"];
// 1. take user id from email
$get_admin_sql = "SELECT * FROM user WHERE email='$email'";
$user = $conn->query($get_admin_sql);
$userID = "";
if($user->rowCount() > 0) {
	$admin = $user->fetch(PDO::FETCH_ASSOC);
	$userID = $admin["id"];
	echo $admin["firstname"]." ".$admin["lastname"]." ";
}
?>
<a href="../logout.php" id="href_btn">Изход</a>
</div>
<!-- end header-->
<div id="elective_wrapper">
<p class="green_p">РЕДАКТИРАЙ ДИСЦИПЛИНА</p>
<form action="edit.php" method="POST" class="general" id="elective">
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="createdBy" value="<?php echo $createdBy; ?>"/>
	<input type="text" id="name_edit" name="name" value="<?php echo $name; ?>" autofocus/>
	<textarea id="description_edit" name="description"><?php echo $description; ?></textarea>
	<div>
		<input type="radio" name="qualification" id="bachelor" value="0" <?php echo ($qualification == 0) ? 'checked' : ''?> />
		<label class="radio" for="bachelor">Бакалавър</label><br>

		<input type="radio" name="qualification" id="master" value="1" <?php echo ($qualification == 1) ? 'checked' : ''?> />
		<label class="radio" for="master">Магистър</label>
	</div>
	<label class="not_radio">Курс: </label>
<?php
if($qualification == 0) {
// start dropdown bachelor_course
	echo '<select name="bachelor_courses" id="bachelor_courses">';
	$get_courses_sql = "SELECT * FROM course";
	$courses = $conn->query($get_courses_sql);
	$id = 1;
	if($courses->rowCount() > 0)
	{
		while($course = $courses->fetch(PDO::FETCH_ASSOC)) {
			$selected = '';
			if($id == $courseID)
				$selected = 'selected="selected"';
			else
				$selected = '';
			echo '<option value="'.$id.'" '.$selected.'>'.$course["name"].'</option>'.'<br>';
			$id += 1;
		}
	}
	echo '</select>';
// end dropdown bachelor_course
}

if($qualification == 1) {
// start dropdown master_course
	echo '<select name="master_courses" id="master_courses">';
	$get_courses_sql = "SELECT * FROM course";
	$courses = $conn->query($get_courses_sql);
	$id = 1;
	if($courses->rowCount() > 0)
	{
		while($course = $courses->fetch(PDO::FETCH_ASSOC)) {
			if($id == 1 || $id == 2 || $id == 5) {
				$selected = '';
				if($id == $courseID)
					$selected = 'selected="selected"';
				else
					$selected = '';
				echo '<option value="'.$id.'" '.$selected.'>'.$course["name"].'</option>'.'<br>';
			}
			$id += 1;
		}
	}
	echo '</select>';
// end dropdown master_course
}
?>
	<div>
		<input type="radio" name="semester" id="winter" value="0" <?php echo ($semester == 0) ? 'checked' : ''?> />
		<label class="radio" for="winter">Зимен семестър</label><br>
		<input type="radio" name="semester" id="summer" value="1" <?php echo ($semester == 1) ? 'checked' : ''?> />
		<label class="radio" for="summer">Летен семестър</label>
	</div>
	<label class="not_radio">Кредити: </label>
	<input type="number" name="credits" value="<?php echo $credits?>" step="0.5"/>
	<br/><br/>
	<label class="not_radio">Категория: </label>
<?php
// start dropdown category
echo '<select name="category">';
$get_categories_sql = "SELECT * FROM category";
$categories = $conn->query($get_categories_sql);
$id = 1;
if($categories->rowCount() > 0)
{
	while($category = $categories->fetch(PDO::FETCH_ASSOC)) {
		$selected = '';
		if($id == $categoryID)
			$selected = 'selected="selected"';
		else
			$selected = '';
		echo '<option value="'.$id.'" '.$selected.'>'.$category["abbreviation"].'</option>'.'<br>';
		$id += 1;
	}
}
echo '</select><br><br>';
// end dropdown category

// start dropdown type
echo '<label class="not_radio">Тип: </label> <select name="type">';
$get_types_sql = "SELECT * FROM type";
$types = $conn->query($get_types_sql);
$id = 1;
if($types->rowCount() > 0)
{
	while($type = $types->fetch(PDO::FETCH_ASSOC)) {
		$selected = '';
		if($id == $typeID)
			$selected = 'selected="selected"';
		else
			$selected = '';
		echo '<option value="'.$id.'" '.$selected.'>'.$type["name"].'</option>'.'<br>';
		$id += 1;
	}
}
echo '</select><br><br>';
// end dropdown type
?>
<input type="submit" name="sub" id="btn" value="Редактирай"/>
</form>
<div id="elective_edit_messages">
	<p class="form_electives"><?php echo $name_check;?></p>
	<p class="form_electives"><?php echo $description_check;?></p>
	<p class="form_electives"><?php echo $qualification_check;?></p>
	<p class="form_electives"><?php echo $course_check;?></p>
	<p class="form_electives"><?php echo $semester_check;?></p>
	<p class="form_electives"><?php echo $credits_check;?></p>
	<p class="form_electives"><?php echo $category_check;?></p>
	<p class="form_electives"><?php echo $type_check;?></p>
	<p class="form_electives"><?php echo $entry_check;?></p>
</div>
</body>
</html>