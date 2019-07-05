<!DOCTYPE html>
<html>
<head>
	<title>Создать задачу</title>
	<style type="text/css">
		input
		{
			width: 300px;
		}
	</style>
</head>
<body>

	<a href="index.php">На главную</a> <br>	<br>

	<form method="POST" action="index.php?action=taskAdd">
		<input type="text" name="action" value="addTask" hidden>
		<input type="text" name="userName" placeholder="Имя пользователя"> <br> <br>
		<input type="text" name="email" placeholder="e-mail"> <br> <br>
		<textarea name="taskContent" placeholder="Текст задачи" style="width: 300px"></textarea> <br> <br>

		<button style="width: 100px">Отправить</button>
	</form>

</body>
</html>