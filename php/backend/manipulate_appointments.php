<?php
	require '../dbconfig.php';
	require 'email_sender.php';
	require 'insert_to_activity_feed.php';
	require 'report_function.php';
	require 'itexmo.php';

	
	if($_POST['action'] == 'insert'){
		$query = "
			SELECT
				start_datetime
			FROM
				appointments
			WHERE 
				start_datetime
			LIKE
				'" . substr($_POST['start_datetime'], 0, 10) . "%'
		";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		
		if($stmt->rowCount() < 5){
			$query = "
				INSERT INTO
					appointments
				(
					service_id,
					provider_id,
					client_id,
					book_datetime,
					start_datetime,
					end_datetime,
					is_finished,
					is_cancelled,
					has_sent_day_of_notif_msg,
					is_confirmed,
					week_of_date,
					book_to_start_days_diff
				)
				VALUES
				(
					:service_id,
					:provider_id,
					:client_id,
					:book_datetime,
					:start_datetime,
					:end_datetime,
					:is_finished,
					:is_cancelled,
					:has_sent_day_of_notif_msg,
					0,
					:week_of_date,
					:book_to_start_days_diff
				)
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':service_id' => $_POST['service_id'],
					':provider_id' => $_POST['provider_id'],
					':client_id' => $_POST['client_id'],
					':book_datetime' => $_POST['book_datetime'],
					':start_datetime' => $_POST['start_datetime'],
					':end_datetime' => $_POST['end_datetime'],
					':is_finished' => $_POST['is_finished'],
					':is_cancelled' => $_POST['is_cancelled'],
					':has_sent_day_of_notif_msg' => $_POST['has_sent_day_of_notif_msg'],
					':week_of_date' => $_POST['book_datetime'],
					':book_to_start_days_diff' => $_POST['book_to_start_days_diff']
				)
			);
			if($stmt->rowCount() == 1){
				$query = "
					SELECT appointment_id FROM appointments WHERE appointment_id = @@identity LIMIT 1
				";
				$stmt = $conn->prepare($query);
				$stmt->execute();
				$result = $stmt->fetch();
				
				$expiry = "
					The appointment will be automatically cancelled if you fail to confirm your appointment within 1 day of booking.
				";
				if($_POST['book_to_start_days_diff'] > "2"){
					$expiry = "
						The appointment will be automatically cancelled if you fail to confirm your appointment within 3 days of booking.
					";
				}
				$link = '<a href="http://mvsantiagowellness.com/confirm_appointment.php?app_id=' . $result['appointment_id'] . '&category=' . $_POST['categoryName'] . '&service=' . $_POST['serviceName'] . '&provider=' . $_POST['providerName'] . '&client=' . $_POST['fullName'] . '&end=' . $_POST['end_datetime'] . '&start=' .  $_POST['start_datetime'] . '&email=' . $_POST['emailAdd'] . '&contact=' . $_POST['contact'] . '">here</a>.';
				sendEmailMessage(
					"Appointment Booking Confirmation required",
					"Hello " . $_POST['fullName'] .".<br><br>Your appointment requires confirmation before it is officially booked.<br><br>
					To confirm your appointment, click " . $link . " " . $expiry . "<br><br>
					The provider is " . $_POST['providerName'] . " and the procedure is " . $_POST['serviceName'] . " of category " . $_POST['categoryName'] . ".<br>
					The appointment begins at " . $_POST['start_datetime'] ." and ends at " . $_POST['end_datetime'] . ".<br>
					The appointment was booked on " . $_POST['book_datetime'] . "",
					$_POST['emailAdd']
				);
				
				insertToActivityFeed($conn, $_POST['client_id'], $_POST['provider_id'], $_POST['book_datetime'], "booked an appointment.");
				itexmo($_POST['contact'], "Your appointment for " . $_POST['start_datetime'] . " needs confirmation. Confirm it in your email inbox.", 'TR-M.V.S269193_HBLWE');
				
				$query = "
					UPDATE
						appointments
					SET	
						id_code = :id_code
					WHERE
						appointment_id = :appointment_id
				";
				$stmt = $conn->prepare($query);
				$stmt->execute(
					array(
						':id_code' => 'MVSAN_APP_' . $result['appointment_id'],
						':appointment_id' => $result['appointment_id']
					)
				);
				
				echo "Appointment status still unconfirmed. Please click the link on the confirmation message sent to your email address to confirm your appointment.\n\nUnconfirmed appointments will be deleted after three days.";
			}
		}
		else{
			echo "Only 5 online appointments per day are allowed. Please book at a later date.";
		}
	}
	else if($_POST['action'] == 'update'){
		$query = "
			UPDATE
				appointments
			SET
				book_datetime = :book_datetime,
				start_datetime = :start_datetime,
				end_datetime = :end_datetime,
				book_to_start_days_diff = :book_to_start_days_diff,
				is_confirmed = 0
			WHERE
				appointment_id = :appointment_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':book_datetime' => $_POST['book_datetime'],
				':start_datetime' => $_POST['start_datetime'],
				':end_datetime' => $_POST['end_datetime'],
				':appointment_id' => $_POST['appointment_id'],
				':book_to_start_days_diff' => $_POST['book_to_start_days_diff']
			)
		);
		if($stmt->rowCount() == 1){
			
			$expiry = "
				The appointment will be automatically cancelled if you fail to confirm your appointment within 1 day of booking.
			";
			if($_POST['book_to_start_days_diff'] > "2"){
				$expiry = "
					The appointment will be automatically cancelled if you fail to confirm your appointment within 3 days of booking.
				";
			}
			$link = '<a href="http://mvsantiagowellness.com/confirm_appointment.php?app_id=' . $_POST['appointment_id'] . '&category=' . $_POST['categoryName'] . '&service=' . $_POST['serviceName'] . '&provider=' . $_POST['providerName'] . '&client=' . $_POST['fullName'] . '&end=' . $_POST['end_datetime'] . '&start=' .  $_POST['start_datetime'] . '&email=' . $_POST['emailAdd'] . '&contact=' . $_POST['contact'] . '">here</a>.';
			
			sendEmailMessage(
				"Appointment Rescheduling Confirmation required",
				"Hello " . $_POST['fullName'] .".<br><br>Your appointment requires confirmation before it is officially rescheduled.<br><br>
				To confirm your appointment, click " . $link . " " . $expiry . "<br><br>
				The provider is " . $_POST['providerName'] . " and the procedure is " . $_POST['serviceName'] . " of category " . $_POST['categoryName'] . ".<br>
				The old appointment was from " . $_POST['old_start'] . " to " . $_POST['old_end'] . "<br>
				The new appointment begins at " . $_POST['start_datetime'] ." and ends at " . $_POST['end_datetime'] . ".<br>
				The appointment was re-booked on " . $_POST['book_datetime'] . "",
				$_POST['emailAdd']
			);
				
			insertToActivityFeed($conn, $_POST['client_id'], $_POST['provider_id'],  $_POST['book_datetime'], "rescheduled an appointment.");
			itexmo($_POST['contact'], "Your appointment for " . $_POST['start_datetime'] . " needs confirmation. Confirm it in your email inbox.", 'TR-M.V.S269193_HBLWE');
			
			echo "Appointment successfully rescheduled.";
		}
	}
	else{
		$query = "
			UPDATE
				appointments
			SET
				is_cancelled = 1,
				cancellation_reason = :cancellation_reason
			WHERE
				appointment_id = :appointment_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':cancellation_reason' => $_POST['cancellation_reason'],
				':appointment_id' => $_POST['appointment_id']
			)
		);
		if($stmt->rowCount() == 1){
			
			sendEmailMessage(
				"Appointment Successfully Cancelled",
				"Hello " . $_POST['fullName'] .".<br><br>Your appointment for " . $_POST['date'] . ", " . $_POST['start'] . " to " . $_POST['end'] . " of service " . $_POST['service'] . " with " . $_POST['provider'] . " has been successfully cancelled.<br><br>
				Please feel free to schedule another appointment when it suits your schedule.",
				$_POST['emailAdd']
			);
			
			insertToActivityFeed($conn, $_POST['client_id'], $_POST['provider_id'], $_POST['book_datetime'], "cancelled an appointment.");
			itexmo($_POST['contact'], "Your appointment for " . $_POST['start_datetime'] . " has been cancelled.", 'TR-M.V.S269193_HBLWE');
			$title = $_POST['date'] . " Appointment report for " . $_POST['client'];
			$link = "http://mvsantiagowellness.com/report.php?id=" . $_POST['appointment_id'] . "&start=" . $_POST['start'] . "&end=" . $_POST['end'] . "&reason=" . $_POST['cancellation_reason'] . "&date=" . $_POST['date'] . "&client=" . $_POST['client'] . "&provider=" . $_POST['provider'] . "&category=" . $_POST['category'] . "&service=" . $_POST['service'] . "";
		
			//insertReport($conn, $title, $link, $_POST['timestamp'], $_POST['client_id'], $_POST['provider_id'], $_POST['appointment_id']);
			
			echo "Appointment successfully cancelled.";
		}
	}
?>