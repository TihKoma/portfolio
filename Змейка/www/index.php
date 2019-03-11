<?php 

include('save.php');
?>


<!DOCTYPE html> 
<html>
<head>
	<title>GAME</title>
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" /><meta charset="utf-8">
	<script src="jquery-1.11.0.js"></script>
	<script language="javascript" src="game.js"></script>
	<script language="javascript" src="matrix.js"></script>
	<script language="javascript" src="onload.js"></script>
	<script language="javascript" src="square.js"></script>
</head>
<body>
	<div id="matrix1"></div>
	Набранные очки:	<b id="score">0</b>

	<div class="background"></div>

	<div id="save_game">
		<h2 style="text-align: center;">Регистрация</h2>
		<form action="" method="post">
			Введите имя <br>
			<input type="text" name="name" id="name">	<br> <br>

			<!-- <input type="submit" value="Сохранить" id="save"> -->
			<div id="save">Сохранить</div>
		</form>
		<div id="cancel"></div>
	</div>
</body>
</html>
