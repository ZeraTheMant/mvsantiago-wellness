<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	$query = "
		SELECT 
			provider_services.ps_id, 
			services.service_id, 
			services.service_name, 
			services.category_id,
			providers.provider_id, 
			providers.provider_fname, 
			providers.provider_lname, 
			providers.provider_mname, 
			providers.provider_name_ext, 
			providers.provider_level,
			providers.days_worked,
			category.category_id, 
			category.category_name 
		FROM 
			provider_services 
		INNER JOIN 
			services
		ON 
			provider_services.service_id = services.service_id 
		INNER JOIN 
			providers 
		ON 
			provider_services.provider_id = providers.provider_id 
		INNER JOIN 
			category 
		ON 
			provider_services.category_id = category.category_id 
		WHERE 
			provider_services.provider_id = :provider_id";
			
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':provider_id' => $_GET['provider_id']
		)
	);

	$provider_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	echo json_encode($provider_categories);
?>