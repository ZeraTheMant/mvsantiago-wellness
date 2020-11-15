<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	if($_POST['action'] == 'get'){
		$query = "
			SELECT * FROM contact_info LIMIT 1
		";
		

		$stmt = $conn->prepare($query);
		$stmt->execute();
		
		if($stmt->rowCount() > 0){
			$result = $stmt->fetch();	
			
			echo json_encode($result);
		}
	}
	else{
		$query = "
			SELECT * FROM contact_info LIMIT 1
		";
		

		$stmt = $conn->prepare($query);
		$stmt->execute();
		
		if($stmt->rowCount() == 1){
			$query = "
				UPDATE
					contact_info
				SET
					contact_number = :numbers,
					business_hours = :hours,
					contact_email = :email,
					contact_address = :location			
			";
			
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':numbers' => $_POST['numbers'],
					':hours' => $_POST['hours'],
					':email' => $_POST['email'],
					':location' => $_POST['location']
				)
			);
		}
		else{
			$query = "
				INSERT INTO
					contact_info
					(
						contact_number,
						business_hours,
						contact_email,
						contact_address
					)
				VALUES
					(
						:numbers,
						:hours,
						:email,
						:location
					)
			";
			
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':numbers' => $_POST['numbers'],
					':hours' => $_POST['hours'],
					':email' => $_POST['email'],
					':location' => $_POST['location']
				)
			);
		}
	}

?>