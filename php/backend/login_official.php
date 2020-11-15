<?php
	require '../dbconfig.php';
	require 'report_function.php';
	
	$errorMsg = '';		
	if(empty($_POST['email']) || empty($_POST['password'])){
		echo 'credentials';
		exit();
	}
	else{
		$query = $_POST['initial_query'];
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':email' => $_POST['email']
			)					
		);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if($stmt->rowCount() > 0){
			if($result['is_email_verified'] == 0){
				echo 'email';
				exit();
			}
			else{
				$hashedPwdCheck = password_verify($_POST['password'], $result['account_password']);
				if(!$hashedPwdCheck){
					echo 'credentials';
					exit();
				}
				else{					
					session_start();
					$_SESSION['client_id'] = $result['client_id'];
					$_SESSION['email'] = $result['email'];
					$_SESSION['contact_number'] = $result['contact_number'];
					$_SESSION['fname'] = $result['fname'];
					$_SESSION['lname'] = $result['lname'];
					$_SESSION['mname'] = $result['mname'];
					$_SESSION['dob'] = $result['dob'];
					$_SESSION['gender'] = $result['gender'];
					$_SESSION['address'] = $result['address'];
					$_SESSION['user_level'] = $result['user_level'];
					$_SESSION['user_img'] = $result['user_img'];
					
					if($result['user_level'] == 'provider'){
						$query = "
							SELECT
								provider_id
							FROM
								providers_beta
							WHERE
								client_id = :client_id
						";
						$stmt = $conn->prepare($query);
						$stmt->execute(
							array(
								':client_id' => $result['client_id']
							)
						);
						$result2 = $stmt->fetch();
						$_SESSION['provider_id'] = $result2["provider_id"];
					}
					
					echo $result['fname'] . ' ' . $result['mname'] . ' ' . $result['lname'];
					
					$date = new DateTime(null, new DateTimeZone('Asia/Manila'));
					
					//insertReport($conn, $result['fname'] . ' ' . $result['mname'] . ' ' . $result['lname'] . ' logged in.', '', $date->format('Y-m-d H:i:s'), $result['client_id'], 0, 'log');
					exit();
				}
			}
		}
		else{
			echo 'credentials';
			exit();
		}
	}
?>