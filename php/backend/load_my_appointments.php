<?php
	include '../dbconfig.php';

	function getMyAppointments($id, $conn, $column){
		$query = "
			SELECT	
				appointments.start_datetime,
				services.service_name
			FROM
				appointments
			INNER JOIN
				services
			ON	
				appointments.service_id = services.service_id
			WHERE
				appointments." . $column . " = :id
			AND
				appointments.is_finished = 0
			AND
				appointments.is_cancelled = 0
			ORDER BY
				start_datetime DESC
			LIMIT 10
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':id' => $id
			)	
		);
		
		$output = '
			<table style="font-size: 0.8rem;">
		';
		
		if($stmt->rowCount() > 0){
			$result = $stmt->fetchAll();
			
			foreach($result as $row){
				$date = new DateTime($row['start_datetime'], new DateTimeZone('Asia/Manila'));
				$output .= '
					<tr>
						<td style="background-color: green; color: white; padding: 3px;">' . $date->format("d M y h:i A") . '</td>
						<td style="background-color: white; padding: 3px;">' . $row['service_name'] . '</td>
					</tr>
				';
			}
			
			$output .= '</table>';
			return $output;
		}
		else{
			return "<p>No appointments found.</p>";
		}
	}
	
	$query = "
		SELECT 
			provider_id
		FROM
			providers_beta
		WHERE
			client_id = :client_id
		LIMIT 1
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':client_id' => $_GET['client_id']
		)
	);
	
	if($stmt->rowCount() > 0){
		$result = $stmt->fetch();
		$provider_id = $result['provider_id'];

		
		echo getMyAppointments($provider_id, $conn, 'provider_id');	
	}
	else{
		echo getMyAppointments($_GET['client_id'], $conn, 'client_id');
	}

?>