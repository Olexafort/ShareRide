<?php

if (isset($_GET['id']) && !empty($_GET['id'])) {
	
	$car_id = $_GET['id'];
}



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
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>BOOK RIDE</title>
	<center><h2>BOOK NOW</h2></center>
</head>
<body>
	<?php

	$car_details = $db->prepare("SELECT * FROM cars WHERE CAR_ID = :car_id ");
	$car_details->execute(array(':car_id'=>$car_id));

	while ($car_rows = $car_details->fetch(PDO::FETCH_ASSOC)) {
		?>

		<table>
			<tr>
				<td><img src="<?php echo $car_rows['PHOTO']; ?>" width="400px" height="auto"></td>
				
			</tr>
			<tr>
				<td><?php echo "ORIGIN: " . $car_rows['ORIGIN'] . "   " .   "DESTINATION: " . $car_rows['DESTINATION']; ?></td><br><br>
			</tr>
			<tr>
				<td><?php echo "CAR SPACE: " . $car_rows['SPACE']; ?></td><br><br>
			</tr>
			<tr>
				<td><?php echo "DRIVER: " . $car_rows['DRIVER']; ?></td><br><br>
			</tr>
			<tr>
				<td><a href="book_now.php?id=<?php echo $car_id; ?>"><button>PROCEED TO BOOKING</button></a></td>
			</tr>
		</table>

		<?php
	}


	?>
</body>
</html>