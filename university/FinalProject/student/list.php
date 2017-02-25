<!DOCTYPE html>
<html>
<head>
	<title>Дисциплини</title>
	<link rel="stylesheet" type="text/css" href="../css/register_login.css"/>
</head>
<body>
<!-- start header-->
<div id="header_options">
	<a href="list.php" id="href_btn">Виж дисциплини</a>
</div>
<div id="header_logout">
	<a href="../login.php" id="href_btn">Вход</a>
	<a href="../register.php" id="href_btn">Регистрация</a>
</div>
<!-- end header-->
<?php
include("../configDB.php");
// 2. take all electives with this user id
$user_electiveID_sql = "SELECT DISTINCT electiveID FROM user_elective";
$user_electiveID_row = $conn->query($user_electiveID_sql);
$electiveID = "";
$userID = "";
$lecturers_arr = array();
if($user_electiveID_row->rowCount() > 0) {
	echo "<table id='lecturers_electives'>";
	echo "<tr><th>Дисциплина</th><th>Преподавател</th>";
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
			<td><a class='details' href='details.php?id=".$electiveID."'>".$elective["name"]."</a></td>
			<td>".$lecturers."</td></tr>";
	}
	echo "</table>";
}
?>
</body>
</html>