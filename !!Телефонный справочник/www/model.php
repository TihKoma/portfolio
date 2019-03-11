<?php


	function startup(){
		setlocale(LC_ALL, "ru_RU.UTF-8");
		mb_internal_encoding("UTF-8");

		mysql_connect("127.0.0.1", "root", "") or die("Error connecting with DB");
		// mysql_connect("localhost", "root", "") or die("Error connecting with DB");
		mysql_query("SET NAMES utf8");
		mysql_select_db("lab");
		mysql_query("SET CHARACTER SET 'utf8'");
		mysql_query("SET SESSION collation_connection = 'utf8_general_ci'");

		session_start();
	};


?>