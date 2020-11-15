<?php
	require '../dbconfig.php';

	$query = "
		UPDATE
			services
		SET
			category_id = 0
		WHERE
			service_id = :service_id
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':service_id' => $_POST['service_id']
		)
	);
?>