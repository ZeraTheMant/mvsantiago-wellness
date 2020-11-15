<?php
	require '../dbconfig.php';
	
	$query = "
		SELECT 
			category.category_id
		FROM
			appointments
		INNER JOIN
			services
		ON
			appointments.service_id = services.service_id
		INNER JOIN
			category
		ON
			services.category_id = category.category_id
		WHERE
			appointments.client_id = :client_id
	";
	
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':client_id' => $_GET['user_id']
		)
	);
	
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	echo json_encode($result);
?>