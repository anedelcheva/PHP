<?php
include("configDB.php");
include("validate.php");
include("cookies.php");
$role = 2;
if(!isset($_COOKIE["email"]) || !isset($_COOKIE["roleID"]) || $_COOKIE["roleID"] != $role)
	header("Location: login.php");

// set cookie every time the user visits the page (the user is active)
if(isset($_COOKIE["email"]))
	setCookiesForUser($_COOKIE["email"], $role, $seconds);

// start header
echo '<div id="header_options">';
echo '<a href="elective/add3.php" id="href_btn">Добави дисциплинa</a>';
echo '<a href="elective/list.php" id="href_btn">Виж свои дисциплини</a>';
echo '<a href="elective/list_all.php" id="href_btn">Виж всички дисциплини</a>';
echo '<a href="change_lecturer_password.php" id="href_btn">Промени паролата</a>';
echo '</div>';
echo '<div id="header_logout">';
echo "Добре дошли, ";
$email = "";
if(isset($_COOKIE["email"]))
	$email = $_COOKIE["email"];
$get_admin_sql = "SELECT * FROM user WHERE email='$email'";
$user = $conn->query($get_admin_sql);
if($user->rowCount() > 0) {
	$admin = $user->fetch(PDO::FETCH_ASSOC);
	echo $admin["firstname"]." ".$admin["lastname"]." ";
}
echo '<a href="logout.php" id="href_btn">Изход</a>';
echo '</div>';
// end header

$oldPassword = $newPassword = $old_pass_check = $email = $admin = $new_pass_check = "";
$email = $_COOKIE["email"];
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$oldPassword = modify_input($_POST["oldPassword"]);
	$newPassword = modify_input($_POST["newPassword"]);
	$get_user_sql = "SELECT * FROM user WHERE email = '$email'";
	$user = $conn->query($get_user_sql);
	if($user->rowCount() > 0) {
		$admin = $user->fetch(PDO::FETCH_ASSOC);
		if(empty($oldPassword)) {
			$old_pass_check = "Въведете стара парола.";
		}
		else if(strcmp(sha1($oldPassword), $admin["password"]) !== 0) {
			$old_pass_check = "Паролата не е правилна.";
		}
	}
	
	$new_pass_check = checkPassword($newPassword);
		
	// if all check strings are empty, everything is valid and user is registered
	if(empty($new_pass_check) && empty($old_pass_check)) {
		$newPassword = sha1($newPassword);
		$update_user_query = "UPDATE user SET password = :newPassword WHERE email = :email";
		$update_user = $conn->prepare($update_user_query);
		$update_user->bindParam(":email", $email);
		$update_user->bindParam(":newPassword", $newPassword);
		$update_user->execute();
		header("Location: elective/list.php");
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Смени парола</title>
	<link rel="stylesheet" type="text/css" href="css/register_login.css"/>
</head>
<body>
	<div class="login_pass">
		<p class="green_p">СМЕНИ ПАРОЛАТА</p>
		<form action="change_lecturer_password.php" class="general" method="POST">
			<ul class="login_pass">
				<li><input type="password" name="oldPassword" placeholder="Стара парола"/></li>
				<li><input type="password" name="newPassword" placeholder="Нова парола"/></li>
				<li><input type="submit" id="btn" value="Смени паролата"/></li>
			</ul>
		</form>
	</div>
	<div id="login_messages">
		<p class="form_validation"><?php echo $old_pass_check;?></p>
		<p class="form_validation"><?php echo $new_pass_check;?></p>
	</div>
</body>
</html>