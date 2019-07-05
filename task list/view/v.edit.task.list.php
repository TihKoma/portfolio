<!DOCTYPE html>
<html>
<head>
	<title>Список задач</title>

	<style type="text/css">
		textarea
		{
			width: 400px;
			height: 100px;
		}
	</style>
</head>
<body>

	<a href="index.php">На главную</a>

	<?php
		for ($i = 0; $i < $COUNT; $i++) {
			if ((($currentPage-1)*$COUNT + $i) >= $countRecord) // Кол-во уже выведенных записей (записи на пред страницах + на текущей)
				break;

			$this->status[$i] = ($this->status[$i] == 0) ? "" : "checked";
			echo "<form method=\"POST\" action=\"index.php?action=saveChanges&id=".$this->id[$i]."\">
						<hr>
						<p>
							<b>".$this->userName[$i]."</b> <br>
							Статус: <input type=\"checkbox\" name=\"status\" ".$this->status[$i]."> <br>
							".$this->email[$i]." <br>
							<textarea name=\"content\">".$this->taskContent[$i]."</textarea> <br>
							<button>Сохранить</button>
						</p>
					</form>";
		};

		echo "Страница ";

		$countPage = ceil($countRecord / $COUNT);

		for ($i = 1; $i <= $countPage; $i++)
			if ($i == $currentPage)
				echo $i."&nbsp";
			else
				echo "<a href=\"index.php?action=taskEdit&page=".$i."\">".$i."</a>&nbsp";

	?>


</body>
</html>