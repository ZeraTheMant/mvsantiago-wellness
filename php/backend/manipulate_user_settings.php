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
				user_img = :user_img
			WHERE
				client_id = :user_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':email' => $_POST['email'],
				':contact' => $_POST['contact'],
				':address' => $_POST['address'],
				':user_img' => $_POST['user_img'],
				':user_id' => $_POST['user_id']
			)
		);
		echoData($stmt->rowCount());
	}
	else{
		$query = "SELECT account_password FROM clients WHERE client_id = :client_id";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':client_id' => $_POST['user_id']
			)
		);
		
		if($stmt->rowCount() == 1){
			$result = $stmt->fetch();
			
			$hashedPwdCheck = password_verify($_POST['oldPass'], $result['account_password']);
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
					$hashedPwdCheck = password_verify($_POST['newPass'], $result['account_password']);
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
									email = :email,
									contact_number = :contact,
									address = :address,
									user_img = :user_img,
									account_password = :new_pass
								WHERE
									client_id = :user_id
							";
							$stmt = $conn->prepare($query);
							$stmt->execute(
								array(
									':email' => $_POST['email'],
									':contact' => $_POST['contact'],
									':address' => $_POST['address'],
									':user_img' => $_POST['user_img'],
									':new_pass' => $hashedPwd,
									':user_id' => $_POST['user_id']
								)
							);
							echoData($stmt->rowCount());
						}
					}
				}
			}
		}
	
	function echoData($row_count){
		if($row_count == 1){
			/*session_start();
			$_SESSION['client_id'] = $_POST['user_id'];
			$_SESSION['email'] = $_POST['email'];
			$_SESSION['contact_number'] = $_POST['contact'];
			$_SESSION['address'] = $_POST['address'];*/

		
			echo "User information updated successfully.";
		}
		else{
			echo "";
		}
	}
?>