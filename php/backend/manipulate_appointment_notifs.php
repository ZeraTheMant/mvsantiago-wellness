<?php
	require '../dbconfig.php';
	require 'get_all_pending_appointments_of_user_functionned.php';
	require 'email_sender.php';
	require 'insert_to_activity_feed.php';
	require 'report_function.php';
	require 'itexmo.php';
	
	$query = "
		UPDATE 
			appointments
		SET
			has_sent_day_of_notif_msg = 1
		WHERE
			appointment_id = :appointment_id
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':appointment_id' => $_POST['id']
		)
	);
	
	if($stmt->rowCount() > 0){
		
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
		
		sendEmailMessage(
			"Appointment Reminder", 
			"Hello, " . $name . ".<br> Your appointment for " . $appointment_date . " is scheduled for today.<br>.", 
			$result['email']);
		
		//itexmo($_POST['contact'], "Your appointment for " . $appointment_date . " is scheduled for today.", 'TR-M.V.S269193_HBLWE');
		
		$query = "
			SELECT * FROM appointments WHERE appointment_id = :appointment_id LIMIT 1
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':appointment_id' => $_POST['id']
			)
		);
		
		$result = $stmt->fetch();
			
		insertToActivityFeed($conn, $_POST['client_id'], $result['provider_id'], $result['book_datetime'], "'s appointnment is scheduled for today.");
		
		
		$date = new DateTime(null, new DateTimeZone('Asia/Manila'));	
			
		//insertReport($conn, $name . '\'s appointment is scheduled for today.', '', $date->format('Y-m-d h:i:s'), $_POST['client_id'], $result['provider_id'], 'applog');
		
		echo getPendingAppointmentsOfUser($conn);
	}
?>