<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	$query = "SELECT * FROM providers";
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
				</tr>

		';
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$output .= '
				<tr class="actual-row">
					<td>'. $row['provider_id'] . '</td>
					<td>'. $row['provider_fname'] . ' ' . $row['provider_mname'] . ' ' . $row['provider_lname'] . ' ' . $row['provider_name_ext'] . '</td>
					<td><img class="backend-img-thumb" src="'. $row['provider_img'] . '"/></td>
					<td>'. ucfirst($row['provider_level']) . '</td>
					<td>' . $row['provider_gender'] . '</td>
					<td class="not-displayed">' .$row['provider_fname'] . '</td>
					<td class="not-displayed">' .$row['provider_mname'] . '</td>
					<td class="not-displayed">' .$row['provider_lname'] . '</td>
					<td class="not-displayed">' .$row['provider_name_ext'] . '</td>
					<td class="not-displayed">' .$row['provider_bio'] . '</td>
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