<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	$query = "
		SELECT 
			*
		FROM 
			service_images 
		WHERE 
			service_images.service_id = :service_id
		ORDER BY 
			display_order DESC
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':service_id' => $_GET['service_id']
		)
	);
	
		
	$output = '
		<div class="imgbanbtn imgbanbtn-prev" id="imgbanbtn-prev"></div>
		<div class="imgbanbtn imgbanbtn-next" id="imgbanbtn-next"></div>
	';

	if($stmt->rowCount() > 0){

		$result = $stmt->fetchAll();
		
		foreach($result as $row){
			$output .= '
				<div class="imgban" id="imgban' . $row['display_order'] . '" style="background-image: url(' . $row['link'] . '); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
			';
		}
		
		$query = "
			SELECT 
				provider_services.provider_id,
				providers_beta.provider_level,
				clients.fname,
				clients.mname,
				clients.lname,
				clients.name_ext,
				clients.user_img
			FROM
				provider_services
			INNER JOIN
				providers_beta
			ON
				providers_beta.provider_id = provider_services.provider_id
			INNER JOIN
				clients
			ON
				providers_beta.client_id = clients.client_id
			WHERE
				provider_services.service_id = :service_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':service_id' => $_GET['service_id']
			)
		);
		
		$output2 = '';
		//
		if($stmt->rowCount() > 0){
			$result2 = $stmt->fetchAll();
			
			foreach($result2 as $row){
				$doctor_level = "";
				if($row['provider_level'] == "Doctor"){
					$doctor_level = "Dr. ";
				}
				
				$name = $row['name_ext'] . ' ' . $row['fname'] . ' ' . ucfirst($row['mname'][0]) . ' ' . $row['lname'];
				$output2 .= '
					<p>
						<img src="' . $row['user_img'] . '"/>
						<span>' . $name . '</span>
					</p>
				';
			}
		}
		else{
			$output2 = "No providers assigned for this service yet.";
		}
		
		$outputs = array(
			'images' => $output,
			'info' => $output2
		);

				
		echo json_encode($outputs);
	}
	else{
		echo '<p>No services found in database.</p>';
	}

?>