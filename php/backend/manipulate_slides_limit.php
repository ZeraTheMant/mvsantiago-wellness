<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	if($_POST['caller'] == 'GET'){
		$query = "SELECT max_showcase_limit FROM working_plan LIMIT 1";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetch();
		echo $result['max_showcase_limit'];
	}

?>