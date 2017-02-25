<?php
include("../cookies.php");
if(!isset($_COOKIE["email"]) || !isset($_COOKIE["roleID"]) || $_COOKIE["roleID"] != 1)
	header("Location: ../login.php");
// set cookie every time the user visits the page (the user is active)
if(isset($_COOKIE["email"]))
	setCookiesForUser($_COOKIE["email"], 1, $seconds);

$email = "";
?>
<!DOCTYPE html>
<html>
<head>
<title>Списък с лектори</title>
<link rel="stylesheet" type="text/css" href="../css/register_login.css">
</head>
<body>
<?php
include("../configDB.php");

// start header
echo '<div id="header_options">';
echo '<a href="add.php" id="href_btn">Добави преподавател</a>';
echo '<a href="list.php" id="href_btn">Виж преподаватели</a>';
echo '<a href="../change_password.php" id="href_btn">Промени паролата</a>';
echo '</div>';
echo '<div id="header_logout">';
echo "Добре дошли, ";
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

$roleID = 2;
$get_lecturers_sql = "SELECT * FROM user WHERE roleID=$roleID";
$lecturers = $conn->query($get_lecturers_sql);
if($lecturers->rowCount() > 0)
{
	echo "<table id='lecturers'>";
	echo "<tr><th>Име</th><th>Фамилия</th><th>Имейл</th><th>Редактиране</th><th>Изтриване</th></tr>";
	while($lecturer = $lecturers->fetch(PDO::FETCH_ASSOC)) {
		echo 
		"<tr>
		<td>".$lecturer["firstname"]."</td>
		<td>".$lecturer["lastname"]."</td>
		<td>".$lecturer["email"]."</td>
		<td><a class='edit' href='edit.php?id=".$lecturer["id"]."'>Редактирай</a></td>
		<td><a class='delete' href='delete.php?id=".$lecturer["id"]."'>Изтрий</a></td>
		</tr>";
	}
	echo "</table>";
}
?>
</body>
</html>