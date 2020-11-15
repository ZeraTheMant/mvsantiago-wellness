<?php
	require '../dbconfig.php';
	require 'email_sender.php';

	function valueInDB($column, $value, $conn){
		$query = "SELECT " . $column . " FROM clients WHERE " . $column . " = :value";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':value' => $_POST[$value]
			)
		);
		if($stmt->rowCount() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	if($_POST['action'] == 'update'){
		$query = "
			UPDATE
				clients
			SET
				user_level = :user_level
			WHERE
				client_id = :user_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':user_level' => $_POST['user_level'],
				':user_id' => $_POST['user_id']
			)
		);
		if($stmt->rowCount() == 1){
			if($_POST['user_level'] == 'provider'){
				$query = "
					SELECT client_id FROM providers_beta WHERE client_id = :user_id
				";
				$stmt = $conn->prepare($query);
				$stmt->execute(
					array(
						':user_id' => $_POST['user_id']
					)
				);
				
				if($stmt->rowCount() < 1){
					$query = "
						INSERT INTO
							providers_beta
							(
								client_id
							)
						VALUES
							(
								:user_id
							)
					";
					$stmt = $conn->prepare($query);
					$stmt->execute(
						array(
							':user_id' => $_POST['user_id']
						)
					);
				}
			}
			else{
				$query = "
					DELETE FROM
						providers_beta
					WHERE
						client_id = :user_id
				";
				$stmt = $conn->prepare($query);
				$stmt->execute(
					array(
						':user_id' => $_POST['user_id']
					)
				);
			}
			echo "User information updated successfully.";
		}
	}
	else if($_POST['action'] == 'insert'){
		$token = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM12346789';
		$token2 = $token;
		$token = str_shuffle($token);
		$token = substr($token, 0, 10);
		
		$randomPass = str_shuffle($token2);
		$randomPass = substr($randomPass, 0, 10);
		$hashedPwd = password_hash($randomPass, PASSWORD_DEFAULT);
		$query = "
			INSERT INTO
				clients
				(
					email,
					fname,
					mname,
					lname,
					name_ext,
					account_password,
					gender,
					address,
					is_email_verified,
					token,
					user_level,
					user_img,
					dob
				)
				VALUES
				(
					:email,
					:fname,
					:mname,
					:lname,
					:name_ext,
					:password,
					:gender,
					:address,
					0,
					:token,
					:user_level,
					:user_img,
					:dob
				)
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':email' => $_POST['email'],
				':fname' => $_POST['fname'],
				':mname' => $_POST['mname'],
				':lname' => $_POST['lname'],
				':name_ext' => $_POST['name_ext'],
				':password' => $hashedPwd,
				':gender' => 'x',
				':address' => 'x',
				':token' => $token,
				':user_level' => 'semiadmin',
				':user_img' => $_POST['user_img'],
				':dob' => $_POST['dob']
			)
		);
		
		if($stmt->rowCount() == 1){
		    $fullName = $_POST['fname'] . ' ' . $_POST['mname'] . ' ' . $_POST['lname'];
			sendEmailMessage(
				"ADMIN EMAIL VERIFICATION - M.V. Santiago Wellness Center",
				'Hello, '. ' ' . $fullName . ', you are invited to become an administrator at our website mvsantiagowellness.com.  
				<br><br>
				ACCOUNT DETAILS<br>
				Email: ' . $_POST['email'] . '<br>
				Password: ' . $randomPass . '<br><br>
				You can change your password later in your user settings area.<br><br>
				Please click the link below to activate the account. <br>
				<a href="http://mvsantiagowellness.com/confirm_registration.php?email=' . $_POST['email'] . '&token=' . $token . '">Click here</a>',
				$_POST['email']
			);
			echo "New admin account added successfully. Please verify email address first before the new account can log-in.";
		}
	}
?>