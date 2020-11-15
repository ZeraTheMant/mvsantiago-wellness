<?php
	require '../dbconfig.php';
	require 'get_all_pending_appointments_of_user_functionned.php';
	require 'email_sender.php';
	require 'insert_to_activity_feed.php';
	require 'report_function.php';

	$query = "SELECT * FROM appointments WHERE appointment_id = :appointment_id LIMIT 1";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':appointment_id' => $_POST['appointment_id']
		)
	);
	$stmt->execute();
	
	$mainResult = $stmt->fetch();
	


	if($stmt->rowCount() > 0){

		$query = "SELECT email, fname, mname, lname, name_ext FROM clients WHERE client_id = :id";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':id' => $mainResult['client_id']
			)
		);
		
		$result = $stmt->fetch();
		
		$name = $result['fname'] . ' ' . ucfirst($result['mname'][0]) . '. ' . $result['lname'] . ' ' . $result['name_ext'];
		$appointment_date = strtotime($mainResult['start_datetime']);
		$appointment_date = date('M d Y, h:i a', $appointment_date);
		
		sendEmailMessage(
			"Appointment Automatically Deleted", 
			"Hello, " . $name . ".<br> Your appointment for " . $appointment_date . " was automatically deleted on account of your failure to confirm the appointment during the designated confirmation period.<br>.", 
			$result['email']);

			
		insertToActivityFeed($conn, $mainResult['client_id'], $mainResult['provider_id'], $mainResult['book_datetime'], "'s appointnment was automatically deleted.");
		
		$date = new DateTime(null, new DateTimeZone('Asia/Manila'));	
			
		//insertReport($conn, $name . '\'s appointment was automatically deleted.', '', $date->format('Y-m-d h:i:s'), $mainResult['client_id'], 0, 'applog');
		$query = "
			DELETE FROM 
				appointments
			WHERE
				appointment_id = :appointment_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':appointment_id' => $_POST['appointment_id']
			)
		);

		echo getPendingAppointmentsOfUser($conn);
	}
?>