<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	$query = "
		SELECT 
			services.service_id, 
			services.service_name, 
			services.category_id,
			services.appears_on_first_time,
			category.category_id, 
			category.category_name 
		FROM 
			customer_services 
		INNER JOIN 
			services
		ON 
			customer_services.service_id = services.service_id 
		INNER JOIN 
			category 
		ON 
			customer_services.category_id = category.category_id 
		WHERE 
			customer_services.client_id = :client_id";
			
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':client_id' => $_GET['client_id']
		)
	);

	$provider_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	echo json_encode($provider_categories);
?>