<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	$query = "
		SELECT 
			services.service_name,
			services.service_id,
			category.category_name,
			clients.fname,
			clients.mname,
			clients.lname,
			clients.name_ext,
			providers_beta.provider_level
		FROM
			appointments
		INNER JOIN
			services
		ON
			appointments.service_id = services.service_id
		INNER JOIN
			category
		ON
			services.category_id = category.category_id
		INNER JOIN
			providers_beta
		ON
			appointments.provider_id = providers_beta.provider_id
		INNER JOIN 
			clients
		ON
			clients.client_id = providers_beta.client_id
		WHERE
			appointments.client_id = :client_id;
	";
			
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':client_id' => $_GET['client_id']
		)
	);
	
	if($stmt->rowCount() > 0){
		$output = '
			<h3>List of my Categories and Services</h3>
			<p id="instruction">Displays all the categories and services that you have availed.</p>
			<br>
			<div id="provider-categories-services-container">
				<table>
					<thead>
						<tr>
							<th>Category</th>
							<th>Service</th>
							<th>Provider name</th>
						</tr>
					</thead>
					<tbody>
		';
		$provider_categories = $stmt->fetchAll();
		
		foreach($provider_categories as $row){
			$isDoctor = "";
			if($row['provider_level'] == "Doctor"){
				$isDoctor = "Dr. ";
			}
			$name_initial = "";
			if($row['name_ext'] != ""){
				$name_initial = $row['name_ext'];
			}
			
			$name = $isDoctor . '' . $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'] . ' ' . $name_initial;
			
			$output .='
				<tr class="actual-row">
					<td>' . $row['category_name'] . '</td>
					<td>' . $row['service_name'] . '</td>
					<td>' . $name . '</td>
				</tr>
			';
		}
		
		$output .='
			</tbody></table>
		';
		
		
		$output .= "</div>";
		
		echo $output;
	}
	else{
		echo "<p>No categories and services found for this provider.</p>";
	}
?>