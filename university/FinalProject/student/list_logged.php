<?php
include("../cookies.php");
$role = 3; // for student
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
	<a href="list_logged.php" id="href_btn">Виж дисциплини</a>
	<a href="../change_student_password.php" id="href_btn">Промени паролата</a>
</div>
<div id="header_logout">
<?php
include("../configDB.php");
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
?>
<a href="../logout.php" id="href_btn">Изход</a>
</div>
<!-- end header-->
<?php

// 2. take all electives with this user id
$user_electiveID_sql = "SELECT DISTINCT electiveID FROM user_elective";
$user_electiveID_row = $conn->query($user_electiveID_sql);
$electiveID = "";
$userID = "";
$lecturers_arr = array();
if($user_electiveID_row->rowCount() > 0) {
	echo "<table id='lecturers_electives'>";
	echo "<tr><th>Дисциплина</th><th>Преподавател</th>"; //<th>Тип</th><th>Категория</th><th>Курс</th><th>Кредити</th><th>Редактиране</th><th>Изтриване</th></tr>";
	while($user_electiveID = $user_electiveID_row->fetch(PDO::FETCH_ASSOC)) {
		$lecturers_arr = array();
		$electiveID = $user_electiveID["electiveID"];
		$get_electives_sql = "SELECT * FROM elective WHERE id=$electiveID";
		$electives = $conn->query($get_electives_sql);
		$elective = "";
		if($electives->rowCount() > 0) {
			$elective = $electives->fetch(PDO::FETCH_ASSOC);
		}
		$get_user_elective_sql = "SELECT DISTINCT userID FROM user_elective WHERE electiveID=$electiveID";
		$user_elective_row = $conn->query($get_user_elective_sql);
		if($user_elective_row->rowCount() > 0) {
			while($user_elective = $user_elective_row->fetch(PDO::FETCH_ASSOC)) {
				$userID = $user_elective["userID"];
				$get_user_sql = "SELECT * FROM user WHERE id=:userID";
				$user_row = $conn->prepare($get_user_sql);
				$user_row->bindParam(":userID", $userID);
				$user_row->execute();
				if($user_row->rowCount() > 0) {
					
					while($user = $user_row->fetch(PDO::FETCH_ASSOC)) {
						array_push($lecturers_arr, $user["firstname"]." ".$user["lastname"]);
					}
				}	
			}
		}
		$lecturers = implode(", ", $lecturers_arr);
		echo 
			"<tr>
			<td><a class='details' href='details_comment.php?id=".$electiveID."'>".$elective["name"]."</a></td>
			<td>".$lecturers."</td></tr>";
	}
	echo "</table>";
}
?>
</body>
</html>