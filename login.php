<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
</head>
<body>
	<form action="login.php" method="POST" enctype="multipart/form-data">
		<fieldset>
			<legend>Login Form</legend>
			<input type="email" name="email" placeholder="Enter Email Address" required=""><br><br>
			<input type="password" name="password" placeholder="Enter Password" required=""><br><br>
			<input type="submit" name="submit" value="LOGIN">
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
	
	$email = security($_POST['email']);
	$password = security($_POST['password']);

	$stmt = $db->prepare("SELECT * FROM users WHERE EMAIL = :email AND PASSWORD = :password");
	
	try {

		$stmt->execute(array(':email'=>$email,
					':password'=>$password));

		$row_count = $stmt->rowCount();

		if ($row_count > 0) {
			session_start();

			while($rows = $stmt->fetch(PDO::FETCH_ASSOC)){
				
				$_SESSION['id'] = $rows['USER_ID'];
				$_SESSION['user'] = $rows['FIRST_NAME'] . " " . $rows['LAST_NAME'];
				$_SESSION['start'] = time();
				$_SESSION['timeout'] = $_SESSION['start'] + (1800);

				header("Location: homepage.php?" . $_SESSION['id']);
			}
			

		}else{
			header("Location: index.php?status=create_account_to_continue");
		}

	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}

	
}

?>