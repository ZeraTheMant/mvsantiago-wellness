<?php
	require '../dbconfig.php';

	if($_POST['action'] == 'insert'){
		$query = "
			INSERT INTO services
			(
				category_id,
				service_name,
				duration,
				service_description,
				appears_on_first_time,
				price
			)
			VALUES
			(
				:category_id,
				:service_name,
				:duration,
				:service_description,
				:appears_on_first_time,
				:price
			)
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':category_id' => $_POST['category_id'],
				':service_name' => $_POST['service_name'],
				':duration' => json_encode($_POST['duration']),
				':service_description' => $_POST['service_description'],
				':appears_on_first_time' => $_POST['appears_on_first_time'],
				':price' => $_POST['price']
			)
		);
		if($stmt->rowCount() == 1){
			$query = "
				UPDATE
					service_images
				SET
					service_id = @@identity
				WHERE	
					service_id = :dummy_id
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':dummy_id' => $_POST['dummy_id']
				)
			);
			
			echo "New service added successfully.";
		}
	}
	else if($_POST['action'] == 'update'){
		$query = "
			UPDATE
				services
			SET
				category_id = :category_id,
				service_name = :service_name,
				duration = :duration,
				service_description = :service_description,
				appears_on_first_time = :appears_on_first_time,
				price = :price
			WHERE
				service_id = :service_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':category_id' => $_POST['category_id'],
				':service_name' => $_POST['service_name'],
				':duration' => json_encode($_POST['duration']),
				':service_description' => $_POST['service_description'],
				':service_id' => $_POST['service_id'],
				':appears_on_first_time' => $_POST['appears_on_first_time'],
				':price' => $_POST['price']
			)
		);
		if($stmt->rowCount() == 1){
			if($_POST['appears_on_first_time'] == "1"){
				$query = "
					DELETE FROM
						customer_services
					WHERE
						service_id = :service_id
				";
				$stmt = $conn->prepare($query);
				$stmt->execute(
					array(
						':service_id' => $_POST['service_id']
					)
				);
			}
			
			echo "Service information updated successfully.";
		}
	}
	else{
		$query = "DELETE FROM service_images WHERE service_id = :service_id";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':service_id' => $_POST['service_id']
			)
		);
		
		$query = "DELETE FROM services WHERE service_id = :service_id";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':service_id' => $_POST['service_id']
			)
		);
		if($stmt->rowCount() == 1){
			echo "Service deleted successfully.";
		}
	}
?>