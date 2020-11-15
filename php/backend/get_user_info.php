<?php
	require '../dbconfig.php';

	$query = "SELECT * FROM clients WHERE client_id = :id";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':id' => $_GET['user_id']
		)
	);

	if($stmt->rowCount() == 1){
		if($_GET['getter'] == 'backend'){
			$output = '';
			$result = $stmt->fetch();
			
			$output .= '		
				<form id="user-settings-form" class="userInfoForm" method="post" enctype="multipart/form-data">
				
					<div class="user-info-row">
						<div><span class="userInfoField">Photo</span></div>
						<div id="relative-div">
							<img class="modal-preview-img" src="' .$result['user_img'] . '"/>
							<label for="modal-img" class="modal-img-btn">Change</label>
							<input type="file" name="userImage" id="modal-img" accept="image/*"/>
						</div>
					</div>
					
					<div class="user-info-row">
						<div><span class="userInfoField">User ID</span></div>
						<div><span class="userInfoValue" id="user-id">' .$result['client_id'] . '</span></div>
					</div>
					
					<div class="user-info-row">
						<div><span class="userInfoField" id="user-password">Password</span></div>
						<div>
							<button type="button" class="modal-btn" id="change-password-btn">Change password</button>
							<div id="change-password-form">
								<p>Minimum of 5 characters.</p>
								<p class="zero-margin">Old password:</p>
								<p class="zero-margin"><input type="password" class="userInfoValueInput end" id="oldPass" name="oldPass"/></p>
								<p class="zero-margin">New password:</p>
								<p class="zero-margin"><input type="password" class="userInfoValueInput end" id="newPass" name="newPass"/></p>
								<p class="zero-margin">Confirm new password:</p>
								<p class="zero-margin"><input type="password" class="userInfoValueInput end" id="newPassConfirm" name="newPassConfirm"/></p>
							</div>
						</div>
					</div>
					
					<div class="user-info-row">
						<div><span class="userInfoField">Full Name</span></div>
						<div><span class="userInfoValue" id="user-full-name">' . $result['fname'] . ' ' . $result['mname'] . ' ' . $result['lname'] . '</span></div>
					</div>
					
					<div class="user-info-row">
						<div><span class="userInfoField">Date of Birth</span></div>
						<div><span class="userInfoValue" id="user-dob">' . $result['dob'] . '</span></div>
					</div>
					
					<div class="user-info-row">
						<div><span class="userInfoField">Gender</span></div>
						<div><span class="userInfoValue" id="user-gender">' . $result['gender'] . '</span></div>
					</div>
					
					<div class="user-info-row">
						<div><span class="userInfoField">Email address</span></div>
						<div><input type="email" class="userInfoValueInput" name="user-email" value="' . $result['email'] . '" id="user-email" required></div>
					</div>
					
					<div class="user-info-row">
						<div><span class="userInfoField">Contact no.</span></div>
						<div><input type="text" pattern="\d+$" name="user-contact" class="userInfoValueInput" value="' . $result['contact_number'] . '" id="user-contact" required></div>
					</div>
					
					<div class="user-info-row">
						<div><span class="userInfoField">Address</span></div>
						<div><textarea rows="4" class="userInfoValueInput" name="user-address" id="user-address" required>' . $result['address'] . '</textarea></div>
					</div>
					
					<div class="user-info-row">
						<div><span class="userInfoField">User level</span></div>
						<div><span class="userInfoValue" id="user-level">' . $result['user_level'] . '</span></div>
					</div>
					
				</form>
			';
			
			echo $output;	
		}
		else{
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			echo json_encode($result);
		}
	}
	else{
		echo "<p>Error: No user found.</p>";
	}
?>