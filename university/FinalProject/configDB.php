<?php
include("config.php");
$servername = "127.0.0.1:".$DB_PORT;
//$dbname = ;
//$username = ;
//$pass = ;
$conn = null;
try {
	$conn = new PDO("mysql:host=$servername;dbname=$DB_NAME", $DB_USER, $DB_PASS);
} catch (PDOException $e) {
	die("Connection failed: ".$e->getMessage());
}
?>