<?php
	require '../dbconfig.php';
	
	$query = "
		UPDATE
			activity_feed
		SET
			is_seen = 1
		WHERE
			activity_id = :feed_id
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':feed_id' => $_POST['feed_id']
		)
	);
	
	if($stmt->rowCount() > 0){
		echo "Success";
	}
	else{
		echo "Error";
	}
?>