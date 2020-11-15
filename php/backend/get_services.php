<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$query = "
		SELECT 
			services.service_id,
			services.category_id,
			service_images.link
		FROM 
			services 
		INNER JOIN
			service_images
		ON
			services.service_id = service_images.service_id
		WHERE
			service_images.display_order = 1;
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	$thumbnails = $stmt->fetchAll();

	$query = "select services.appears_on_first_time, services.service_id, services.service_name, services.service_img, services.price, services.duration, services.service_description, category.category_name, services.category_id from services inner join category on services.category_id = category.category_id";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$output = '';
	
	if($stmt->rowCount() > 0){
		$output .= '
			<h3>List of Services</h3>
			<p id="instruction">Double-click the row of the service whose information that you wish to edit.</p>
			<br>
			<table>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Category</th>
					<th>Main Image</th>
					<th>Duration</th>
					<th>Description</th>
					<th>Price</th>
				</tr>

		';
		$result = $stmt->fetchAll();
		for($i=0; $i<count($result); $i++){
			for($x=0; $x<count($thumbnails); $x++){
				if($thumbnails[$x]['service_id'] == $result[$i]['service_id'] && $thumbnails[$x]['category_id'] == $result[$i]['category_id']){
					$durationArr = json_decode($result[$i]['duration'], true);
					$hours = 'hours';
					if($durationArr[0]['hours'] == "1"){
						$hours = 'hour';
					}
					$appearsOnFirstTime = "Yes";
					if($result[$i]['appears_on_first_time'] == '0'){
						$appearsOnFirstTime = "No";
					}
					$minutes = 'minutes';
					if($durationArr[0]['minutes'] == "1"){
						$minutes = 'minute';
					}
					
					$abbrev = explode(" ", $result[$i]['category_name']);
					$prefix = strtoupper($abbrev[0][0] . $abbrev[0][1]);
					
					if(count($abbrev) > 1){
						$prefix = strtoupper($abbrev[0][0] . $abbrev[1][0]);
					}
					
					$output .= '
						<tr class="actual-row">
							<td>'. $prefix . $result[$i]['service_id'] . '</td>
							<td>'. $result[$i]['service_name'] . '</td>
							<td>' . $result[$i]['category_name'] . '</td>
							<td><img class="backend-img-thumb" src="'. $thumbnails[$x]['link'] . '"/></td>
							<td>' . $durationArr[0]['hours'] . ' ' . $hours . ', ' . $durationArr[0]['minutes'] . ' ' . $minutes  .  '</td>
							<td>'. $result[$i]['service_description'] . '</td>
							<td style="display:none">' . $result[$i]['duration'] . '</td>
							<td style="display:none">' . $appearsOnFirstTime . '</td>
							<td>'. $result[$i]['price'] . '</td>
						</tr>
					';
				}
			}
		}
		
		$output .= '</table>';
		echo $output;
	}
	else{
		echo '<p>No services found in database.</p>';
	}

?>