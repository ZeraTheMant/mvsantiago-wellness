<?php
	require 'php/dbconfig.php';

	function redirect($msg){
		echo '
			<script type="text/javascript">
				alert("'. $msg .'");
				window.location = "http://mvsantiagowellness.com";
			</script>
		';
		//header('Location: https://jarindentalclinic.com');
		exit();
	}
	
	if(!isset($_GET['email']) ||  !isset($_GET['token'])){
		redirect("No email and token given.");
	}
	else{
		$email = $_GET['email'];
		$token = $_GET['token'];
		
		$query = "
			SELECT client_id FROM clients 
			WHERE email = :email 
			AND token = :token
			AND is_email_verified = 0
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':email' => $email,
				':token' => $token
			)
		);
		
		if($stmt->rowCount() > 0){
			$query = "
				UPDATE clients
				SET is_email_verified = 1,
				token = ''
				WHERE email = :email
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(array(':email' => $email));
			redirect("Your email has been verified! You can now log-in.");
		}
		else{
			redirect('zz');
		}
	}
?>