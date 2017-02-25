<?php
include("../cookies.php");
$role = 2; // for teacher
if(!isset($_COOKIE["email"]) || !isset($_COOKIE["roleID"]) || $_COOKIE["roleID"] != $role)
	header("Location: ../login.php");

// set cookie every time the user visits the page (the user is active)
if(isset($_COOKIE["email"]))
	setCookiesForUser($_COOKIE["email"], $role, $seconds);

$id = (isset($_GET["id"])) ? $_GET["id"] : '';
include("../modify_input.php");
include("../configDB.php");
$lecturerID = $lecturer_added_check = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$id_db = modify_input($_POST["id"]);
	if(isset($_POST["lecturer"]))
		$lecturerID = intval(modify_input($_POST["lecturer"]));
		$insert_elective_sql = "INSERT INTO user_elective(userID, electiveID) VALUES (:userID, :electiveID)";
		$insert_elective = $conn->prepare($insert_elective_sql);
		$insert_elective->bindParam(":userID", $lecturerID);
		$insert_elective->bindParam(":electiveID", $id_db);
		$insert_elective->execute();
		header("Location: list.php");
}
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

?>
<div class="login_pass">
<p class="green_p">ДОБАВИ ПРЕПОДАВАТЕЛ</p>
<form action="add_lecturer.php" class="general" method="POST">
<input type="hidden" name="id" value="<?php echo $id; ?>"/>
<ul class="add_lecturer">
<?php
// 1. take elective with
$get_elective_sql = "SELECT * FROM elective WHERE id='$id'";
$get_elective_row = $conn->query($get_elective_sql);
$createdBy = "";
if($get_elective_row->rowCount() > 0) {
	$elective = $get_elective_row->fetch(PDO::FETCH_ASSOC);
	$createdBy = $elective["createdBy"];
}

$roleID = 2;
if($userID == $createdBy) {
	$get_users_sql = "SELECT * FROM user WHERE roleID=$roleID";
	$users = $conn->query($get_users_sql);
	
	echo '<li><select name="lecturer" id="lecturer">';
	if($users->rowCount() > 0) {
		while($user = $users->fetch(PDO::FETCH_ASSOC)) {
			if($user["id"] != $userID)
				echo '<option value="'.$user["id"].'">'.$user["email"].'</option>'.'<br>';
		}
	}
	echo '</select></li>';
}
?>
<li><input type="submit" name="sub" id="btn" value="Добави"/></li>
</ul>
</div>
</form>
</body>
</html>