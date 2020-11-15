<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$query = "
		SELECT 
			testimonials.*,
			clients.fname,
			clients.mname,
			clients.lname,
			clients.name_ext,
			clients.dob,
			clients.user_img
		FROM 
			testimonials
		INNER JOIN
			clients
		ON
			testimonials.client_id = clients.client_id
		WHERE
			testimonials.approval_status = :approval_status
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':approval_status' => $_GET['approval_status']
		)
	);
	$output = '';
	
	if($stmt->rowCount() > 0){
		$result = $stmt->fetchAll();
		
		if($_GET['view'] == "backend"){		
				$output .= '
					<h3>List of Testimonials</h3>
					<p id="instruction">Double-click the row of the testimonial that you wish to view.</p>
					<br>
					<table>
						<tr>
							<th>ID</th>
							<th>Sender</th>
							<th>Sender Address</th>
							<th>Testimonial</th>
						</tr>

				';
				foreach($result as $row){
					$output .= '
						<tr class="actual-row">
							<td>'. $row['testimonial_id'] . '</td>
							<td>'. $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'] . ' ' . $row['name_ext'] . '</td>
							<td>'. $row['sender_address'] . '</td>
							<td>'. substr($row['message'], 0, 70) . '...</td>
							<td class="not-displayed">' .$row['message'] . '</td>
							<td class="not-displayed">' .$row['dob'] . '</td>
							<td class="not-displayed">' .$row['user_img'] . '</td>
						</tr>
					';
				}
				
				$output .= '</table>';
		}
		else{
			foreach($result as $row){
				$current_date = date_create(date("Y-m-d"));
				$date_of_birth = date_create($row['dob']);
				$current_age = date_diff($current_date, $date_of_birth)->format("%y");
				
				$output .= '
					<div class="outer-testimonial-box">
						<div class="inner-testimonial-box">
							<img class="thumb-img" src="' . $row['user_img'] .'"/>
							<p class="testi-message">' . $row['message'] . '</p>
							<p class="testi-sender">'. $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'] . ' ' . $row['name_ext'] . ', '. $current_age . '</p>
							<p class="testi-sender-address">' . $row['sender_address'] . '</p>
						</div>
						<div class="quotation-design-box"><div>&#8221;</div></div>
					</div>
				';
			}
		}
	}
	else{
		$output = '<p style="grid-column: 1/5;">No testimonials found in database.</p>';
	}
	
	echo $output;
?>