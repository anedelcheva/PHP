<?php
include("../cookies.php");
$role = 1; // for admin
if(!isset($_COOKIE["email"]) || !isset($_COOKIE["roleID"]) || $_COOKIE["roleID"] != $role)
	header("Location: ../login.php");

// set cookie every time the user visits the page (the user is active)
if(isset($_COOKIE["email"]))
	setCookiesForUser($_COOKIE["email"], $role, $seconds);

// ADD LECTURER
include("../configDB.php");
include("../validate.php");

// start header
echo '<div id="header_options">';
echo '<a href="add.php" id="href_btn">Добави преподавател</a>';
echo '<a href="list.php" id="href_btn">Виж преподаватели</a>';
echo '<a href="../change_password.php" id="href_btn">Промени паролата</a>';
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
echo '<a href="../logout.php" id="href_btn">Изход</a>';
echo '</div>';
// end header

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
		$roleID = 2; // for lecturer
		$insert_lecturer_sql = "INSERT INTO user(email, firstname, lastname, password, roleID) 
							    VALUES (:email, :firstname, :lastname, :password, :roleID)";
		$insert_lecturer = $conn->prepare($insert_lecturer_sql);
		$insert_lecturer->bindParam(":email", $email);
		$insert_lecturer->bindParam(":firstname", $firstname);
		$insert_lecturer->bindParam(":lastname", $lastname);
		$insert_lecturer->bindParam(":password", $password);
		$insert_lecturer->bindParam(":roleID", $roleID);
		$insert_lecturer->execute();
		header("Location: list.php");
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Добави преподавател</title>
	<link rel="stylesheet" type="text/css" href="../css/register_login.css"/>
	<script src="../js/validate_forms.js"></script>
</head>
<body>
	<div class="register_lecturer">
		<p class="green_p">ДОБАВИ ПРЕПОДАВАТЕЛ</p>
		<form action="add.php" class="general" method="POST">
			<ul class="add_user">
				<li><input type="email" name="email" id="email" placeholder="E-mail" onkeyup="emailValidator()" value="<?php echo $email;?>"/></li>
				<li><input type="text" name="firstname" placeholder="Име" value="<?php echo $firstname;?>"/></li>
				<li><input type="text" name="lastname" placeholder="Фамилия" value="<?php echo $lastname;?>"/></li>
				<li><input type="password" name="password" id="password" placeholder="Парола" onfocus="passwordOnFocus(this)" onblur="passwordBlur();"/></li>
				<div id="tooltip"></div>
				<li><input type="submit" id="btn" value="Добави преподавател"/></li>
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