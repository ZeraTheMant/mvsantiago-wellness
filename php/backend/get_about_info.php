<?php
	require '../dbconfig.php';
	
	$query = "SELECT * FROM about_page_text LIMIT 1";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	$data = array();
	
	$result = $stmt->fetch();
	
	$data['about_main_text'] = $result['about_main_text'];
	$data['vision_text'] = $result['vision_text'];
	$data['mission_text'] = $result['mission_text'];
	
	echo json_encode($data);
?>