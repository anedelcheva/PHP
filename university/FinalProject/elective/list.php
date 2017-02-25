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
	<title>Дисциплини</title>
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
include("../configDB.php");
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
<?php

// 2. take all electives with this user id
$user_elective_sql = "SELECT electiveID FROM user_elective WHERE userID='$userID'";
$user_elective_row = $conn->query($user_elective_sql);
$electiveID = "";
$createdBy = "";
if($user_elective_row->rowCount() > 0) {
	echo "<table id='electives'>";
	echo "<tr><th>Име</th><th>Тип</th><th>Категория</th><th>Курс</th><th>Кредити</th><th>Редактиране</th><th>Изтриване</th><th>Добави преподавател</th></tr>";
	while($user_elective = $user_elective_row->fetch(PDO::FETCH_ASSOC)) {
		$electiveID = $user_elective["electiveID"];
		$get_electives_sql = "SELECT * FROM elective WHERE id=$electiveID";
		$electives = $conn->query($get_electives_sql);
		if($electives->rowCount() > 0) {

			$elective = $electives->fetch(PDO::FETCH_ASSOC);
			$createdBy = $elective["createdBy"];
				
			$typeID = $elective["typeID"];
			$get_type_sql = "SELECT * FROM type WHERE id=$typeID";
			$type_row = $conn->query($get_type_sql);
			$type = "";
			if($type_row->rowCount() > 0) {
				$type = $type_row->fetch(PDO::FETCH_ASSOC);
			}
			
			$categoryID = $elective["categoryID"];
			$get_category_sql = "SELECT * FROM category WHERE id=$categoryID";
			$category_row = $conn->query($get_category_sql);
			$category = "";
			if($category_row->rowCount() > 0) {
				$category = $category_row->fetch(PDO::FETCH_ASSOC);
			}
			
			$courseID = $elective["courseID"];
			$get_course_sql = "SELECT * FROM course WHERE id=$courseID";
			$course_row = $conn->query($get_course_sql);
			$course = "";
			if($course_row->rowCount() > 0) {
				$course = $course_row->fetch(PDO::FETCH_ASSOC);
			}
			
			echo 
			"<tr>
			<td>".$elective["name"]."</td>
			<td>".$type["name"]."</td>
			<td>".$category["abbreviation"]."</td>
			<td>".$course["name"]."</td>
			<td>".$elective["credits"]."</td>
			<td><a class='edit' href='edit.php?id=".$elective["id"]."'>Редактирай</a></td>
			<td><a class='delete' href='delete.php?id=".$elective["id"]."'>Изтрий</a></td>";
			if($createdBy == $userID)
				echo "<td><a class='add_lecturer' href='add_lecturer.php?id=".$elective["id"]."'>Добави преподавател</a></td>";
			echo "</tr>";
		}
	}
	echo "</table>";
}
?>
</body>
</html>