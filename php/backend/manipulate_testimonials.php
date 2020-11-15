<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	if($_POST['action'] == 'approve'){
		$query = "
			UPDATE
				testimonials
			SET
				approval_status = 1
			WHERE
				testimonial_id = :testimonial_id
		";
	}
	else if($_POST['action'] == 'hide'){
		$query = "
			UPDATE
				testimonials
			SET
				approval_status = 0
			WHERE
				testimonial_id = :testimonial_id
		";
	}
	else{
		$query = "
			DELETE FROM
				testimonials
			WHERE
				testimonial_id = :testimonial_id
		";
	}
	
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':testimonial_id' => $_POST['testimonial_id']
		)
	);

?>