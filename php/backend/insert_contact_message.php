<?php
//insert.php
	require '../dbconfig.php';
	require 'email_sender.php';
	require 'insert_to_activity_feed.php';
	require 'itexmo.php';
	
	$query = "INSERT INTO message_inbox (
			msg_sender, 
			sender_email, 
			sender_contact,
			message,
			date_sent,
			is_read
		)
		VALUES
		(
			:senderName,
			:senderEmail,
			:senderContact,
			:senderMessage,
			:date_sent,
			:is_read
		)";
		
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':senderName' => $_POST['senderName'],
			':senderEmail' => $_POST['senderEmail'],
			':senderContact' => $_POST['senderContact'],
			':senderMessage' => $_POST['senderMessage'],
			':date_sent' => $_POST['dateSent'],
			':is_read' => $_POST['is_read']
		)
	);

	if($stmt->rowCount() == 1){
		
		sendEmailMessage(
			"INQUIRY ACKNOWLEDGEMENT", 
			"Hello " . $_POST['senderName'] . ". Your inquiry has been received. Thank you for contacting our clinic and expect a speedy reply.",
			$_POST['senderEmail']
		);
		
		insertToActivityFeed($conn, "", 0, $_POST['dateSent'], $_POST['senderName'] . " has sent a message.");
		
		itexmo($_POST['senderContact'], "Your appointment new needs confirmation in your email.", 'TR-M.V.S269193_HBLWE');
		
		$query = "SELECT msg_id FROM message_inbox WHERE msg_id = LAST_INSERT_ID()";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetch();
		
		echo $result['msg_id'];
	}
	else{
		echo false;
	}
?>