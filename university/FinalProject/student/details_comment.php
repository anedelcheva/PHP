<?php
include("../cookies.php");
$role = 3; // for student
if(!isset($_COOKIE["email"]) || !isset($_COOKIE["roleID"]) || $_COOKIE["roleID"] != $role)
	header("Location: ../login.php");

// set cookie every time the user visits the page (the user is active)
if(isset($_COOKIE["email"]))
	setCookiesForUser($_COOKIE["email"], $role, $seconds);

// 1. Get the elective with the id from query string
$id = (isset($_GET["id"])) ? $_GET["id"] : '';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Повече информация</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/register_login.css"/>
</head>
<body>
<!-- start header-->
<div id="header_options">
	<a href="list_logged.php" id="href_btn">Виж дисциплини</a>
	<a href="../change_student_password.php" id="href_btn">Промени паролата</a>
</div>
<div id="header_logout">
<?php
// 2. Connect to database
include("../configDB.php");
include("../modify_input.php");

$email = "";
if(isset($_COOKIE["email"]))
	$email = $_COOKIE["email"];
$get_admin_sql = "SELECT * FROM user WHERE email='$email'";
$user = $conn->query($get_admin_sql);
$userID = "";
if($user->rowCount() > 0) {
	$admin = $user->fetch(PDO::FETCH_ASSOC);
	$userID = $admin["id"];
	echo "Добре дошли, ".$admin["firstname"]." ".$admin["lastname"]." ";
}

$content = $content_check = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$id_db = intval(modify_input($_POST["id"]));
	if(isset($_POST["content"]))
		$content = modify_input($_POST["content"]);
	if(empty($content)) {
		$content_check = "Не можете да публикувате празен коментар";
	}
	
	if(empty($content_check)) {
		$insert_comment_sql = "INSERT INTO comment(userID, electiveID, content) VALUES (:userID, :electiveID, :content)";
		$insert_comment = $conn->prepare($insert_comment_sql);
		$insert_comment->bindParam(":userID", $userID);
		$insert_comment->bindParam(":electiveID", $id_db);
		$insert_comment->bindParam(":content", $content);
		$insert_comment->execute();
	}
	header("Location: details_comment?id=".$id_db.".php");
}
?>
<a href="../logout.php" id="href_btn">Изход</a>
</div>
<!-- end header-->
<?php
// 3. Get elective from the database with the id in the query string
$elective_with_id_query = "SELECT * FROM elective WHERE id=:id";
$elective_with_id = $conn->prepare($elective_with_id_query);
$elective_with_id->bindParam(":id", $id);
$elective_with_id->execute();
$name = $typeID = $categoryID = $courseID = $lecturer = $qualificationID = "";
$type = $category = $course = $lecturer = $qualification = $description = "";
if($elective_with_id->rowCount() > 0)
{
	$elective = $elective_with_id->fetch(PDO::FETCH_ASSOC);
	$name = $elective["name"];
	$description = $elective["description"];
	$qualificationID = $elective["qualification"];
	
	$typeID = $elective["typeID"];
	$get_type_sql = "SELECT * FROM type WHERE id=$typeID";
	$type_row = $conn->query($get_type_sql);
	if($type_row->rowCount() > 0) {
		$type = $type_row->fetch(PDO::FETCH_ASSOC);
	}
	
	$categoryID = $elective["categoryID"];
	$get_category_sql = "SELECT * FROM category WHERE id=$categoryID";
	$category_row = $conn->query($get_category_sql);
	if($category_row->rowCount() > 0) {
		$category = $category_row->fetch(PDO::FETCH_ASSOC);
	}
	
	$courseID = $elective["courseID"];
	$get_course_sql = "SELECT * FROM course WHERE id=$courseID";
	$course_row = $conn->query($get_course_sql);
	if($course_row->rowCount() > 0) {
		$course = $course_row->fetch(PDO::FETCH_ASSOC);
	}
	
	if($qualificationID == 0)
		$qualification = "бакалавър";
	else
		$qualification = "магистър";
	
}
$elective_with_id_query = "SELECT * FROM user_elective WHERE electiveID=:id";
$elective_with_id = $conn->prepare($elective_with_id_query);
$elective_with_id->bindParam(":id", $id);
$elective_with_id->execute();
$lecturers = array();
$userID = "";
$lecturer = "";
if($elective_with_id->rowCount() > 0) {
	while($elective = $elective_with_id->fetch(PDO::FETCH_ASSOC)) {
		$userID = $elective["userID"];
		$user_with_id_query = "SELECT * FROM user WHERE id=:id";
		$user_with_id = $conn->prepare($user_with_id_query);
		$user_with_id->bindParam(":id", $userID);
		$user_with_id->execute();
		if($user_with_id->rowCount() > 0) {
			while($user = $user_with_id->fetch(PDO::FETCH_ASSOC)) {
				$lecturer = $user["firstname"]." ".$user["lastname"];
				array_push($lecturers, $lecturer);
			}
		}
	}
}
$lecturers = implode(", ", $lecturers);
?>
<div id="wrapper">
	<div id="description">
		<h1><?php if(!empty($name)) echo $name; ?></h1>
		<h2><?php if(!empty($lecturers)) echo $lecturers; ?></h2>
		<p><span class="italic">Описание:</span> <?php if(!empty($description)) echo $description; ?></p>
		<p><span class="italic">Тип:</span> <?php if(!empty($type["name"])) echo $type["name"]; ?></p>
		<p><span class="italic">Категория:</span> <?php if(!empty($category["abbreviation"])) echo $category["abbreviation"]; ?></p>
		<p><span class="italic">Курс:</span> <?php if(!empty($course["name"])) echo $course["name"]; ?></p>
		<p><span class="italic">Програма:</span> <?php if(!empty($qualification)) echo $qualification; ?></p>
		<form action="details_comment.php" method="POST">
			<input type="hidden" name="id" value="<?php echo $id; ?>"/>
			<textarea name="content" id="content" placeholder="Добавяне на коментар"></textarea>
			<br>
			<input type="submit" id="publish_btn" value="Публикуване"/>
		</form>

	</div>
<?php
$comment_sql = "SELECT * FROM comment WHERE electiveID='$id'";
$comment_row = $conn->query($comment_sql);
$userID = "";
$createdBy = "";
if($comment_row->rowCount() > 0) {
	echo '<div id="comments">
			<h2>Коментари</h2>';
	while($comment = $comment_row->fetch(PDO::FETCH_ASSOC)) {
		$userID = $comment["userID"];
		$get_user_sql = "SELECT * FROM user WHERE id=$userID";
		$user_row = $conn->query($get_user_sql);
		if($user_row->rowCount() > 0) {
			$user = $user_row->fetch(PDO::FETCH_ASSOC);
			echo '<p><span class="vili">'.$user["firstname"].' '.$user["lastname"].'</span> <span class="date">'.$comment["createDate"].'</span></p>';
			echo '<p class="comment">"'.$comment["content"].'"</p>';
		}
	}
	echo '</div>';
}
?>
</div>
</body>
</html>