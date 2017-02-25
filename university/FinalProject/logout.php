<?php
include("cookies.php");
setCookiesAndLocationForUser("", "", -3600, "login.php");
// we delete cookies by setting time in the past
/*setcookie("email", "", time() - 3600, "/"); 
setcookie("roleID", "", time() - 3600, "/");
header("Location: login.php");*/
?>