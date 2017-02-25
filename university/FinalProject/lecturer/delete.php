<?php
include("../cookies.php");
//areCookiesSet(1, "../login.php");
if(!isset($_COOKIE["email"]) || !isset($_COOKIE["roleID"]) || $_COOKIE["roleID"] != 1)
	header("Location: ../login.php");
// set cookie every time the user visits the page (the user is active)
setCookiesForUser($_COOKIE["email"], 1, $seconds);

?>
<!DOCTYPE html>
<html>
<head>
<title>Изтрий преподавател</title>
</head>
<body>
<?php
// 1. Get the elective with the id from query string
$id = (isset($_GET["id"])) ? $_GET["id"] : '';

// 2. Connect to database
include("../configDB.php");

// 3. Get elective from the database with the id in the query string
$lecturer_with_id_query = "DELETE FROM user WHERE id=:id";
$lecturer_with_id = $conn->prepare($lecturer_with_id_query);
$lecturer_with_id->bindParam(":id", $id);
$lecturer_with_id->execute();
header("Location: list.php");
?>
</body>
</html>