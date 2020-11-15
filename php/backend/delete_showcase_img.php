<?php
	require '../dbconfig.php';
	
	$query = "
		DELETE FROM
			showcase_images
		WHERE 
			showcase_id = :showcase_id
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':showcase_id' => $_POST['img_id']
		)
	);
?>