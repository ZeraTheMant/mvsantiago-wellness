<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$query = "SELECT * FROM working_plan LIMIT 1";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	$plan = $stmt->fetch(PDO::FETCH_ASSOC);
	
	//$working_schedule = $plan['days_open'];
	//$closed_days = $plan['specified_closed_days'];
	//echo $working_schedule;
?>