<?php
include("configDB.php");
include("validate.php");
	
$email = $firstname = $lastname = $password = $email_check = $pass_check = $firstname_check = $lastname_check = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$email = modify_input($_POST["email"]);
	$firstname = modify_input($_POST["firstname"]);
	$lastname = modify_input($_POST["lastname"]);
	$password = modify_input($_POST["password"]);
	$get_user_sql = "SELECT * FROM user WHERE email = '$email'";
	$user = $conn->query($get_user_sql);
	$email_check = checkEmail($email, $user);
	$firstname_check = checkFirstname($firstname);
	$lastname_check = checkLastname($lastname);
	$pass_check = checkPassword($password);
		
	// if all check strings are empty, everything is valid and user is registered
	if(empty($email_check) && empty($firstname_check) && 
	   empty($lastname_check) && empty($pass_check)) {
		$password = sha1($password);
		$roleID = 3; // for student
		$insert_student_sql = "INSERT INTO user(email, firstname, lastname, password, roleID) 
							   VALUES (:email, :firstname, :lastname, :password, :roleID)";
		$insert_student = $conn->prepare($insert_student_sql);
		$insert_student->bindParam(":email", $email);
		$insert_student->bindParam(":firstname", $firstname);
		$insert_student->bindParam(":lastname", $lastname);
		$insert_student->bindParam(":password", $password);
		$insert_student->bindParam(":roleID", $roleID);
		$insert_student->execute();
		header("Location: login.php");
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Регистрация</title>
	<link rel="stylesheet" type="text/css" href="css/register_login.css">
	<script src="js/validate_forms.js"></script>
</head>
<body>
	<div id="header_options">
		<a href="student/list.php" id="href_btn">Виж дисциплини</a>
	</div>
	<div id="header_logout">
		<a href="login.php" id="href_btn">Вход</a>
		<a href="register.php" id="href_btn">Регистрация</a>
	</div>
	<div class="register_lecturer">
		<p class="green_p">РЕГИСТРАЦИЯ</p>
		<form action="register.php" class="general" method="POST">
			<ul class="register">
				<li><input type="text" name="email" id="email" placeholder="E-mail" onkeyup="emailValidator()" value="<?php echo $email;?>"/></li>
				<li><input type="text" name="firstname" placeholder="Име" value="<?php echo $firstname;?>"/></li>
				<li><input type="text" name="lastname" placeholder="Фамилия" value="<?php echo $lastname;?>"/></li>
				<li><input type="password" name="password" id="password" placeholder="Парола" onfocus="passwordOnFocus(this)" onblur="passwordBlur();"/></li>
				<div id="tooltip"></div>
				<li><input type="submit" id="btn" value="Регистрация"/></li>
			</ul>
		</form>
	</div>
	<div id="registration_messages">
		<p class="form_validation"><?php echo $email_check;?></p>
		<p class="form_validation"><?php echo $firstname_check;?></p>
		<p class="form_validation"><?php echo $lastname_check;?></p>
		<p class="form_validation"><?php echo $pass_check;?></p>
	</div>
</body>
</html>