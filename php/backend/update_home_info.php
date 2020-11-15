<?php
	require '../dbconfig.php';
	
	function updateSliderCount($conn){
	    $query = "
	        UPDATE working_plan
	        SET max_showcase_limit = :max_showcase_limit
	    ";
	    $stmt = $conn->prepare($query);
	    $stmt->execute(
	        array(
	            ':max_showcase_limit' => $_POST['slider_limit']
	        )     
	    );
	}
	
	$query = "
		SELECT * FROM outside_links
	";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
	if($stmt->rowCount() > 0){
		$query = "
			UPDATE outside_links
			SET fb_link = :fb_link,
				ig_link = :ig_link,
				home_info = :home_info
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':fb_link' => $_POST['fb_link'],
				':ig_link' => $_POST['ig_link'],
				':home_info' => $_POST['home_info']
			)
		);
		
	    updateSliderCount($conn);
		
		if($stmt->rowCount() > 0){
			echo "Home info contents successfully updated.";
		}
	}
	else{
		$query = "
			INSERT INTO
				outside_links
				(
					fb_link,
					ig_link,
					home_info
				)
				VALUES
				(
					:fb_link,
					:ig_link,
					:home_info
				)
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':fb_link' => $_POST['fb_link'],
				':ig_link' => $_POST['ig_link'],
				':home_info' => $_POST['home_info']
			)
		);
		
		updateSliderCount($conn);
		
		if($stmt->rowCount() > 0){
		    updateSliderCount($conn);
			echo "Home info contents successfully updated.";
		}
	}
?>