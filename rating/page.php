<?php
	// Основной роутер

	include_once "config.php";
	include_once "model/m_Base.php";
	include_once "model/m_MYSQL.php";
	include_once "model/m_Page.php";
	include "controller/c.page.php";

	$method = "";
	$params = [];

	$url = parse_url("http://".$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);	// Получаем текущий полный URL
	$dirs = explode('/', $url['path']);											// Распетрушиваем путь на «папки»

	isset($url['query']) && parse_str($url['query'], $_GET);					// Парсим переменные GET в глобальный массив $_GET

	for ($i = 1; $i < (count($dirs)-1); $i++)									// Декодируем в UTF-8 все символы, отличные от латиницы
		$dirs[$i] = urldecode($dirs[$i]);

	$Page = Page::getInstance();												// Получаем экземпляр класса (Singleton)

	$Page->getMethod($dirs, $method, $params);
	$Page->$method($params);






?>
