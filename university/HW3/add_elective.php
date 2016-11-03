<html>
<body>
<?php
// exploit security
$subject = $lecturer = $description = $group = $credits = "";
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$subject = modify_input($_POST["subject"]);
	$lecturer = modify_input($_POST["lecturer"]);
	$description = modify_input($_POST["description"]);
	$group = modify_input($_POST["group"]);
	$credits = modify_input($_POST["credits"]);
}

function modify_input($data)
{
	$data = trim($data); // removes whitespace characters from the beginning and end of a given string
	$data = stripslashes($data); // unquotes a quoted string
	$data = htmlspecialchars($data); // converts special characters to HTML entities (> is converted to &gt;)
	return $data;
}

// validation
$errors = array();
if(empty($subject))
{
	array_push($errors, "Subject is required");
}
else if(strlen($subject) > 150)
{
	array_push($errors, "Subject cannot be more than 150 symbols");
}

if(empty($lecturer))
{
	array_push($errors, "Lecturer is required");
}
else if(strlen($lecturer) > 200)
{
	array_push($errors, "Lecturer's name cannot be more than 200 symbols");
}

if(empty($description))
{
	array_push($errors, "Description is required");
}
else if(strlen($description) < 10)
{
	array_push($errors, "Description cannot be less than 10 symbols");
}

if(empty($group))
{
	array_push($errors, "Group is required");
}

if(empty($credits))
{
	array_push($errors, "Credits are required");
}
else if(floatval($credits) > 10)
{
	array_push($errors, "Credits cannot be greater than 10");
}
else if(floatval($credits) < 0.5)
{
	array_push($errors, "Credits cannot be less than 0.5");
}

if(count($errors) > 0)
{
	echo "<ul style='color: red;'>";
	foreach($errors as $error)
	{
		echo "<li>$error</li>";
	}
	echo "</ul>";
}
else
{
	echo "Име на предмета: ".$subject."<br>";
	echo "Преподавател: ".$lecturer."<br>";
	echo "Описание: ".$description."<br>";
	if($group == "maths")
		echo "Група: M<br>";
	elseif($group == "applied_maths")
		echo "Група: ПM<br>";
	elseif($group == "CSbasics")
		echo "Група: ОКН<br>";
	elseif($group == "CScore")
		echo "Група: ЯКН<br>";
	echo "Кредити: ".$credits;
}
?>