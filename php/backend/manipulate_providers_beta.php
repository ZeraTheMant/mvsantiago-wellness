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
	
	if($_POST['action'] == 'insert'){
		if(valueInDB('email', 'email', $conn)){
			echo "Email already exists in database.\n";
			exit();
		}
		else{
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
						lname,
						mname,
						name_ext,
						account_password,
						dob,
						gender,
						is_email_verified,
						user_level,
						token,
						user_img
					)
				VALUES
					(
						:email,
						:fname,
						:lname,
						:mname,
						:name_ext,
						:password,
						:dob,
						:gender,
						0,
						:provider,
						:token,
						:prov_img
					)
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':email' => $_POST['email'],
					':fname' => $_POST['fname'],
					':lname' => $_POST['lname'],
					':mname' => $_POST['mname'],
					':name_ext' => $_POST['name_ext'],
					':password' => $hashedPwd,
					':dob' => $_POST['dob'],
					':gender' => $_POST['gender'],
					':provider' => 'provider',
					':token' => $token,
					':prov_img' => $_POST['prov_img']
				)
			);
			
			if($stmt->rowCount() == 1){
				/*$query = "SELECT client_id FROM clients WHERE client_id = @@identity";
				$stmt = $conn->prepare($query);
				$stmt->execute();
				$result = $stmt->fetch();
				echo $result['client_id'];*/
				
				
				
				$query = "
					INSERT INTO providers_beta
					(
						client_id,
						provider_bio,
						provider_level,
						days_worked
					)
					VALUES
					(
						@@identity,
						:provider_bio,
						:provider_level,
						:days_worked
					)
				";
				$stmt = $conn->prepare($query);
				$stmt->execute(
					array(
						':provider_bio' => $_POST['provider_bio'],
						':provider_level' => $_POST['provider_level'],
						':days_worked' => json_encode($_POST['days_worked'])
					)
				);
				if($stmt->rowCount() > 0){
					insertToProviderServices("@@identity", $conn);
					$fullName = $_POST['fname'] . ' ' . $_POST['mname'] . ' ' . $_POST['lname'];
					sendEmailMessage(
						"PROVIDER EMAIL VERIFICATION - M.V. Santiago Wellness Center",
						'Hello, ' . $fullName . ' You are invited to join mvsantiagowellness.com as a provider. 
						<br><br>
						ACCOUNT DETAILS<br>
        				Email: ' . $_POST['email'] . '<br>
        				Password: ' . $randomPass . '<br><br>
        				You can change your password later in your user settings area.<br><br>
						Please click the link below to activate the account. <br>                      <a href="http://mvsantiagowellness.com/confirm_registration.php?email=' . $_POST['email'] . '&token=' . $token . '">Click here</a>
                        ',
						$_POST['email']
					);
					
					echo "New provider added successfully. Please verify email address first before the provider can log-in.";
				}
			}
		}
	}
	else if($_POST['action'] == 'update'){
		$query = "
			UPDATE providers_beta
			SET
			provider_bio = :provider_bio,
			provider_level = :provider_level,
			days_worked = :days_worked
			WHERE provider_id = :provider_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':provider_bio' => $_POST['provider_bio'],
				':provider_level' => $_POST['provider_level'],
				':days_worked' => json_encode($_POST['days_worked']),
				':provider_id' => $_POST['provider_id']
			)
		);
		
		$query = "
			SELECT
				client_id
			FROM
				providers_beta
			WHERE provider_id = :provider_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':provider_id' => $_POST['provider_id']
			)
		);
		
		$result = $stmt->fetch();
		$client_id = $result['client_id'];
		
		$query = "
			UPDATE
				clients
			SET
				user_img = :prov_img
			WHERE
				client_id = :client_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':prov_img' => $_POST['prov_img'],
				':client_id' => $client_id
			)
		);
		/*if($stmt->rowCount() == 1){
			insertToProviderServices($_POST['provider_id'], $conn);
			echo "Provider information updated successfully.";
		}*/
		insertToProviderServices($_POST['provider_id'], $conn);
		echo "Provider information updated successfully.";
	}
	else{
		$query = "DELETE FROM providers WHERE provider_id = :provider_id";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':provider_id' => $_POST['provider_id']
			)
		);
		if($stmt->rowCount() == 1){
			echo "Provider deleted successfully.";
		}
	}

	function insertToProviderServices($provider_id, $conn){
		$query = "
			DELETE FROM provider_services WHERE provider_id = :provider_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':provider_id' => $provider_id
			)
		);
		
		$kiko = '';
		
		for($i=0; $i<count($_POST['cat_and_serv_id']); $i++){
			if($i< count($_POST['cat_and_serv_id'])-1){
				$kiko .= '
					(' . $provider_id . ', ' . $_POST["cat_and_serv_id"][$i]['category_id'] . ', ' . $_POST["cat_and_serv_id"][$i]['service_id'] . '),
				';
			}
			else{
				$kiko .= '
					(' . $provider_id . ', ' . $_POST["cat_and_serv_id"][$i]['category_id'] . ', ' . $_POST["cat_and_serv_id"][$i]['service_id'] . ')
				';
			}
		}
		
		$query = "
			INSERT INTO provider_services
			(
				provider_id,
				category_id,
				service_id
			)
			VALUES
		";
		
		$query .= $kiko;
		$stmt = $conn->prepare($query);
		$stmt->execute();
	}
?>