<?php
	function insertReport($conn, $title, $link, $timestamp, $client_id, $provider_id, $appointment_id){
		$query = "
			INSERT INTO
				reports
				(
					title,
					link,
					timestamp,
					client_id,
					provider_id,
					appointment_id
				)
				VALUES
				(
					:title,
					:link,
					:timestamp,
					:client_id,
					:provider_id,
					:appointment_id
				)
		";
		
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':title' => $title,
				':link' => $link,
				':timestamp' => $timestamp,
				':client_id' => $client_id,
				':provider_id' => $provider_id,
				':type' => $appointment_id
			)
		);
	}
?>