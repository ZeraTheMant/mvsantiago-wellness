<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$query = "
		SELECT 
			services.service_id, 
			services.service_name, 
			services.duration, 
			services.category_id, 
			category.category_name 
		FROM 
			services 
		INNER JOIN 
			category 
		ON 
			services.category_id = category.category_id
		WHERE
			services.category_id = :category_id
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':category_id' => $_GET['category_id']
		)
	);
	
	$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	echo json_encode($services);
?>