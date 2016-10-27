<html>
<body>
<form action="add_elective.php" method="post">
	Име на предмета:<br/>
	<input type="text" name="subject" maxlength="150" size="40" autofocus required>
	<br/><br/>
	Преподавател: <br/>
	<input type="text" name="lecturer" maxlength="200" size="40" required>
	<br/><br/>
	Описание: <br/>
	<textarea name="description" rows="5" cols="30" minlength="10" required></textarea>
	<br/><br/>
	Група:
	<select name="group" required>
		<option value="maths">М</option>
		<option value="applied_maths">ПМ</option>
		<option value="CSbasics">ОКН</option>
		<option value="CScore">ЯКН</option>
	</select>
	<br/><br/>
	Кредити:
	<input type="number" name="credits" min="0.5" step="0.5" required>
	<br/><br/>
	<input type="submit" value="Добави">
</form>
</body>
</html>