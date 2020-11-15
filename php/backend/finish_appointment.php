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
			is_finished = 1
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
			"Appointment Finished", 
			"Hello, " . $name . ".<br> Your appointment for " . $appointment_date . " has ended.<br><br>You can schedule another appointment with us anytime.", 
			$result['email']);
			
		itexmo($_POST['contact'], "Your appointment for " . $appointment_date . " has ended.", 'TR-M.V.S269193_HBLWE');
			
		$date = new DateTime(null, new DateTimeZone('Asia/Manila'));	
			
		//insertReport($conn, $result['fname'] . ' ' . $result['mname'] . ' ' . $result['lname'] . '\'s appointment has ended.', '', $date->format('Y-m-d H:i:s'), $_POST['client_id'], 0, 'applog');
			
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
			
		insertToActivityFeed($conn, $_POST['client_id'], $result['provider_id'], $result['book_datetime'], "'s appointnment has ended.");
		
		echo getPendingAppointmentsOfUser($conn);
	}
?>