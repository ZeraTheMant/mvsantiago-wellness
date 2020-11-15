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
	
	
	$errorMsg = "";		
	if(empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['mi']) || empty($_POST['email']) || empty($_POST['contact_number']) || empty($_POST['dob']) || empty($_POST['address']) || empty($_POST['gender'])){
		$errorMsg .= "Incomplete information. Please fill out all the fields.\n";
	}		
	if($_POST['password'] != $_POST['cpassword']){
		$errorMsg .= "Password and confirm password values don't match.\n";
	}
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$errorMsg .= "Entered email is invalid.\n";
	}
	if(!preg_match("/^[a-zA-Z\s]+$/", $_POST['fname']) || !preg_match("/^[a-zA-Z\s]+$/", $_POST['lname'])){
		$errorMsg .= "First and last names can only contain letters and spaces.\n";
	}
	if(!preg_match("/^[\d]+$/", $_POST['contact_number'])){
		$errorMsg .= "Contact number can only contain numbers.\n";
	}
	if($errorMsg != ""){
		echo $errorMsg;
		exit();
	}
	else{
		$errorMsg2 = "";
		if(valueInDB('email', 'email', $conn)){
			$errorMsg2 .= "Email already exists in database.\n";
		}
		if(valueInDB('contact_number', 'contact_number', $conn)){
			$errorMsg2 .= "Contact number already exists in database.\n";
		}
		$query = "
			select fname, mname, lname from clients where fname = :fname and mname = :mname and lname =:lname;
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':fname' => $_POST['fname'],
				':mname' => $_POST['mi'],
				':lname' => $_POST['lname']
			)
		);
		if($stmt->rowCount() > 0){
			$errorMsg2 .= "Name already registered. \n";
		}
		if($errorMsg2 != ""){
			echo $errorMsg2;
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
							account_password, 
							email, 
							contact_number, 
							fname, 
							lname, 
							mname, 
							dob, 
							gender, 
							address, 
							is_email_verified, 
							token,
							user_level,
							heart_cond,
							skin_cond,
							allergy
						) 
					VALUES 
						(
							:password, 
							:email, 
							:contact_no, 
							:fname, 
							:lname, 
							:mname, 
							:dob, 
							:gender, 
							:address, 
							:is_email_verified, 
							:token,
							:level,
							:heart_cond,
							:skin_cond,
							:allergy
						)
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':password' => $hashedPwd,
					':email' => $_POST['email'],
					':contact_no' => $_POST['contact_number'],
					':fname' => $_POST['fname'],
					':lname' => $_POST['lname'],
					':mname' => $_POST['mi'],
					':dob' => $_POST['dob'],
					':gender' => $_POST['gender'],
					':address' => $_POST['address'],
					':is_email_verified' => false,
					':token' => $token,
					':level' => $_POST['user_level'],
					':heart_cond' => $_POST['heart_cond'],
					':skin_cond' => $_POST['skin_cond'],
					':allergy' => $_POST['allergy']
				)
			);
			$fullName = $_POST['fname'] . ' ' . $_POST['mi'] . ' ' . $_POST['lname'];
			//https://jarindentalclinic.com/confirm.php
			sendEmailMessage(
				"ACCOUNT VERIFICATION - M.V. Santiago Wellness Center",
				"Hello, " . $fullName . ' . Thank you for registering for our website. 
				<br><br>
				ACCOUNT DETAILS<br>
				Email: ' . $_POST['email'] . '<br>
				Password: ' . $randomPass . '<br><br>
				You can change your password later in your user settings area.<br><br>
				Please click the link below to activate your account. <br>
				<a href="http://mvsantiagowellness.com/confirm_registration.php?email=' . $_POST['email'] . '&token=' . $token . '">Click here</a>',
				$_POST['email']
			);	
			echo true;
			exit();
		}
	}
?>