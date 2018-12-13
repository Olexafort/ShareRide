<!DOCTYPE html>
<html>
<head>
	<title>Sign-up Page</title>
</head>
<body>
	<form action="index.php" method="POST" enctype="multipart/form-data">
		<fieldset>
			<legend>Sign-Up</legend>
			<input type="text" name="first" placeholder="First Name" required=""><br><br>
			<input type="text" name="last" placeholder="Last Name" required=""><br><br>
			<input type="email" name="email" placeholder="Email Address" required=""><br><br>
			<input type="password" name="password" placeholder="Enter password" required=""><br><br>
			<input type="submit" name="submit" value="SIGNUP">
		</fieldset>
	</form>
</body>
</html>

<?php

require_once('header.php');

function security($var){
	$db = mysqli_connect('localhost', 'root', '', 'shareride');

	mysqli_real_escape_string($db, $var);
	htmlspecialchars($var);

	return $var;
}

if (isset($_POST['submit']) && !empty($_POST['submit'])) {

	$first = security($_POST['first']);
	$last = security($_POST['last']);
	$email = security($_POST['email']);
	$password = security($_POST['password']);
	
	$check = $db->prepare("SELECT * FROM users WHERE EMAIL = :email ");
	$check->bindValue(':email', $email);
	$check->execute();
	$row_count = $check->rowCount();

	if ($row_count > 0) {
		header("Location: index.php?status=account_already_exists");
	}else{
		$stmt = $db->prepare("INSERT INTO users (FIRST_NAME, LAST_NAME, EMAIL, PASSWORD) VALUES (:first, :last, :email, :password)");
	

		try {
			$stmt->execute(array(':first'=>$first,
							':last'=>$last,
							':email'=>$email,
							':password'=>$password));

			header("Location: login.php");

		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}

	
}

?>