<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$query = "
		SELECT
			appointment_id
		FROM
			appointments
		WHERE
			client_id = :client_id
		LIMIT 1
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':client_id' => $_GET['client_id']
		)
	);
	
	$query = "
		SELECT 
			provider_services.ps_id, 
			services.service_id, 
			services.service_name, 
			services.duration, 
			services.category_id, 
			services.appears_on_first_time,
			providers_beta.provider_id, 
			providers_beta.provider_level, 
			providers_beta.days_worked, 
			category.category_id, 
			category.category_name,
			clients.client_id,
			clients.fname,
			clients.lname,
			clients.mname,
			clients.name_ext
		FROM 
			provider_services
		INNER JOIN 
			services 
		ON 
			provider_services.service_id = services.service_id 
		INNER JOIN providers_beta 
		ON
			provider_services.provider_id = providers_beta.provider_id 
		INNER JOIN 
			category 
		ON
			provider_services.category_id = category.category_id
		INNER JOIN
			clients
		ON
			providers_beta.client_id = clients.client_id
		";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	$provider_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	echo json_encode($provider_categories);	
?>