<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$column = $_GET['column'];
	$text = $_GET['text'];	
	$query = $_GET['query'];
	$selected_id = $_GET['selected_id'];
	
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$output = '<option selected disabled>---Choose a ' . $text . '---</option>';
	
	if($stmt->rowCount() > 0){
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$provider_level = ''; 
			if(strtolower($row['provider_level']) == 'doctor' || strtolower($row['provider_level']) == 'dentist'){
				$provider_level = 'Dr.'; 
			}

			$output .= '
				<option id="' . $row[$selected_id] . '">'. $provider_level . ' ' . $row['provider_fname'] . ' ' . ucfirst($row['provider_mname'][0]) . '. ' . $row['provider_lname'] . ' ' . $row['provider_name_ext'] . '</option>
			';
		}
	}
	
	echo $output;
?>