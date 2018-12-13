<?php

#require our database connection file 
#
require_once('header.php');


#create table to store user information
#
$user_table = "CREATE TABLE IF NOT EXISTS users (
		USER_ID INT(11) NOT NULL AUTO_INCREMENT,
		FIRST_NAME VARCHAR(25) NOT NULL,
		LAST_NAME VARCHAR(25) NOT NULL,
		EMAIL VARCHAR(50) NOT NULL,
		PASSWORD VARCHAR(50) NOT NULL,
		PRIMARY KEY (USER_ID) )ENGINE=InnoDB DEFAULT CHARSET=latin1 ";

$table_1 = $db->prepare($user_table);
try {
	$table_1->execute();
} catch (PDOException $e) {
	echo "Error: " . $e->getMessage();
}

#create table to store cars up for lease
#
$cars = "CREATE TABLE IF NOT EXISTS cars (
		CAR_ID INT(11) NOT NULL AUTO_INCREMENT,
		NAME VARCHAR(50) NOT NULL,
		ORIGIN VARCHAR(50) NOT NULL,
		DESTINATION VARCHAR(50) NOT NULL,
		SPACE INT(11) NOT NULL,
		DRIVER VARCHAR(50) NOT NULL,
		PRICE INT(11) NOT NULL,
		PHOTO VARCHAR(225) NOT NULL,
		USER_ID INT(11) NOT NULL,
		PRIMARY KEY (CAR_ID),
		FOREIGN KEY (USER_ID) REFERENCES users (USER_ID) ON DELETE CASCADE ON UPDATE CASCADE )
		ENGINE=InnoDB DEFAULT CHARSET=latin1 ";

$table_2 = $db->prepare($cars);
try {
	$table_2->execute();
} catch (PDOException $e) {
	echo "Error: " . $e->getMessage();
}


#create table to store borrowed car information.
#
$borrow = "CREATE TABLE IF NOT EXISTS borrowed (
		BORROW_ID INT(11) NOT NULL AUTO_INCREMENT,
		BORROW_DATE DATE NOT NULL,
		USER_ID INT(11) NOT NULL,
		CAR_ID INT(11) NOT NULL,
		PRIMARY KEY (BORROW_ID),
		FOREIGN KEY (CAR_ID) REFERENCES cars (CAR_ID) ON DELETE CASCADE ON UPDATE CASCADE )
		ENGINE=InnoDB DEFAULT CHARSET=latin1 ";

$table_3 = $db->prepare($borrow);
try {
	$table_3->execute();
} catch (PDOException $e) {
	echo "Error: " . $e->getMessage();
}
