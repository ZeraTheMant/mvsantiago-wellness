<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	if($_GET['caller'] == "backend"){

		$query = "SELECT * FROM showcase_images ORDER BY display_order ASC";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$output = '
			<table>
				<tr>
					<th>Display order</th>
					<th>Thumbnail</th>
					<th>Header</th>
					<th>Subheader</th>
					<th>Links to</th>
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
				<tr class="actual-row">
					<td>'. $row['display_order'] . '</td>
					<td><img class="backend-img-thumb" src="' . $row['img_link'] . '"/></td>
					<td>' . $row['header'] . '</td>
					<td>' . $row['subheader'] . '</td>
					<td>' . $row['link_to'] . '</td>
					<td>' . $displayedMsg . '</td>
					<td style="display: none;">' . $row['description'] . '</td>
					<td style="display: none;">' . $row['showcase_id'] . '</td>
					<td style="display: none;">' . $row['img_link'] . '</td>
				</tr>
				
			';
		}
		
		$output .= '</table>';
				
		echo $output;
	}
	else{
		$resultArray = array();
		$query = "SELECT * FROM showcase_images ORDER BY display_order DESC";
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
					<div id="showcase-info">
						<div class="showcase-info-relative-div">
							<div id="showcase-info-faded"></div>
							<div id="showcase-info-content">
								<div class="showcase-info-relative-div" id="wewe">
									<h1 id="showcase-info-header">' . $row['header'] . '</h1>
									<h3 class="showcase-subheader">' . $row['subheader'] . '</h3>
									<p>
										' . $row['description'] . ' 
									</p>
									<a id="showcase-link-to-about" href="' . $row['link_to'] . '">LEARN MORE</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
		}
		
		$resultArray["showcase"] = $output;
		
		$query = "SELECT * FROM category ORDER BY RAND() LIMIT 3";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		
		$output = '';
		
		if($stmt->rowCount() > 0){
		$result = $stmt->fetchAll();
		
		foreach($result as $row){
			$desc = $row['category_description'];
			if(strlen($desc) > 200){
				$desc = substr($desc, 0, 197) . "...";
			}
			$output .= '
				<div class="index-service">
					<div class="index-service-content">
						<img src="' . $row['category_img'] .'" class="index-service-img"/>
						<h3 class="index-service-header">' . $row['category_name'] . '</h3>
						<p class="index-service-desc">' . $desc . '</p>
					</div>
				</div>
			';
		}
		}
		else{
			$output .= '
				<p>No service found.</p>
			';
		}
		
		$resultArray["services"] = $output;
		
		$query = "SELECT testimonials.*, clients.name_ext, clients.fname, clients.mname, clients.lname, clients.dob, clients.user_img FROM testimonials INNER JOIN clients ON testimonials.client_id = clients.client_id WHERE testimonials.approval_status = 1 ORDER BY RAND() LIMIT 1";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		
		$output = '';
		
		if($stmt->rowCount() > 0){
		$result = $stmt->fetch();
		
		$name = $result['fname'] . ' ' . ucfirst($result['mname'][0]) . '. ' . $result['lname'] . ' ' . $result['name_ext'];
		
		$current_date = date_create(date("Y-m-d"));
		$date_of_birth = date_create($result['dob']);
		$current_age = date_diff($current_date, $date_of_birth)->format("%y");
		
		$output .= '
			<p id="simple-testi">' . $result['message'] . '</p>
			
			<div id="flexed">
				<div id="w3w">
					<p id="simple-testi-name">' . $name . ', '. $current_age . '</p>
					<p>' . $result['sender_address'] . '</p>
				</div>
				<div id="w0w">
					<img src="' . $result['user_img'] . '" id="simple-testi-pic"/>
				</div>
			</div>
		';

		}
		else{
			$output = '<p style="grid-column: 1/5;">No testimonials found in database.</p>';
		}	
		
		$resultArray["testimonial"] = $output;
		
		echo json_encode($resultArray);
	}


?>