<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$closed_days =  json_encode($_POST['closed_days']);
	$working_days = array(
		"Sunday" => $_POST['sunday'],
		"Monday" => $_POST['monday'],
		"Tuesday" => $_POST['tuesday'],
		"Wednesday" => $_POST['wednesday'],
		"Thursday" => $_POST['thursday'],
		"Friday" => $_POST['friday'],
		"Saturday" => $_POST['saturday']
	);
	
	$query = "SELECT * FROM working_plan";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	if($stmt->rowCount() > 0){
		$query = "UPDATE working_plan SET days_open = :days_open, specified_closed_days = :specified_closed_days, max_num_of_online_appointments_per_day = :max_num";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':days_open' => json_encode($working_days),
				':specified_closed_days' => $closed_days,
				':max_num' => $_POST['max_num']
			)
		);
	}
	else{
		$query = "INSERT INTO working_plan (days_open, specified_closed_days, max_num_of_online_appointments_per_day) VALUES (:days_open, :specified_closed_days, :max_num)";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':days_open' => json_encode($working_days),
				':specified_closed_days' => $closed_days,
				':max_num' => $_POST['max_num']
			)
		);
	}
?>