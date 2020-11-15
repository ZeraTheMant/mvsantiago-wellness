<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$column = $_GET['column'];
	$text = $_GET['text'];	
	$query = $_GET['query'];
	$selected_id = $_GET['selected_id'];
	
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$output = '<option selected disabled>---Choose a ' . $text . '---</option>';
	
	if($stmt->rowCount() > 0){
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$output .= '
				<option id="' . $row[$selected_id] . '">'. $row[$column] . '</option>
			';
		}
	}
	
	echo $output;
?>