<?php
	include '../dbconfig.php';
	
	$query = "SELECT * FROM outside_links LIMIT 1";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$fp_data = array();
	
	if($stmt->rowCount() > 0){
		$result = $stmt->fetch();
		
		$home_info = $result['home_info'];
		$fb_link = $result['fb_link'];
		$ig_link = $result['ig_link'];

		
		$fp_data['home_info'] = $result['home_info'];
		$fp_data['fb_link'] = $result['fb_link'];
		$fp_data['ig_link'] = $result['ig_link'];
		
		if(isset($_GET['caller'])){
			echo json_encode($fp_data);
		}	
	}
?>