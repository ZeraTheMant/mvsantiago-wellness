<?php
    session_start();
	require 'php/dbconfig.php';
	require 'php/backend/email_sender.php';
	require 'php/backend/itexmo.php';

	function redirect($msg, $link){
		echo '
			<script type="text/javascript">
				alert("'. $msg .'");
				window.location = "' . $link . '";
			</script>
		';
		//header('Location: https://jarindentalclinic.com');
		exit();
	}
	
	if(!isset($_GET['app_id'])){
		redirect("No appointment ID given.", "http://mvsantiagowellness.com/");
	}
	else{
		$app_id = $_GET['app_id'];
		
		$query = "
			SELECT appointment_id FROM appointments 
			WHERE is_confirmed = 0 
			AND appointment_id = :app_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':app_id' => $app_id
			)
		);
		
		if($stmt->rowCount() > 0){
			$query = "
				UPDATE appointments
				SET is_confirmed = 1
				WHERE appointment_id = :app_id
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(array(':app_id' => $app_id));
			
			sendEmailMessage(
				"Appointment Successfully Confirmed",
				"Hello " . $_GET['client'] .".<br><br>Your appointment has been successfully confirmed.<br><br>
				The provider is " . $_GET['provider'] . " and the procedure is " . $_GET['service'] . " of category " . $_GET['category'] . ".<br>
				The appointment begins at " . $_GET['start'] ." and ends at " . $_GET['end'] . ".<br>",
				$_GET['email']
			);
			
			itexmo($_GET['contact'], "Your appointment has been confirmed successfully.", 'TR-M.V.S269193_HBLWE ');
			
			if(isset($_SESSION['email'])){
    			redirect("Your appointment has now been confirmed.", "http://mvsantiagowellness.com/php/backend/backend_user_appointments.php");
			}
			else{
			   	redirect("Your appointment has now been confirmed.", "http://mvsantiagowellness.com/");
			}

		}
		else{
			redirect();
		}
	}
?>