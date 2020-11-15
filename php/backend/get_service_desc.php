<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$query = "
		SELECT
			service_description
		FROM
			services
		WHERE
			service_id = :id
		LIMIT 1
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':id' => $_GET['id']
		)
	);
	
	$result = $stmt->fetch();
	
	echo $result['service_description'];

?>