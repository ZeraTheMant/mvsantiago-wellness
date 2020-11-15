<?php
	require '../dbconfig.php';

	$query = "SELECT testimonial_id FROM testimonials WHERE client_id = :client_id LIMIT 1";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':client_id' => $_GET['user_id']
		)
	);
	
	if($stmt->rowCount() > 0){
		echo true;
	}
	else{
		echo false;
	}
?>