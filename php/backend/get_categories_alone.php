<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	$query = "
		SELECT 
			category_name,
			category_id
		FROM 
			category 
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	$provider_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	echo json_encode($provider_categories);

?>