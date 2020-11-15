<?php
	require '../dbconfig.php';
	//require 'report_function.php';
	//$inipath = php_ini_loaded_file();

    $query = "
        UPDATE
            appointments
        SET
            remarks = :remarks
        WHERE
            appointment_id = :appointment_id
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute(
        array(
            ':remarks' => $_POST['remarks'],
            ':appointment_id' => $_POST['appointment_id']
        )  
    );

	/*if($_POST['action'] == 'insert'){
		$title = $_POST['date'] . " Appointment report for " . $_POST['client'];
		$link = "http://mvsantiagowellness.com/report.php?id=" . $_POST['appointment_id'] . "&end=" . $_POST['end'] . "&start=" . $_POST['start'] . "&date=" . $_POST['date'] . "&client=" . $_POST['client'] . "&provider=" . $_POST['provider'] . "&category=" . $_POST['category'] . "&service=" . $_POST['service'] . "&remarks=" . $_POST['remarks'] . "";
	
		insertReport($conn, $title, $link, $_POST['timestamp'], $_POST['client_id'], $_POST['provider_id'], 'appointment');
	}
	else if($_POST['action'] == 'hide'){
		$query = "
			UPDATE
				testimonials
			SET
				approval_status = 0
			WHERE
				testimonial_id = :testimonial_id
		";
	}
	else{
		$query = "
			DELETE FROM
				testimonials
			WHERE
				testimonial_id = :testimonial_id
		";
	}*/
?>