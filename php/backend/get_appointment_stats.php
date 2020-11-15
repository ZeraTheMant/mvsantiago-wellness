<?php
	include "../dbconfig.php";
	
	$data = array(
		'January' => 0,
		'February' => 0,
		'March' => 0,
		'April' => 0,
		'May' => 0,
		'June' => 0,
		'July' => 0,
		'August' => 0,
		'September' => 0,
		'October' => 0,
		'November' => 0,
		'December' => 0,
	);
	
	$query = "
		SELECT
			start_datetime
		FROM
			appointments
		WHERE
			is_cancelled = 0
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	if($stmt->rowCount() > 0){
		$result = $stmt->fetchAll();
		
		foreach($result as $row){
			$date = new DateTime($row['start_datetime']);
			$data[$date->format('F')] += 1;
		}
	}
	
	echo json_encode($data);
?>