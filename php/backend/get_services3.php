<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$query = "
		SELECT
			service_name,
			service_id
		FROM
			services
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	echo json_encode($result);

?>