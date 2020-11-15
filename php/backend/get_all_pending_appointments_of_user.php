<?php
	require '../dbconfig.php';
	
	$query = "
		SELECT
			client_id,
			start_datetime,
			end_datetime,
			is_finished,
			is_cancelled,
			has_sent_day_of_notif_msg,
			appointment_id
		FROM
			appointments
		WHERE 
			client_id = :client_id
		AND
			is_finished = 0
		AND
			is_cancelled = 0
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':client_id' => $_GET['id']
		)
	);
	
	if($stmt->rowCount() > 0){
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		echo json_encode($result);	
	}
	else{
		echo "";
	}
?>