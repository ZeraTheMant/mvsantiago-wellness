<?php
	require '../dbconfig.php';
	
	$data = array();

	$query = "
		SELECT 
			appointments.*,
			services.service_name,
			services.duration,
			category.category_name,
			customer.lname,
			customer.mname,
			customer.fname,
			providers_beta.provider_level,
			clients.lname as prov_lname,
			clients.mname as prov_mname,
			clients.fname as prov_fname,
			clients.name_ext as prov_name_ext,
			providers_beta.days_worked
		FROM
			appointments
		INNER JOIN
			services
		ON
			appointments.service_id = services.service_id
		INNER JOIN
			providers_beta
		ON
			appointments.provider_id = providers_beta.provider_id
		INNER JOIN 
			category
		ON
			category.category_id = services.category_id
		INNER JOIN 
			clients
		ON
			providers_beta.client_id = clients.client_id
		INNER JOIN 
			clients as customer
		ON
			appointments.client_id = customer.client_id
		WHERE
			is_finished = :is_finished
		AND
			is_cancelled = :is_cancelled
		AND	
			appointments.client_id = :client_id
	";
	
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':is_finished' => $_GET['is_finished'],
			':is_cancelled' => $_GET['is_cancelled'],
			':client_id' => $_GET['client_id']
		)
	);
	
	$result = $stmt->fetchAll();
	
	foreach($result as $row){
		$provider_ext = "";
		if($row['provider_level'] == 'Doctor' || $row['provider_level'] == 'Dentist'){
			$provider_ext = "Dr. ";
		}
		
		$color = "#ff0000";
		if($row['is_confirmed'] == "1"){
			$color = "#006400";
		}
		
		$data[] = array(
			'title' => $row['service_name'],
			'id_code' => $row['id_code'],
			'appointment_id' => $row['appointment_id'],
			'service_id' => $row['service_id'],
			'provider_id' => $row['provider_id'],
			'client_id' => $row['client_id'],
			'start' => $row['start_datetime'],
			'end' => $row['end_datetime'],
			'category' => $row['category_name'],
			'provider' => $provider_ext . $row['prov_fname'] . ' ' . $row['prov_mname'] . ' ' . $row['prov_lname'] . ' ' . $row['prov_name_ext'],
			'client' => $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'],
			'resourceId' =>  $row['provider_id'],
			'allDay' => false,
			'days_worked' => $row['days_worked'],
			'service_duration' => $row['duration'],
			'color' => $color
		);
	}
	
	echo json_encode($data);
?>