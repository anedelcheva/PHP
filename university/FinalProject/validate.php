<?php
include("modify_input.php");

// email check
function checkEmail($email, $user) {
	$email_check = "";
	if(empty($email))
		$email_check = "Въведете имейл.";
	else if($user->rowCount() > 0)
		$email_check = "Този имейл вече съществува в системата.";
	else if(!preg_match('/^([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)@([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)[\\.]([a-zA-Z]{2,9})$/', $email))
		$email_check = "Имейлът не е валиден.";
	return $email_check;
}

function checkEmail2($email) {
	$email_check = "";
	$get_user_sql = "SELECT * FROM user WHERE email = '$email'";
	$user = $conn->query($get_user_sql);
	if(empty($email))
		$email_check = "Въведете имейл.";
	else if($user->rowCount() > 0)
		$email_check = "Този имейл вече съществува в системата.";
	else if(!preg_match('/^([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)@([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)[\\.]([a-zA-Z]{2,9})$/', $email))
		$email_check = "Имейлът не е валиден.";
	return $email_check;
}

// firstname check
function checkFirstname($firstname) {
	$firstname_check = "";
	if(empty($firstname))
		$firstname_check = "Въведете име.";
	else if(mb_strlen($firstname, 'utf8') < 2)
		$firstname_check = "Името не може да бъде по-малко от 2 символа.";
	else if(mb_strlen($firstname, 'utf8') > 20)
		$firstname_check = "Името не може да бъде повече от 20 символа.";
	return $firstname_check;
}
		
// lastname check
function checkLastname($lastname) {
	$lastname_check = "";
	if(empty($lastname))
		$lastname_check = "Въведете фамилия.";
	else if(mb_strlen($lastname, 'utf8') < 2)
		$lastname_check = "Фамилията не може да бъде по-малка от 2 символа.";
	else if(mb_strlen($lastname) > 25)
		$lastname_check = "Фамилията не може да бъде повече от 25 символа.";
	return $lastname_check;
}
	
// password check
function checkPassword($password) {
	$pass_check = "";
	if(empty($password))
		$pass_check = "Въведете парола.";
	else if(!(preg_match( '~[A-Z]~', $password) &&
			  preg_match( '~[a-z]~', $password) &&
			  preg_match( '~\d~', $password) &&
			  strlen($password) > 6))
		$pass_check = "Паролата трябва да е поне 7 символа и да съдържа поне 1 цифра, 1 малка буква и 1 голяма буква.";
	return $pass_check;
}

function checkElectiveName($name) {
	$name_check = "";
	if(empty($name))
		$name_check = "Въведете дисциплина.";
	else if(mb_strlen($name, 'utf8') > 100)
		$name_check = "Дисциплината не може да е повече от 100 символа.";
	else if(mb_strlen($name, 'utf8') < 6)
		$name_check = "Дисциплината не може да е по-малко от 6 символа.";
	return $name_check;
}

function checkElectiveDescription($description) {
	$description_check = "";
	if(empty($description))
		$description_check = "Въведете описание.";
	else if(mb_strlen($description, 'utf8') > 3000)
		$description_check = "Описанието не може да е повече от 3000 символа.";
	else if(mb_strlen($description, 'utf8') < 10)
		$description_check = "Описанието не може да е по-малко от 10 символа.";
	return $description_check;
}

function checkElectiveCredits($credits) {
	$credits_check = "";
	if($credits > 10)
		$credits_check = "Кредитите не могат да са повече от 10.";
	else if($credits < 1)
		$credits_check = "Кредитите не могат да са по-малко от 1.";
	return $credits_check;
}

function ifEmpty($param, $message) {
	$check = "";
	if(is_int($param) || is_double($param))
		$check = "";
	else if(empty($param))
		$check = $message;
	return $check;
}

function ifElementSet($postElement) {
	$element = "";
	if(isset($postElement))
		$element = modify_input($postElement);
	return $element;
}

function ifElementSetInt($postElement) {
	$element = "";
	if(isset($postElement))
		$element = intval(modify_input($postElement));
	return $element;
}
?>