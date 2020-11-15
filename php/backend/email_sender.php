<?php
	function sendEmailMessage($subject, $msgBody, $address){
		require('PHPMailer/PHPMailer.php');
		require('PHPMailer/SMTP.php');
		require('PHPMailer/Exception.php');
		require('PHPMailer/OAuth.php');
		require('PHPMailer/POP3.php');
		$mail = new PHPMailer\PHPMailer\PHPMailer();
		$mail->IsSMTP();	
		$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		//$mail->Host = "smtp.gmail.com";
		$mail->Host = "mail.mvsantiagowellness.com";
		$mail->Port = 465; // or 587 or 465
		$mail->IsHTML(true);
		//$mail->Username = "info.jarindentalclinic@gmail.com";
		//$mail->Password = "jarindentalclinicinfo.1997";		
		$mail->Username = "noreply@mvsantiagowellness.com";
		$mail->Password = "09154891147Qft_";	
		$mail->SetFrom('noreply@mvsantiagowellness.com');
		$mail->FromName = 'M.V. Santiago Wellness Center';
		$mail->WordWrap = 50;
		$mail->Subject = $subject;
		$mail->Body = $msgBody;
		$mail->AddAddress($address);
		
		$error = '';
		if($mail->Send()){
			$error = '<label>Thank you for contacting us.</label>';
		}
		else{
			$error = '<label>An error occurred.</label>';
		}
	}
?>