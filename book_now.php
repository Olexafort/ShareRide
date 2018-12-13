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
	$email = $rows['EMAIL'];
	$username = $rows['FIRST_NAME'] . " " . $rows['LAST_NAME'];
}

$car_details = $db->prepare("SELECT * FROM cars WHERE CAR_ID = :car_id ");
$car_details->execute(array(':car_id'=>$car_id));

while ($car_rows = $car_details->fetch(PDO::FETCH_ASSOC)) {
	$origin = $car_rows['ORIGIN'];
	$destination = $car_rows['DESTINATION'];
	$driver = $car_rows['DRIVER'];
	$space = $car_rows['SPACE'];
}

$today = date('Y-m-d');

$add_car = $db->prepare("INSERT INTO borrowed (BORROW_DATE, USER_ID, CAR_ID) VALUES (:now_date, :ussr_id, :car_id) ");
$add_car->execute(array(':now_date'=>$today,
						':ussr_id'=>$id,
						':car_id'=>$car_id));

$mail = new PHPMailer;

$mail->From = "olelnash@gmail.com";
$mail->FromName = "Odhiambo Nashon";

$mail->addAddress($email, $username);


$mail->addReplyTo("olelnash@gmail.com", "Reply");


//Send HTML or Plain Text email
$mail->isHTML(true);

$mail->Subject = "You have successfully booked your ride";
$mail->Body = "<i> You have succesfully booked a ride from " . $origin . "to " . $destination . "The car has a total passanger space of " .$space . "and your driver will be " . $driver . "Thank you and enjoy your ride</i>";

$mail->AltBody = "You have succesfully booked a ride from " . $origin . "to " . $destination . "The car has a total passanger space of " .$space . "and your driver will be " . $driver . "Thank you and enjoy your ride";

if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
    header("Location: index.php?status=success");
}
?>