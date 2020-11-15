<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$query = "
		UPDATE
			clients
		SET
			user_level = :user_level
		WHERE
			client_id = :client_id
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':user_level' => $_POST['user_level'],
			':client_id' => $_POST['client_id']
		)
	);
	
	echo $stmt->rowCount();

?>