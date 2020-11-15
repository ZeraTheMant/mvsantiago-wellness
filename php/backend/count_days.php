<?php
	require '../dbconfig.php';
		
	$query = "
        SELECT start_datetime as sbs FROM appointments WHERE is_finished = 0 AND is_cancelled = 0
	";
	/*		AND
			start_datetime
		LIKE
			'" . substr($_GET['start_datetime'], 0, 10) . "%'
	*/
	$stmt = $conn->prepare($query);
	$stmt->execute();
    $table = $stmt->fetchAll();
    
    $days_with_apps = array();
	
	foreach($table as $row){
	    array_push($days_with_apps, substr($row['sbs'], 0, 10));
	}
	
	$query = "
		SELECT
			max_num_of_online_appointments_per_day
		FROM
			working_plan
		LIMIT 1
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$result = $stmt->fetch();
	$max_num_of_online_appointments_per_day = intval($result['max_num_of_online_appointments_per_day']);
	
	$occurences = array_count_values($days_with_apps);
	$closed_days = array();

    foreach($occurences as $key => $val){
        if(intval($val) >= $max_num_of_online_appointments_per_day){
            array_push($closed_days, $key);
        }
    }
	
	echo json_encode($closed_days);
?>