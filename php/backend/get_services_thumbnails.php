<?php
	require '../dbconfig.php';
	
	$query = "SELECT * FROM service_images WHERE service_id = :service_id ORDER BY display_order ASC";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':service_id' => $_GET['service_id']
		)
	);
	$output = '';
	
	if($stmt->rowCount() > 0){

		$result = $stmt->fetchAll();
		foreach($result as $row){	
			$output .= '
				<tr class="actual-row">
					<td>'. $row['display_order'] . '</td>
					<td><img class="img-cms-thumb" src="'. $row['link'] . '"/></td>
					<td style="display: none;">' . $row['si_id'] . '</td>
					<td style="display: none;">' . $row['link'] . '</td>
				</tr>
			';
		}	
		$output .= '</table>';
		echo $output;
	}
	else{
		echo $output;
	}
?>