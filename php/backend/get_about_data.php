<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();
	//$output = '<option selected>All providers</option>';
	
	$resultArray = array();
	
	$query = "
		SELECT
			providers_beta.*,
			clients.fname,
			clients.mname,
			clients.lname,
			clients.name_ext,
			clients.user_img
		FROM
			providers_beta
		INNER JOIN
			clients
		ON
			providers_beta.client_id = clients.client_id
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	$output = '';
	
	if($stmt->rowCount() > 0){
		$result = $stmt->fetchAll();
		foreach($result as $row){
			$provider_level = '';
			if($row['provider_level'] == 'Doctor'){
				$provider_level = 'Dr. ';
			}
			
			$output .= '
				<div class="provider-profile">
					<div class="aaa">
						<img src="' . $row['user_img'] . '" class="provider-thumb"/>
					</div>
					<div class="whew">
						<h3>' . $provider_level . '' . $row['fname'] . ' ' . ucfirst($row['mname'][0]) . '. ' . $row['lname'] . ' ' . $row['name_ext'] . '</h3>
						<p>' . $row['provider_bio'] . '</p>
					</div>
				</div>
			';
		}
	}
	
	$resultArray["providers"] = $output;
	
	$query = "SELECT * FROM about_page_text LIMIT 1";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	$data = array();
	
	$result = $stmt->fetch();
	
	$data['about_main_text'] = $result['about_main_text'];
	$data['vision_text'] = $result['vision_text'];
	$data['mission_text'] = $result['mission_text'];
	
	$resultArray['about_texts'] = $data;
	
	echo json_encode($resultArray);
	
	
?>