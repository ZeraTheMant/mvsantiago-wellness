<?php
	require '../dbconfig.php';
	
	if(empty($_POST['oldPass']) && empty($_POST['newPass']) && empty($_POST['newPassConfirm'])){
		$query = "
			UPDATE 
				clients 
			SET 
				email = :email, 
				contact_number = :contact, 
				address = :address, 
				user_image = :img 
			WHERE 
				client_id = :client_id";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':email' => $_POST['user-email'],
				':contact' => $_POST['user-contact'],
				':address' => $_POST['user-address'],
				':img' => $_POST['user-img'],
				':client_id' => $_POST['client_id']
			)
		);
		$updatedNoPass = $stmt->rowCount();
	}
	else{
		$query = "SELECT account_password FROM clients WHERE client_id = :client_id";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':client_id' => $_POST['client_id']
			)
		);
		
		if($stmt->rowCount() == 1){
			$result = $stmt->fetch();
			
			$hashedPwdCheck = password_verify($_POST['oldPass'], $result['password']);
			if(!$hashedPwdCheck){
				echo 'Your old password is incorrect.';
				exit();
			}
			else{
				if(!preg_match("/^[a-zA-Z\d]+$/", $_POST['newPass']) || !preg_match("/^[a-zA-Z\d]+$/", $_POST['newPassConfirm'])){
					echo 'Your new password contains invalid characters. Please try a new one';
					exit();
				}
				else{
					$hashedPwdCheck = password_verify($_POST['newPass'], $result['password']);
					if($hashedPwdCheck){
						echo 'You new password must be different from the old one.';
						exit();
					}
					else{
						$hashedPwd = password_hash($_POST['newPass'], PASSWORD_DEFAULT);
						$query = "
							UPDATE 
								clients 
							SET 
								account_password = :password, 
								email = :email, 
								contact_number = :contact, 
								address = :address, 
								user_image = :img 
							WHERE 
								client_id = :client_id";
						$stmt = $conn->prepare($query);
						$stmt->execute(
							array(
								':password' => $hashedPwd,
								':email' => $_POST['user-email'],
								':contact' => $_POST['user-contact'],
								':address' => $_POST['user-address'],
								':img' => $_POST['user-img'],
								':client_id' => $_POST['client_id']
							)
						);
						$updatedNoPass = $stmt->rowCount();
					}
				}
			}
		}
	}
	
	$query = "SELECT id, username, email, contact_no, fname, lname, middle_initial, dob, gender, address FROM users WHERE id = :id";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':id' => $_POST['user-id']
		)
	);
	
	if($stmt->rowCount() == 1){
		$result = $stmt->fetch();
	}
	
	echo $updatedNoPass;
?>