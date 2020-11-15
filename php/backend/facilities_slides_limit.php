<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	$query = "SELECT max_facilities_slides FROM working_plan LIMIT 1";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$result = $stmt->fetch();
	echo $result['max_facilities_slides'];

?>