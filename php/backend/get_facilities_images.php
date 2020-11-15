<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	if($_GET['caller'] == "backend"){
		$query = "SELECT * FROM facilities_images ORDER BY display_order ASC";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$output = '
			<table>
				<tr>
					<th>Display order</th>
					<th>Thumbnail</th>
					<th>Header</th>
					<th>Description</th>
				</tr>
		';
		
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$displayedMsg = $row['description'];
			if(strlen($row['description']) > 20){
				$displayedMsg = substr($row['description'], 0, 20) . '...';
			}
			
			$output .= '
				<tr class="actual-row fac-row">
					<td>'. $row['display_order'] . '</td>
					<td><img class="backend-img-thumb" src="' . $row['img_link'] . '"/></td>
					<td>' . $row['header'] . '</td>
					<td>' . $displayedMsg . '</td>
					<td style="display: none;">' . $row['description'] . '</td>
					<td style="display: none;">' . $row['fi_id'] . '</td>
					<td style="display: none;">' . $row['img_link'] . '</td>
				</tr>
				
			';
		}
		
		$output .= '</table>';
		echo $output;
	}
	else{
		$query = "SELECT * FROM facilities_images ORDER BY display_order ASC";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$output = '
			<div class="imgbanbtn imgbanbtn-prev" id="imgbanbtn-prev"></div>
			<div class="imgbanbtn imgbanbtn-next" id="imgbanbtn-next"></div>
		';
		
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$output .= '				
				
				<div class="showcase-card" id="card-'. $row['display_order'] . '">
					<img id="showcase-img" src="' . $row['img_link'] . '"/>
					<div class="facility-info">
						<h1>' . $row['header'] . '</h1>
						<p>' . $row['description'] . '</p>
					</div>
				</div>
			';
		}
		echo $output;
	}


?>