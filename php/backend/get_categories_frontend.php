<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	$query = "SELECT * FROM category";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$output = '';
	
	if($stmt->rowCount() > 0){

		$result = $stmt->fetchAll();
	
		foreach($result as $row){
			$desc = $row['category_description'];
			if(strlen($desc) > 200){
				$desc = substr($desc, 0, 197) . '...';

			}
			
			$output .= '
				<div class="individual-category">
					<div class="category-img-container">
						<img src="' . $row['category_img'] . '" class="category-img"/>
						<div class="read-more-container">
							<a href="http://mvsantiagowellness.com/services/view_category.php?category_id=' . $row['category_id'] . '&title=' . $row['category_name'] . '&img=' . $row['category_img'] . '&desc=' . $row['category_description'] . '" class="read-more">READ MORE</a>
						</div>
					</div>
					<div class="category-info">
						<div class="category-info-content">
							<h3>' . $row['category_name'] . '</h3>
							<p>
								' . $desc . '
							</p>
						</div>
					</div>
				</div>
			';
		}
		
		echo $output;
	}
	else{
		echo '<p>No categories found in database.</p>';
	}

?>