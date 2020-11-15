<?php
	require '../dbconfig.php';
	
	$query = "
		SELECT * FROM about_page_text
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	if($stmt->rowCount() > 0){
		$query = "
			UPDATE about_page_text
			SET about_main_text = :aboutHeader,
				vision_text = :vision,
				mission_text = :mission
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':aboutHeader' => $_POST['aboutHeader'],
				':mission' => $_POST['mission'],
				':vision' => $_POST['vision']
			)
		);
		
		if($stmt->rowCount() > 0){
			echo "About us page contents successfully updated.";
		}
	}
	else{
		$query = "
			INSERT INTO
				about_page_text
				(
					about_main_text,
					mission_text,
					vision_text
				)
				VALUES
				(
					:aboutHeader,
					:mission,
					:vision
				)
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':aboutHeader' => $_POST['aboutHeader'],
				':mission' => $_POST['mission'],
				':vision' => $_POST['vision']
			)
		);
		
		if($stmt->rowCount() > 0){
			echo "About us page contents successfully updated.";
		}
	}
?>