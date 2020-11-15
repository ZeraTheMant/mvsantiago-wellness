<?php
	require '../dbconfig.php';

	
	if($_POST['action'] == "insert"){
		$query = "SELECT * FROM service_images WHERE link = :link";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':link' => $_POST['img_link']
			)
		);
		
		if($stmt->rowCount() > 0){
			echo false;
		}
		else{
			$query = "
				INSERT INTO 
					service_images
					(
						service_id,
						display_order,
						link
					)
					VALUES
					(
						:service_id,
						:display_order,
						:link
					)
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':service_id' => $_POST['service_id'],
					':display_order' => $_POST['display_order'],
					':link' => $_POST['img_link']
				)
			);
			
			if($stmt->rowCount() > 0){
				echo true;
			}
			else{
				echo false;
			}
		}	
	}
	else if($_POST['action'] == "update"){
		$query = "
			UPDATE
				service_images
			SET
				display_order = :display_order,
				link = :link
			WHERE
				si_id = :si_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(			
				':display_order' => $_POST['display_order'],
				':link' => $_POST['img_link'],
				':si_id' => $_POST['si_id']
			)
		);
		echo 'we';
	}
	else{
		$query = "
			DELETE FROM
				service_images
			WHERE
				si_id = :si_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(			
				':si_id' => $_POST['si_id']
			)
		);
	}

?>