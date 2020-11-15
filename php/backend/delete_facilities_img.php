<?php
	require '../dbconfig.php';
	
	$query = "
		DELETE FROM
			facilities_images
		WHERE 
			fi_id = :img_id
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':img_id' => $_POST['img_id']
		)
	);
?>