<?php
	include '../dbconfig.php';

	$query = "SELECT days_open, specified_closed_days FROM working_plan LIMIT 1";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $row){
		$working_schedule =  $row['days_open'];
		$holiday_array = $row['specified_closed_days'];

	}
	$working_schedule = json_encode($working_schedule);
	$holiday_array = json_encode($holiday_array);

?>