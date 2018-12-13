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
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>ShareRide Inc</title>
</head>
<body>
	<div class="rows">
		<div class="cols-md-6">
			<a href="list.php?id=<?php echo $id; ?>"><button>LIST A RIDE</button></a>
		</div>
	</div>

	<div>
		<?php

		$cars = $db->prepare("SELECT * FROM cars ORDER BY CAR_ID DESC");
		$cars->execute();

		while ($row = $cars->fetch(PDO::FETCH_ASSOC)) {
			?>

			<table>
				<tr>
					<td><img src="<?php echo $row['PHOTO']; ?>" width="400px" height="auto"></td>
				</tr>
				<tr>
					<td><?php echo "ORIGIN: " . $row['ORIGIN']; ?></td>
				</tr>
				<tr>
					<td><?php echo "DESTINATION: " . $row['DESTINATION']; ?></td>
				</tr>
				<tr>
					<td><?php echo "SPACE: " . $row['SPACE']; ?></td>
				</tr>
				<tr>
					<td><?php echo "DRIVER: " . $row['DRIVER']; ?></td>
				</tr>
				<tr>
					<td><?php echo "PRICE: " . $row['PRICE']; ?></td>
				</tr>
				<tr>
					<td><a href="book.php?id=<?php echo $row['CAR_ID']; ?>"><button>BOOK RIDE</button></a></td>
				</tr>
			</table>

			<?php
		}
		?>
	</div>
</body>
</html>