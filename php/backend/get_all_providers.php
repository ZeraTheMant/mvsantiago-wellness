<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();
	//$output = '<option selected>All providers</option>';
	
	$query = "
		SELECT
			providers_beta.*,
			clients.fname,
			clients.mname,
			clients.lname,
			clients.name_ext
		FROM
			providers_beta
		INNER JOIN
			clients
		ON
			providers_beta.client_id = clients.client_id
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	if($stmt->rowCount() > 0){
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		/*foreach($result as $row){
			$provider_level = ''; 
			if(strtolower($row['provider_level']) == 'doctor' || strtolower($row['provider_level']) == 'dentist'){
				$provider_level = 'Dr.'; 
			}
			
			$output .= '
				<option id="' . $row['provider_id'] . '">'. $provider_level . ' ' . $row['provider_fname'] . ' ' . ucfirst($row['provider_mname'][0]) . '. ' . $row['provider_lname'] . ' ' . $row['provider_name_ext'] . '</option>
			';
		}*/
		
		echo json_encode($result);
	}
?>