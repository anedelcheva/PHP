<?php
include("../cookies.php");
if(!isset($_COOKIE["email"]) || !isset($_COOKIE["roleID"]) || $_COOKIE["roleID"] != 1)
	header("Location: ../login.php");

// set cookie every time the user visits the page (the user is active)
setCookiesForUser($_COOKIE["email"], 1, $seconds);

// 1. Get the elective with the id from query string
$id = (isset($_GET["id"])) ? $_GET["id"] : '';

// 2. Connect to database
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

// 3. Get elective from the database with the id in the query string
$user_with_id_query = "SELECT * FROM user WHERE id=:id";
$user_with_id = $conn->prepare($user_with_id_query);
$user_with_id->bindParam(":id", $id);
$user_with_id->execute();
$email = $firstname = $lastname = $id_db = $email_check = $firstname_check = $lastname_check = "";
if($user_with_id->rowCount() > 0)
{
	$user = $user_with_id->fetch(PDO::FETCH_ASSOC);
	$email = $user["email"];
	$firstname = $user["firstname"];
	$lastname = $user["lastname"];
}
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$id_db = modify_input($_POST["id"]);
	$email = modify_input($_POST["email"]);
	$firstname = modify_input($_POST["firstname"]);
	$lastname = modify_input($_POST["lastname"]);
	// validation
	$get_user_sql = "SELECT * FROM user WHERE email = '$email'";
	$user = $conn->query($get_user_sql);
	if($user->rowCount() > 0)
		$email_check = "Този имейл вече съществува в системата.";
	else if(empty($email))
		$email_check = "Въведете имейл.";
	else if(!preg_match('/^([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)@([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)[\\.]([a-zA-Z]{2,9})$/', $email))
		$email_check = "Имейлът не е валиден.";
	$firstname_check = checkFirstname($firstname);
	$lastname_check = checkLastname($lastname);
	
	if(empty($email_check) && empty($firstname_check) && empty($lastname_check)) {
		$update_user_query = "UPDATE user SET email = :email, firstname = :firstname, lastname = :lastname WHERE id = :id";
		$update_user = $conn->prepare($update_user_query);
		$update_user->bindParam(":email", $email);
		$update_user->bindParam(":firstname", $firstname);
		$update_user->bindParam(":lastname", $lastname);
		$update_user->bindParam(":id", $id_db);
		$update_user->execute();
		header("Location: list.php");
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Редактирай преподавател</title>
<link rel="stylesheet" type="text/css" href="../css/register_login.css">
<script src="../js/validate_forms.js"></script>
</head>
<body>
<div class="register_lecturer">
	<p class="green_p">РЕДАКТИРАЙ ПРЕПОДАВАТЕЛ</p>
	<form action="edit.php" class="general" method="POST">
		<input type="hidden" name="id" value="<?php echo $id; ?>"/>
		<ul id="edit_lecturer">
			<li><input type="text" name="email" id="email" placeholder="E-mail" onkeyup="emailValidator()" value="<?php echo $email;?>"/></li>
			<li><input type="text" name="firstname" placeholder="Име" value="<?php echo $firstname;?>"/></li>
			<li><input type="text" name="lastname" placeholder="Фамилия" value="<?php echo $lastname;?>"/></li>
			<li><input type="submit" id="btn" value="Редактирай"/></li>
		</ul>
	</form>
</div>
<div id="registration_messages">
	<p class="form_validation"><?php echo $email_check;?></p>
	<p class="form_validation"><?php echo $firstname_check;?></p>
	<p class="form_validation"><?php echo $lastname_check;?></p>
</div>
</body>
</html>