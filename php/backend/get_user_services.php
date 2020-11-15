<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$query = "
		SELECT 
			customer_services.*,
			services.service_name,
			category.category_name
		FROM 
			customer_services
		INNER JOIN
			services
		ON
			services.service_id = customer_services.service_id
		INNER JOIN
			category
		ON
			services.category_id = category.category_id
		WHERE
			client_id = :client_id
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':client_id' => $_GET['client_id']
		)
	);
	
	$all_services = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	echo json_encode($all_services);
?>