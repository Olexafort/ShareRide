<?php
session_start();

require_once('header.php');

if (!isset($_SESSION['id']) || $_SESSION['id'] == NULL) {
	header("Location: login.php?please_login_to_continue");
}

$now = time();
	
if ($now > $_SESSION['timeout']) {
	session_unset();
	session_destroy();

	header("Location: login.php?session_expired");
}

$session_id = $_SESSION['id'];

$stmt = $db->prepare("SELECT * FROM users WHERE USER_ID = :ID ");
$stmt->execute(array(':ID'=>$session_id));

while($rows = $stmt->fetch(PDO::FETCH_ASSOC)){
	$id = $rows['USER_ID'];
	$name = $rows['FIRST_NAME'] . " " . $rows['LAST_NAME'];
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>ShareRide Inc</title>
</head>
<body>
	<form action="list.php" method="POST" enctype="multipart/form-data">
		<div>
			<input type="text" name="name" placeholder="Enter Car Name or type"><br><br>
		</div>
		<div>
			<input type="text" name="origin" placeholder="Enter Origin"><br><br>
		</div>
		<div>
			<input type="text" name="destination" placeholder="Enter Destination"><br><br>
		</div>
		<div>
			<select name="space">
				<option selected="">Select Vehicle space</option>
				<option>2</option>
				<option>3</option>
				<option>4</option>
				<option>5</option>
				<option>6</option>
				<option>8</option>
			</select><br><br>
		</div>
		<div>
			<input type="float" name="price" placeholder="Enter Price"><br><br>
		</div>
		<div>
			<input type="file" name="photo"><br><br>
		</div>
		<div>
			<input type="hidden" name="driver" value="<?php echo $name; ?>">
		</div>
		<div>
			<input type="hidden" name="ussr_id" value="<?php echo $id; ?>">
		</div>
		<div>
			<input type="submit" name="submit" value="Save Changes">
		</div>
	</form>
</body>
</html>

<?php

function security($var){
	$db = mysqli_connect('localhost', 'root', '', 'shareride');

	mysqli_real_escape_string($db, $var);
	htmlspecialchars($var);

	return $var;
}

if (isset($_POST['submit']) && !empty($_POST['submit'])) {
	
	$origin = security($_POST['origin']);
	$destination = security($_POST['destination']);
	$space = security($_POST['space']);
	$price = security($_POST['price']);
	$driver = security($_POST['driver']);
	$ussr_id = security($_POST['ussr_id']);
	$name = security($_FILES['name']);

	$filetmp = $_FILES["photo"]["file_tmp"];
	$filename = basename($_FILES["photo"]["name"]);

	$filedir = "uploads/" . $filename;

	move_uploaded_file($filetmp, $filedir);

	$stmt = $db->prepare("INSERT INTO cars (NAME, ORIGIN, DESTINATION, SPACE, DRIVER, PRICE, PHOTO, USER_ID) VALUES 
	(:name, :origin, :destination, :space, :driver, :price, :photo, :ussr_id)");

	try {
		$stmt->execute(array(':name'=>$name, 
						':origin'=>$origin,
                        ':destination'=>$destination,
                        ':space'=>$space,
                        ':driver'=>$driver,
                        ':price'=>$price,
                        ':photo'=>$filedir,
                        ':ussr_id'=>$ussr_id));
		header("Location: homepage.php?status=success");

	} catch (PDOException $e) {
	echo "Error: " . $e->getMessage();
	}
}

?>
