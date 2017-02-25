<?php
// if cookies with keys "email" or "roleID" are not set or "roleID" value is different
// from $roleID (which is the value for administrator), forward user to login page

$seconds = 1440; // 24 minutes

/*function areCookiesSet($roleID, $location) {
	if(!isset($_COOKIE["email"]) || !isset($_COOKIE["roleID"]) || $_COOKIE["roleID"] != $roleID)
		header("Location: " + $location);
}*/

function setCookiesForUser($email, $roleID, $seconds) {
	setcookie("email", $email, time() + $seconds, "/");
	setcookie("roleID", $roleID, time() + $seconds, "/");
}

function setCookiesAndLocationForUser($email, $roleID, $seconds, $location) {
	setcookie("email", $email, time() + $seconds, "/");
	setcookie("roleID", $roleID, time() + $seconds, "/");
	header("Location: ".$location);
}
?>