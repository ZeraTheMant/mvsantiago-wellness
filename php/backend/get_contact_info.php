<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	$query = "
		SELECT * FROM contact_info LIMIT 1
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	$data = array();
	
	
	$result = $stmt->fetch();
	
	$data['contact_number'] = utf8_encode($result['contact_number']);
	$data['business_hours'] = utf8_encode($result['business_hours']);
	$data['contact_email'] = utf8_encode($result['contact_email']);
	$data['contact_address'] = utf8_encode($result['contact_address']);

	echo json_encode($data);

?>