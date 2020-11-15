<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	$query = "
		SELECT 
			services.service_id,
			service_images.link
		FROM 
			services 
		INNER JOIN
			service_images
		ON
			services.service_id = service_images.service_id
		WHERE 
			services.category_id = :category_id
		AND
			service_images.display_order = 1;
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':category_id' => $_GET['category_id']
		)
	);
	
	$thumbnails = $stmt->fetchAll();
	
	$query = "SELECT services.*, category.category_name FROM services INNER JOIN category ON services.category_id = category.category_id WHERE services.category_id = :category_id";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':category_id' => $_GET['category_id']
		)
	);
	

	
	$output = '';
	
	if($stmt->rowCount() > 0){
//&desc=' . $result[$i]['service_description'] . '
		$result = $stmt->fetchAll();
		for($i=0; $i<count($result); $i++){
			for($x=0; $x<count($thumbnails); $x++){
				if($thumbnails[$x]['service_id'] == $result[$i]['service_id']){
					$desc = $result[$i]['service_description'];
					if(strlen($desc) > 200){
						$desc = substr($desc, 0, 197) . '...';

					}
					
					$output .= '
						<div class="individual-category">
							<div class="category-img-container">
								<img src="' . $thumbnails[$x]['link'] . '" class="category-img"/>
								<div class="read-more-container">
									<a href="http://mvsantiagowellness.com/services/view_service.php?id=' . $result[$i]['service_id'] . '&title=' . $result[$i]['service_name'] . '&desc=asdf&price=' . $result[$i]['price'] . '&category=' . $result[$i]['category_name'] . '" class="read-more">READ MORE</a>
								</div>
							</div>
							<div class="category-info">
								<div class="category-info-content">
									<h3>' . $result[$i]['service_name'] . '</h3>
									<p>
										' . $desc . '
									</p>
									<span>Price: &#8369;' . $result[$i]['price'] . '</span>
								</div>
							</div>
						</div>
					';	
					break;
				}
			}
		}
		
		echo $output;
	}
	else{
		echo '<p>No services found in database.</p>';
	}

?>