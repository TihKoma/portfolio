<?php 


function startup() {
	setlocale(LC_ALL, "ru_RU.UTF-8");
	mb_internal_encoding("UTF-8");
	
	mysql_connect("localhost", "root", "") or die("Error");
	mysql_query("SET NAMES utf8");
	mysql_select_db("diary");
	
	session_start();
}



?>