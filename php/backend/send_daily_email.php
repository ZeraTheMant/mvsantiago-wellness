<?php
	require '../dbconfig.php';
	require 'email_sender.php';
	require 'insert_to_activity_feed.php';
	require 'itexmo.php';
	
	$query = "
		UPDATE 
			appointments
		SET
			week_of_date = :dummy
		WHERE
			appointment_id = :id
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':dummy' => $_POST['dummy'],
			':id' => $_POST['id']
		)
	);
	
	$query = "SELECT email, fname, mname, lname, name_ext FROM clients WHERE client_id = :id";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':id' => $_POST['client_id']
		)
	);
	
	$result = $stmt->fetch();
	
	$name = $result['fname'] . ' ' . ucfirst($result['mname'][0]) . '. ' . $result['lname'] . ' ' . $result['name_ext'];
	$appointment_date = strtotime($_POST['start_datetime']);
	$appointment_date = date('M d Y, H:i a', $appointment_date);
	
	$now = new DateTime(substr($_POST['dummy'], 0, 10));
    $ref = new DateTime(substr($_POST['start_datetime'], 0, 10));
    $diff = $now->diff($ref);
	
	sendEmailMessage(
		"Appointment Reminder", 
		"Hello, " . $name . ".<br> Your appointment for " . $appointment_date . " is " . $diff->d . " days from now.<br>.", 
		$result['email']);
	
	
	insertToActivityFeed($conn, $_POST['client_id'], 0, $_POST['start_datetime'], "appointment reminder sent.");
?>