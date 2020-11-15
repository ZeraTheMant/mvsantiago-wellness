<?php
	function insertToActivityFeed($conn, $client_id, $provider_id, $date, $message){
		$query = "
			INSERT INTO 
				activity_feed
					(
						client_id,
						provider_id,
						date,
						message,
						is_seen
					)
				VALUES
					(
						:client_id,
						:provider_id,
						:date,
						:message,
						0
					)
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':client_id' => $client_id,
				':provider_id' => $provider_id,
				':date' => $date,
				':message' => $message
			)
		);
	}

?>