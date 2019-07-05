<!DOCTYPE html>
<html>
<head>
	<title>Список задач</title>
</head>
<body>

	<?php
		if (isset($_COOKIE['login']))
			echo "<a href=\"index.php?action=logout\">Выйти</a> <br><br>";
		else
			echo "<a href=\"index.php?action=createFormLogin\">Вход</a> <br><br>";
	?>

	

	<a href="index.php?action=taskCreate">Создать задачу</a> &nbsp;&nbsp;

	<?php
		if (isset($_COOKIE['login']) && $_COOKIE['login'] == 'admin')
			echo "<a href=\"index.php?action=taskEdit&page=$currentPage\">Редактировать</a> <br><br>";
	?>

	Отсортировать по <a href="index.php?action=view_list&page=<?= $currentPage ?>&sortBy=userName">Имени пользователя</a> &nbsp;
	<a href="index.php?action=view_list&page=<?= $currentPage ?>&sortBy=email">email</a> &nbsp;
	<a href="index.php?action=view_list&page=<?= $currentPage ?>&sortBy=status">статусу</a> <br><br>

	<?php
		for ($i = 0; $i < $COUNT; $i++) {
			if ((($currentPage-1)*$COUNT + $i) >= $countRecord) // Кол-во уже выведенных записей (записи на пред страницах + на текущей)
				break;

			$this->status[$i] = ($this->status[$i] == 0) ? "Не выполнен" : "Выполнен";
			echo "<hr> <p>
					<b>".$this->userName[$i]."</b> <br>
					Статус: ".$this->status[$i]." <br>
					".$this->email[$i]." <br>
					".$this->taskContent[$i]." <br>
				</p>";
		};

		echo "Страница ";

		$countPage = ceil($countRecord / $COUNT);

		for ($i = 1; $i <= $countPage; $i++)
			if ($i == $currentPage)
				echo $i."&nbsp";
			else
				echo "<a href=\"index.php?action=view_list&page=".$i."&sortBy=$param\">".$i."</a>&nbsp";

	?>

</body>
</html>