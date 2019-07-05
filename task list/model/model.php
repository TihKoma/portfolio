<?php 

$COUNT = 3; //Кол-во задач на странице


function startup() {
	setlocale(LC_ALL, "ru_RU.UTF-8");
	mb_internal_encoding("UTF-8");
	
	mysql_connect("127.0.0.1", "root", "") or die("Error");
	mysql_query("SET NAMES utf8");
	mysql_select_db("task_book");
	
	session_start();
}



?>