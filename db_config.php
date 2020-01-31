<?php
	
	try {
		$host = "localhost";
		$user = "root";
		$password = "";
		$database = "dambaal_news";
		$link = mysqli_connect($host, $user, $password, $database);
	} catch (Exception $ex) {
		
	}
