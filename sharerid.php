<?php

$username = "root";
$password = "";

$con = new PDO ("mysql:dbname=modules; host=localhost", $username, $password);
$con -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$con -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$table_one = $con -> prepare('
	CREATE TABLE IF NOT EXISTS users (
		USER_ID INT(11) NOT NULL AUTO_INCREMENT,
		FIRST_NAME VARCHAR(25) NOT NULL,
		LAST_NAME VARCHAR(25) NOT NULL,
		EMAIL VARCHAR(50) NOT NULL,
		PROFILE_PHOTO VARCHAR(225) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1; ');
try {

	$table_one -> excute();

} catch (PDOException $e) {
	echo "An error occured: " . $e -> getMessage();
}





?>