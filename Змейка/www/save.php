<?php

//Сохраняет игрока в БД
function startup()
{
	// Настройки подключения к БД.
	$hostname = 'localhost'; 
	$username = 'root'; 
	$password = '';
	$dbName = 'snake';
	
	// Языковая настройка.
	setlocale(LC_ALL, 'ru_RU.CP1251');	
	
	// Подключение к БД.
	mysql_connect($hostname, $username, $password) or die('No connect with data base'); 
	mysql_query('SET NAMES utf8');
	mysql_select_db($dbName) or die('No data base');

	//Открытие сессии.
	session_start();	
}

startup();

if (isset($_POST['name'])) {
	$name = $_POST['name'];
	echo $name;
	// mysql_query("INSERT INTO player (name, score) VALUES ('$name','0')");//Добавление в БД игрока
}

if (isset($_POST['score'])) {
	$score = $_POST['score'];
	mysql_query("INSERT INTO player (score) VALUES ('$score')");//Добавление в БД очки игрока
}

if ($name != '' && $score != '') {
	mysql_query("INSERT INTO player (name, score) VALUES ('$name','$score')");//Добавление в БД игрока и его очки
}
// mysql_close();

?>