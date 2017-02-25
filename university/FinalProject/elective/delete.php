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
<title>Изтрий изборна</title>
</head>
<body>
<?php
// 1. Get the elective with the id from query string
$id = (isset($_GET["id"])) ? $_GET["id"] : '';
// 2. Connect to database
include("../configDB.php");

$email = "";
if(isset($_COOKIE["email"]))
	$email = $_COOKIE["email"];
// 3. take user id from email
$get_admin_sql = "SELECT * FROM user WHERE email='$email'";
$user = $conn->query($get_admin_sql);
$userID = "";
if($user->rowCount() > 0) {
	$admin = $user->fetch(PDO::FETCH_ASSOC);
	$userID = $admin["id"];
}
//echo $id.'<br>';
$elective_sql = "SELECT * FROM elective WHERE id=:id";
$elective_row = $conn->prepare($elective_sql);
$elective_row->bindParam(":id", $id);
$elective_row->execute();
$elective = "";
if($elective_row->rowCount() > 0) {
	$elective = $elective_row->fetch(PDO::FETCH_ASSOC);
}
if($elective["createdBy"] == $userID) {
	//echo "Титуляр<br>";
	// 4. delete entry from user_elective with userID of user with this email and electiveID the id of the elective
	$user_elective_with_id_query = "DELETE FROM user_elective WHERE electiveID=:id";
	$user_elective_with_id = $conn->prepare($user_elective_with_id_query);
	$user_elective_with_id->bindParam(":id", $id);
	$user_elective_with_id->execute();
	
	$delete_elective_comment_sql = "DELETE FROM comment WHERE electiveID=:id";
	$delete_elective_comment = $conn->prepare($delete_elective_comment_sql);
	$delete_elective_comment->bindParam(":id", $id);
	$delete_elective_comment->execute();

	$elective_with_id_query = "DELETE FROM elective WHERE id=:id";
	$elective_with_id = $conn->prepare($elective_with_id_query);
	$elective_with_id->bindParam(":id", $id);
	$elective_with_id->execute();
} else {
	//echo "Не е титуляр<br>";
	$user_elective_with_id_query = "DELETE FROM user_elective WHERE userID=:userID AND electiveID=:id";
	$user_elective_with_id = $conn->prepare($user_elective_with_id_query);
	$user_elective_with_id->bindParam(":userID", $userID);
	$user_elective_with_id->bindParam(":id", $id);
	$user_elective_with_id->execute();
	
	$get_user_elective_with_id_sql = "SELECT * FROM user_elective WHERE electiveID='$id'";
	$get_user_elective_with_id = $conn->query($get_user_elective_with_id_sql);
	if($get_user_elective_with_id->rowCount() == 0) {
		
		$delete_elective_comment_sql = "DELETE FROM comment WHERE electiveID=:id";
		$delete_elective_comment = $conn->prepare($delete_elective_comment_sql);
		$delete_elective_comment->bindParam(":id", $id);
		$delete_elective_comment->execute();
		
		$elective_with_id_query = "DELETE FROM elective WHERE id=:id";
		$elective_with_id = $conn->prepare($elective_with_id_query);
		$elective_with_id->bindParam(":id", $id);
		$elective_with_id->execute();
	}
}
header("Location: list.php");
?>
</body>
</html>