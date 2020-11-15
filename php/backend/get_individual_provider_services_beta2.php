<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	$query = "
		SELECT 
			provider_services.ps_id, 
			services.service_id, 
			services.service_name, 
			services.category_id,
			services.appears_on_first_time,
			providers_beta.provider_id, 
			providers_beta.provider_level,
			providers_beta.days_worked,
			category.category_id, 
			category.category_name 
		FROM 
			provider_services 
		INNER JOIN 
			services
		ON 
			provider_services.service_id = services.service_id 
		INNER JOIN 
			providers_beta 
		ON 
			provider_services.provider_id = providers_beta.provider_id 
		INNER JOIN 
			category 
		ON 
			provider_services.category_id = category.category_id 
		WHERE 
			provider_services.provider_id = :provider_id";
			
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':provider_id' => $_GET['provider_id']
		)
	);
	
	if($stmt->rowCount() > 0){
		$output = '
			<h3>List of my Categories and Services</h3>
			<p id="instruction">Displays all the categories and services that you as a provider are allowed to perform.</p>
			<br>
			<div id="provider-categories-services-container">
				<table>
					<thead>
						<tr>
							<th>Category</th>
							<th>Service</th>
							<th>Requires provider approval</th>
						</tr>
					</thead>
					<tbody>
		';
		$provider_categories = $stmt->fetchAll();
		
		foreach($provider_categories as $row){
			$appearsOnFirstTime = 'Yes';
			if($row['appears_on_first_time'] == "1"){
				$appearsOnFirstTime = 'No';
			}
			
			$output .='
				<tr class="actual-row">
					<td>' . $row['category_name'] . '</td>
					<td>' . $row['service_name'] . '</td>
					<td>' . $appearsOnFirstTime . '</td>
				</tr>
			';
		}
		
		$days_worked = json_decode($provider_categories[0]["days_worked"], true);
		
		$output .='
			</tbody></table>
			<br><br>
			<h3>My Working Days and Hours</h3>
			<p id="instruction">Displays your working days and hours.</p>
			<br>
			
			<table>
				<thead>
					<tr>
						<th>Day</th>
						<th>Start</th>
						<th>End</th>
					</tr>
				</thead>
				<tbody>

		';
		

		foreach ($days_worked as $key => $value){
			$output .= '
				<tr>
					<td><label>' . $key . '</label></td>
					<td><input style="width: 100%; box-sizing: border-box; padding: 5px;" type="time" value="' . $value['start'] . '" disabled/></td>
					<td><input style="width: 100%; box-sizing: border-box; padding: 5px;" type="time" value="' . $value['end'] . '" disabled/></td>
				</tr>
			';
		}
		
		$output .= "</tbody></table></div>";
		echo $output;
	}
	else{
		echo "<p>No categories and services found for this provider.</p>";
	}
?>