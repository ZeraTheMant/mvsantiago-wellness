<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$query = "
		UPDATE
			working_plan
		SET
			max_facilities_slides = :max_facilities_slides
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':max_facilities_slides' => $_POST['slider_limit']
		)
	);
?>