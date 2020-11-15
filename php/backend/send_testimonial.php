<?php
	require '../dbconfig.php';
	require 'insert_to_activity_feed.php';
	require 'email_sender.php';
	
	//$inipath = php_ini_loaded_file();

	$query = "
		INSERT INTO
			testimonials
		(
			client_id,
			sender_address,
			message,
			approval_status
		)
		VALUES
		(
			:client_id,
			:address,
			:message,
			:approval_status
		)
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':client_id' => $_POST['client_id'],
			':address' => $_POST['address'],
			':message' => $_POST['message'],
			':approval_status' => $_POST['approval_status'],
		)
	);
	
	if($stmt->rowCount() == 1){
		
		sendEmailMessage(
			"Testimonial Subject for Approval", 
			"Hello, your testimonial has been received and is now subject for approval by the administrator. <br><br> Thank you for sharing your experience with M.V. Santiago Wellness Center.",
			$_POST['senderEmail']
		);
		
		insertToActivityFeed($conn, $_POST['client_id'], 0, $_POST['dateSent'],  " has sent a testimonial.");
		
		echo "Testimonial successfully sent to the administrator for approval.";
	}
	else{
		echo "Testimonial not sent.";
	}
?>