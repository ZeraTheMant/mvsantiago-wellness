<?php
	require '../dbconfig.php';
	
	function getPendingAppointmentsOfUser($conn){
		$user_appointments = "";
					
		$query = "
			SELECT
				appointments.client_id,
				appointments.start_datetime,
				appointments.end_datetime,
				appointments.is_finished,
				appointments.is_cancelled,
				appointments.has_sent_day_of_notif_msg,
				appointments.appointment_id,
				appointments.is_confirmed,
				appointments.book_to_start_days_diff,
				appointments.week_of_date,
				appointments.book_datetime,
				clients.contact_number
			FROM
				appointments
			INNER JOIN
				clients
			ON	
				appointments.client_id = clients.client_id
			WHERE
				is_finished = 0
			AND
				is_cancelled = 0
		";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		
		if($stmt->rowCount() > 0){
			$result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
			$user_appointments = json_encode($result2);	
		}
		
		return $user_appointments;
	}
?>