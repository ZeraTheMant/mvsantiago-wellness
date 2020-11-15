<?php
	require '../dbconfig.php';
	
	$query = "
		SELECT
			start_datetime,
			end_datetime,
			provider_id
		FROM
			appointments
		WHERE 
			start_datetime
		LIKE
			'" . $_GET['date'] . "%'
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	echo json_encode($result);
?>