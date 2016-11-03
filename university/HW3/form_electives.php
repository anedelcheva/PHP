<html>
<body>
<form action="add_elective.php" method="post">
	Име на предмета:<br/>
	<input type="text" name="subject" size="40" autofocus>
	<br/><br/>
	Преподавател: <br/>
	<input type="text" name="lecturer" size="40">
	<br/><br/>
	Описание: <br/>
	<textarea name="description" rows="5" cols="30"></textarea>
	<br/><br/>
	Група:
	<select name="group">
		<option value="maths">М</option>
		<option value="applied_maths">ПМ</option>
		<option value="CSbasics">ОКН</option>
		<option value="CScore">ЯКН</option>
	</select>
	<br/><br/>
	Кредити:
	<input type="number" name="credits" step="0.5">
	<br/><br/>
	<input type="submit" value="Добави">
</form>
</body>
</html>