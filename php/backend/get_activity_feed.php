<?php
	require '../dbconfig.php';
	session_start();
	//$inipath = php_ini_loaded_file();

	$output = '';

	if($_GET['user_level'] == "admin"){
		$query = "
			SELECT
				activity_feed.*,
				clients.fname,
				clients.lname
			FROM
				activity_feed
			LEFT JOIN
				clients
			ON	
				activity_feed.client_id = clients.client_id
			WHERE
				activity_feed.is_seen = 0
			ORDER BY
				date DESC
			LIMIT 10
			OFFSET " . $_GET['offset'] . "
		";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		
		$result = $stmt->fetchAll();
		
		foreach($result as $row){
			$prefix = $row['fname'] . ' ' . $row['lname'];
			$spaceOrNo = " ";
			$date = new DateTime($row['date']);
			
			if($row['client_id'] == $_GET['client_id']){
				$prefix = "You";
			}
			else if($row['client_id'] == "0"){
				$prefix = "";
				$spaceOrNo = "";
			}
			
			$output .= '
				<div class="feed-row" id="' . $row['activity_id'] . '">
					<div>
						<span class="feed-close-icon icon-cross"></span>&nbsp&nbsp<span class="feed-content">' . $prefix . '' . $spaceOrNo . '' . $row['message'] . '</span> 
					</div>
					<div>
						<span class="row-date">' . $date->format("M j g:i A") . '</span>
					</div>
				</div>
			';
		}
	}
	else if($_GET['user_level'] == "provider"){
		$query = "
			SELECT
				activity_feed.*,
				clients.fname,
				clients.lname
			FROM
				activity_feed
			LEFT JOIN
				clients
			ON	
				activity_feed.client_id = clients.client_id
			WHERE
				activity_feed.is_seen = 0
			AND
				(activity_feed.client_id = :client_id OR activity_feed.provider_id = :provider_id)
			ORDER BY
				date DESC
			LIMIT 10
			OFFSET " . $_GET['offset'] . "
		";
		
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':client_id' => $_GET['client_id'],
				':provider_id' => $_SESSION['provider_id']
			)
		);
		
		$result = $stmt->fetchAll();
		
		foreach($result as $row){
			$prefix = $row['fname'] . ' ' . $row['lname'];
			$spaceOrNo = " ";
			$date = new DateTime($row['date']);
			
			if($row['client_id'] == $_GET['client_id']){
				$prefix = "You";
			}
			else if($row['client_id'] == "0"){
				$prefix = "";
				$spaceOrNo = "";
			}
			
			$output .= '
				<div class="feed-row" id="' . $row['activity_id'] . '">
					<div>
						<span class="feed-close-icon icon-cross"></span>&nbsp&nbsp<span class="feed-content">' . $prefix . '' . $spaceOrNo . '' . $row['message'] . '</span> 
					</div>
					<div>
						<span class="row-date">' . $date->format("M j g:i A") . '</span>
					</div>
				</div>
			';
		}
	}
	else{
		$query = "
			SELECT
				activity_feed.*,
				clients.fname,
				clients.lname
			FROM
				activity_feed
			LEFT JOIN
				clients
			ON	
				activity_feed.client_id = clients.client_id
			WHERE
				activity_feed.is_seen = 0
			AND
				activity_feed.client_id = :client_id
			ORDER BY
				date DESC
			LIMIT 10
			OFFSET " . $_GET['offset'] . "
		";
		
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':client_id' => $_GET['client_id']
			)
		);
		
		$result = $stmt->fetchAll();
		
		foreach($result as $row){
			$prefix = $row['fname'] . ' ' . $row['lname'];
			$spaceOrNo = " ";
			$date = new DateTime($row['date']);
			
			if($row['client_id'] == $_GET['client_id']){
				$prefix = "You";
			}
			else if($row['client_id'] == "0"){
				$prefix = "";
				$spaceOrNo = "";
			}
			
			$output .= '
				<div class="feed-row" id="' . $row['activity_id'] . '">
					<div>
						<span class="feed-close-icon icon-cross"></span>&nbsp&nbsp<span class="feed-content">' . $prefix . '' . $spaceOrNo . '' . $row['message'] . '</span> 
					</div>
					<div>
						<span class="row-date">' . $date->format("M j g:i A") . '</span>
					</div>
				</div>
			';
		}
	}
	
	echo $output;

?>