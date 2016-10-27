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
else if(floatval($credits) <= 0)
{
	array_push($errors, "Credits cannot be less than or equal to 0");
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

// preserve values
?>
Име на предмета:<br>
<input type="text" name="subject" maxlength="150" size="40" required value="<?php echo $subject; ?>">
<br><br>
Преподавател:<br>
<input type="text" name="lecturer" maxlength="200" size="40" required value="<?php echo $lecturer; ?>">
<br><br>
Описание:<br>
<textarea name="description" rows="5" cols="30" minlength="10" required><?php echo $description; ?></textarea>
<br><br>
Група:
<select name="group" required>
	<option value="maths" <?php if($group == "" || $group == "maths") echo 'selected="selected"'?>>М</option>
	<option value="applied_maths" <?php if($group == "" || $group == "applied_maths") echo 'selected="selected"'?>>ПМ</option>
	<option value="CSbasics" <?php if($group == "" || $group == "CSbasics") echo 'selected="selected"'?>>ОКН</option>
	<option value="CScore" <?php if($group == "" || $group == "CScore") echo 'selected="selected"'?>>ЯКН</option>
</select>
<br><br>
Кредити:
<input type="number" name="credits" min="0.5" step="0.5" required value="<?php echo $credits; ?>">
</body>
</html>