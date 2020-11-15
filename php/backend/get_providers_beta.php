<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();



	$query = "
		SELECT 
			providers_beta.*,
			clients.fname,
			clients.mname,
			clients.lname,
			clients.name_ext,
			clients.gender,
			clients.user_img,
			clients.email,
			clients.dob
		FROM providers_beta
		INNER JOIN clients
		ON providers_beta.client_id = clients.client_id;
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$output = '';
	
	if($stmt->rowCount() > 0){
		$output .= '
			<h3>List of Providers</h3>
			<p id="instruction">Double-click the row of the provider whose information that you wish to edit.</p>
			<br>
			<table>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Image</th>
					<th>Position</th>
					<th>Gender</th>
					<th>Email</th>
					<th>Date of Birth</th>
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
					<td>PROV_'. $row['provider_id'] . '</td>
					<td>'. $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'] . ' ' . $row['name_ext'] . '</td>
					<td><img class="backend-img-thumb" src="'. $row['user_img'] . '"/></td>
					<td>'. ucfirst($row['provider_level']) . '</td>
					<td>' . $row['gender'] . '</td>
					<td class="not-displayed">' .$row['fname'] . '</td>
					<td class="not-displayed">' .$row['mname'] . '</td>
					<td class="not-displayed">' .$row['lname'] . '</td>
					<td class="not-displayed">' .$row['name_ext'] . '</td>
					<td class="not-displayed">' .$row['provider_bio'] . '</td>
					<td>' . $row['email'] . '</td>
					<td>' . $row['dob'] . '</td>
					<td>' . $interval->y . '</td>
					<td style="display: none;">'. $row['user_img'] . '</td>
					<td style="display: none;">'. $row['provider_id'] . '</td>
				</tr>
			';
		}
		
		$output .= '</table>';
		echo $output;
	}
	else{
		echo '<p>No providers found in database.</p>';
	}

?>