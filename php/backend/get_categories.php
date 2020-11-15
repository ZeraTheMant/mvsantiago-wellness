<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	$query = "SELECT * FROM category";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$output = '';
	
	if($stmt->rowCount() > 0){
		$output .= '
			<h3>List of Categories</h3>
			<p id="instruction">Double-click the row of the category whose information that you wish to edit.</p>
			<br>
			<table>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Image</th>
					<th>Description</th>
				</tr>

		';
		$result = $stmt->fetchAll();
		foreach($result as $row){
			
			
			$output .= '
				<tr class="actual-row">
					<td>C'. $row['category_id'] . '</td>
					<td>' . $row['category_name'] . '</td>
					<td><img class="backend-img-thumb" src="'. $row['category_img'] . '"/></td>
					<td>' . $row['category_description'] . '</td>
					<td style="display: none;">'. $row['category_img'] . '</td>
				</tr>
			';
		}
		
		$output .= '</table>';
		echo $output;
	}
	else{
		echo '<p>No categories found in database.</p>';
	}

?>