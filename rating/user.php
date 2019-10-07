<?php
	// Роутер для внутреннего использования. Отвечает за изменения данных в БД о User'е

	include_once "config.php";
	include_once "model/m_Base.php";
	include_once "model/m_MYSQL.php";
	include_once "model/m_User.php";
	include "controller/c.user.php";


	$method = "";
	$params = [];

	$url = parse_url("http://".$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);	// Получаем текущий полный URL
	$dirs = explode('/', $url['path']);											// Распетрушиваем путь на «папки»

	isset($url['query']) && parse_str($url['query'], $_GET);					// Парсим переменные GET в глобальный массив $_GET

	for ($i = 1; $i < (count($dirs)-1); $i++)									// Декодируем в UTF-8 все символы, отличные от латиницы
		$dirs[$i] = urldecode($dirs[$i]);


	$User = User::getInstance();												// Получаем экземпляр класса (Singleton)


	$User->getMethod($dirs, $method, $params);
	$User->$method($params);





?>
