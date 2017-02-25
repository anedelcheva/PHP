<?php
include("../cookies.php");
$role = 2;
if(!isset($_COOKIE["email"]) || !isset($_COOKIE["roleID"]) || $_COOKIE["roleID"] != $role)
	header("Location: ../login.php");

// set cookie every time the user visits the page (the user is active)
if(isset($_COOKIE["email"]))
	setCookiesForUser($_COOKIE["email"], $role, $seconds);

include("../configDB.php");
include("../validate.php");

$email = "";
if(isset($_COOKIE["email"]))
	$email = $_COOKIE["email"];
$get_admin_sql = "SELECT * FROM user WHERE email='$email'";
$user = $conn->query($get_admin_sql);
$userID = "";
if($user->rowCount() > 0) {
	$admin = $user->fetch(PDO::FETCH_ASSOC);
	$userID = $admin["id"];
}

$name = $description = $qualification = $semester = $course = $credits = $category = $type = "";
$name_check = $description_check = $qualification_check = $semester_check = $course_check = $credits_check = $category_check = $type_check = $entry_check = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST["name"]))
		$name = modify_input($_POST["name"]);
	if(isset($_POST["description"]))
		$description = modify_input($_POST["description"]);
	
	if(isset($_POST['credits']))
		$credits = doubleval(modify_input($_POST['credits']));
	
	if(isset($_POST['category']))
		$category = intval(modify_input($_POST['category']));
	
	if(isset($_POST['type']))
		$type = intval(modify_input($_POST['type']));
	
	if(isset($_POST['qualification']))
		$qualification = intval(modify_input($_POST['qualification']));
	
	if($qualification == 0) {
		if(isset($_POST['bachelor_courses']))
			$course = intval(modify_input($_POST['bachelor_courses']));
	} else {
		if(isset($_POST['master_courses']))
			$course = intval(modify_input($_POST['master_courses']));
	}
	
	if(isset($_POST['semester']))
		$semester = intval(modify_input($_POST['semester']));
	
	// check if an elective with this name, category and type already exists in DB
	$get_elective_sql = "SELECT * FROM elective WHERE name='$name' AND typeID='$type' AND categoryID='$category'";
	$get_elective = $conn->query($get_elective_sql);
	if($get_elective->rowCount() > 0)
		$entry_check = "Дисциплина с това име, тип и категория вече съществува.";
	
	$name_check = checkElectiveName($name);
	$description_check = checkElectiveDescription($description);
	$credits_check = checkElectiveCredits($credits);
	$semester_check = ifEmpty($semester, "Изберете семестър.");
	$qualification_check = ifEmpty($qualification, "Изберете ОКС.");
	$course_check = ifEmpty($course, "Курсът не е наличен.");
	$category_check = ifEmpty($category, "Категорията не е налична.");
	$type_check = ifEmpty($type, "Типът не е наличен.");
	
	if(empty($name_check) && empty($description_check) && empty($credits_check) && 
	   empty($semester_check) && empty($qualification_check) && empty($course_check) && 
	   empty($category_check) && empty($type_check) && empty($entry_check)) {
		// 1. insert elective in table elective
		$insert_elective_sql = "INSERT INTO elective(name, description, typeID, categoryID, credits, semester, qualification, courseID, createdBy) 
								VALUES (:name, :description, :type, :category, :credits, :semester, :qualification, :course, :createdBy)";
		$insert_elective = $conn->prepare($insert_elective_sql);
		$insert_elective->bindParam(":name", $name);
		$insert_elective->bindParam(":description", $description);
		$insert_elective->bindParam(":category", $category);
		$insert_elective->bindParam(":credits", $credits);
		$insert_elective->bindParam(":semester", $semester);
		$insert_elective->bindParam(":qualification",  $qualification);
		$insert_elective->bindParam(":course", $course);
		$insert_elective->bindParam(":type", $type);
		$insert_elective->bindParam(":createdBy", $userID);
		$insert_elective->execute();
		
		// 2. select this elective from table elective and take its id
		$get_elective_sql = "SELECT id FROM elective WHERE name='$name' AND categoryID='$category' AND typeID='$type'";
		$elective_row = $conn->query($get_elective_sql);
		$electiveID = "";
		if($elective_row->rowCount() > 0) {
			$elective = $elective_row->fetch(PDO::FETCH_ASSOC);
			$electiveID = $elective["id"];
		}
		
		// 3. insert row in table user_elective with userID=$userID and electiveID=$electiveID
		$insert_user_elective_sql = "INSERT INTO user_elective(userID, electiveID) 
									 VALUES (:userID, :electiveID)";
		$insert_user_elective = $conn->prepare($insert_user_elective_sql);
		$insert_user_elective->bindParam(":userID", $userID);
		$insert_user_elective->bindParam(":electiveID", $electiveID);
		$insert_user_elective->execute();
		header("Location: list.php");
	}
}
?>

<html>
<head>
	<title>Добави дисциплина</title>
	<!--<script src="../js/validate_elective.js"></script>-->
	<link rel="stylesheet" type="text/css" href="../css/register_login.css"/>
</head>

<body>
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
echo $admin["firstname"]." ".$admin["lastname"]." ";
?>
<a href="../logout.php" id="href_btn">Изход</a>
</div>
<!-- end header-->
<div id="elective_wrapper">
<p class="green_p">ДОБАВИ ДИСЦИПЛИНА</p>
<form action="add3.php" method="POST" class="general" id="elective">
	<input type="text" id="name_add" placeholder="Дисциплина" name="name"/>
	<textarea id="description_add" placeholder="Описание" name="description"></textarea>
	<div>
		<input type="radio" name="qualification" id="bachelor" value="0" onclick="selectProgram('bachelor');" checked />
		<label class="radio" for="bachelor">Бакалавър</label><br>
		<input type="radio" name="qualification" id="master" value="1" onclick="selectProgram('master');" />
		<label class="radio" for="master">Магистър</label>
	</div>
	<label class="not_radio">Курс: </label>
<?php
// start dropdown bachelor_course
echo '<select name="bachelor_courses" id="bachelor_courses">';
$get_courses_sql = "SELECT * FROM course";
$courses = $conn->query($get_courses_sql);
$id = 1;
if($courses->rowCount() > 0)
{
	while($course = $courses->fetch(PDO::FETCH_ASSOC)) {
		echo '<option value="'.$id.'">'.$course["name"].'</option>'.'<br>';
		$id += 1;
		//echo $id;
	}
}
echo '</select>';
// end dropdown bachelor_course

// start dropdown master_course
echo '<select name="master_courses" id="master_courses" style="display: none">';
$get_courses_sql = "SELECT * FROM course";
$courses = $conn->query($get_courses_sql);
$id = 1;
if($courses->rowCount() > 0)
{
	while($course = $courses->fetch(PDO::FETCH_ASSOC)) {
		if($id == 1 || $id == 2 || $id == 5) {
			echo '<option value="'.$id.'">'.$course["name"].'</option>'.'<br>';
		}
		$id += 1;
	}
}
echo '</select>';
// end dropdown master_course
	
	echo '
	<div>
		<input type="radio" name="semester" id="winter" value="0" checked/>
		<label class="radio" for="winter">Зимен семестър</label><br>
		<input type="radio" name="semester" id="summer" value="1"/>
		<label class="radio" for="summer">Летен семестър</label>
	</div>
	<label class="not_radio">Кредити: </label>
	<input type="number" name="credits" step="0.5"/>
	<br/><br/>';

echo '<label class="not_radio">Категория: </label>';
// start dropdown category
echo '<select name="category">';
$get_categories_sql = "SELECT * FROM category";
$categories = $conn->query($get_categories_sql);
$id = 1;
if($categories->rowCount() > 0)
{
	while($category = $categories->fetch(PDO::FETCH_ASSOC)) {
		echo '<option value="'.$id.'">'.$category["abbreviation"].'</option>'.'<br>';
		$id += 1;
	}
}
echo '</select><br><br>';
// end dropdown category

// start dropdown type
echo '<label class="not_radio">Тип: </label><select name="type">';
$get_types_sql = "SELECT * FROM type";
$types = $conn->query($get_types_sql);
$id = 1;
if($types->rowCount() > 0)
{
	while($type = $types->fetch(PDO::FETCH_ASSOC)) {
		echo '<option value="'.$id.'">'.$type["name"].'</option>'.'<br>';
		$id += 1;
	}
}
echo '</select><br><br>';
// end dropdown type
?>
<input type="submit" name="sub" id="btn" value="Добави"/>
</form>
</div>

<div id="elective_messages">
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
<script type="text/javascript">
function selectProgram(program) {
	if(program == "master") {
		document.getElementById("master_courses").style["display"] = "inline-block";
		document.getElementById("bachelor_courses").style["display"] = "none";
	}
	
	if(program == "bachelor") {
		document.getElementById("master_courses").style["display"] = "none";
		document.getElementById("bachelor_courses").style["display"] = "inline-block";
	}
}
</script>
</body>
</html>