<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$query = "SELECT * FROM services";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	$all_services = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	echo json_encode($all_services);
?>