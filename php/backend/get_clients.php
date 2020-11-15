<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

    if($_GET['user_level'] == "admin"){
      	$query = "SELECT * FROM clients WHERE user_level = :user_level OR user_level = 'semiadmin'";
    	$stmt = $conn->prepare($query);
    	$stmt->execute(
    		array(
    			':user_level' => $_GET['user_level']
    		)
    	);  
    }
    else{
        $query = "SELECT * FROM clients WHERE user_level = :user_level";
    	$stmt = $conn->prepare($query);
    	$stmt->execute(
    		array(
    			':user_level' => $_GET['user_level']
    		)
    	);
    }

	$output = '';
	
	if($stmt->rowCount() > 0){
	    $level = "Users";
	   	$prefix = "USER_";
	    if($_GET['user_level'] == "admin"){
	        $level = "Administrators";    
	        $prefix = "ADMIN_";
	    }
	    
		$output .= '
			<h3>List of ' . $level . '</h3>
			<p id="instruction">Double-click the row of the account whose information that you wish to edit.</p>
			<br>
			<table>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Contact number</th>
					<th>Date of Birth</th>
					<th>Gender</th>
					<th>Image</th>
					<th>User level</th>
					<th>Age</th>
				</tr>

		';
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$date = new DateTime($row['dob']);
			$now = new DateTime();
			$interval = $now->diff($date);

			
			
			$output .= '
				<tr class="actual-row">
					<td>'. $prefix . $row['client_id'] . '</td>
					<td>'. $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'] . ' ' . $row['name_ext'] . '</td>
					<td>'. $row['email'] . '</td>
					<td>'. $row['contact_number'] . '</td>
					<td>'. $row['dob'] . '</td>
					<td>'. $row['gender'] . '</td>
					<td><img class="backend-img-thumb" src="'. $row['user_img'] . '"/></td>
					<td>'. $row['user_level'] . '</td>
					<td class="not-displayed">' .$row['fname'] . '</td>
					<td class="not-displayed">' .$row['mname'] . '</td>
					<td class="not-displayed">' .$row['lname'] . '</td>
					<td class="not-displayed">' .$row['name_ext'] . '</td>
					<td class="not-displayed">' .$row['address'] . '</td>
					<td>' . $interval->y . '</td>
					<td class="not-displayed">'. $row['client_id'] . '</td>
					<td class="not-displayed">'. $row['heart_cond'] . '</td>
					<td class="not-displayed">'. $row['skin_cond'] . '</td>
					<td class="not-displayed">'. $row['allergy'] . '</td>
				</tr>
			';
		}
		
		$output .= '</table>';
		echo $output;
	}
	else{
		echo '<p>No users found in database.</p>';
	}

?>