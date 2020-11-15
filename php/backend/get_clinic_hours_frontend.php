<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$query = "SELECT * FROM working_plan LIMIT 1";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	$plan = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if($_GET['action'] == 'front_end'){
		$query = "SELECT days_worked FROM providers_beta WHERE provider_id = :provider_id";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':provider_id' => $_GET['provider_id']
			)
		);
		
		$days_worked = $stmt->fetch();
		
		$plan['days_worked'] =  $days_worked['days_worked'];	
	}

	
	echo json_encode($plan);
?>