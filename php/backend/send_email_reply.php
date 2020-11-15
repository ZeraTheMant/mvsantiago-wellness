<?php
	include 'email_sender.php';
	
	sendEmailMessage("Reply to your inquiry to M.V. Santiago Wellness Center", $_POST['message'], $_POST['to_address']);
?>