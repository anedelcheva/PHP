<?php
include("configDB.php");
include("modify_input.php");
include("cookies.php");
			
$email = $password = $email_check = $pass_check = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = modify_input($_POST["email"]);
	$password = modify_input($_POST["password"]);
	
	// email check
	if(empty($email))
		$email_check = "Въведете имейл.";
	
	// pass check
	if(empty($password))
		$pass_check = "Въведете парола.";
	
	if(empty($email_check) && empty($pass_check)) {
		$get_user_sql = "SELECT * FROM user WHERE email='$email'";
		$user = $conn->query($get_user_sql);
		if($user->rowCount() > 0)
		{
			$person = $user->fetch(PDO::FETCH_ASSOC);
			$password = sha1($password);
			if($person["password"] == $password) 
			{
				$seconds = 60;
				
				if($person["roleID"] == 1) { // admin
					setCookiesAndLocationForUser($email, 1, $seconds, "lecturer/list.php");
				}
				else if($person["roleID"] == 2) { // lecturer
					setCookiesAndLocationForUser($email, 2, $seconds, "elective/list.php");
				}
				else if($person["roleID"] == 3) { // student
					setCookiesAndLocationForUser($email, 3, $seconds, "student/list_logged.php");
				}
			}
			else
				$pass_check = "Грешна парола. Опитайте отново.";
		}
		else
			$email_check = "Този имейл не съществува в системата.";
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Вход</title>
		<link rel="stylesheet" type="text/css" href="css/register_login.css">
	</head>
	<body>
		<div id="header_options">
			<a href="student/list.php" id="href_btn">Виж дисциплини</a>
		</div>
		<div id="header_logout">
			<a href="login.php" id="href_btn">Вход</a>
			<a href="register.php" id="href_btn">Регистрация</a>
		</div>
		<div class="login_pass">
			<p class="green_p">ВХОД</p>
			<form action="login.php" class="general" method="POST">
				<ul class="login">
					<li><input type="email" name="email" placeholder="E-mail" value="<?php echo $email;?>"/></li>
					<li><input type="password" name="password" placeholder="Парола" /></li>
					<li><input type="submit" id="btn" value="Вход"/></li>
				</ul>
			</form>
		</div>
		<div id="login_messages">
			<p class="form_validation"><?php echo $email_check; ?></p>
			<p class="form_validation"><?php echo $pass_check; ?></p>
		</div>
	</body>
</html>