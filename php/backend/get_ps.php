<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	$query = "
		SELECT
			*
		FROM
			provider_services
		WHERE
			provider_id = :provider_id
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':provider_id' => $_GET['provider_id']
		)
	);
	
	if($stmt->rowCount() > 0){
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($result);
	}
?>