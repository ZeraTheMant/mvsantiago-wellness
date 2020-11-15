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
	
	$output = '';
	if($stmt->rowCount() > 0){
		$result = $stmt->fetchAll();
		
		$output .= '
			<table>
				<tr>
					<th>ID</th>
					<th>Category</th>
					<th>Procedure</th>
					<th>Provider</th>
					<th>Start</th>
					<th>End</th>
				</tr>

		';
		
		foreach($result as $row){
			$provider_ext = "";
			if($row['provider_level'] == 'Doctor' || $row['provider_level'] == 'Dentist'){
				$provider_ext = "Dr. ";
			}
			$provider = $provider_ext . $row['prov_fname'] . ' ' . $row['prov_mname'] . ' ' . $row['prov_lname'] . ' ' . $row['prov_name_ext'];
			
			$output .= '
				<tr class="actual-row">
					<td>'. $row['id_code'] . '</td>
					<td>' . $row['category_name'] . '</td>
					<td>' . $row['service_name'] . '</td>
					<td>' . $provider . '</td>
					<td>' . $row['start_datetime'] . '</td>
					<td>' . $row['end_datetime'] . '</td>
				</tr>
			';
		}
		
		$output .= '</table>';
		echo $output;
	}
	else{
		echo '<p>No appointments found in database.</p>';
	}
?>