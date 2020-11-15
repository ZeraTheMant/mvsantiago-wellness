<?php
	require '../dbconfig.php';

	if($_POST['action'] == 'insert'){
		$query = "
			INSERT INTO
				facilities_images
				(
					display_order,
					header,
					description,
					img_link
				)
			VALUES
				(
					:display_order,
					:header,
					:description,
					:link
				)
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':display_order' => $_POST['display_order'],
				':header' => $_POST['header'],
				':description' => $_POST['description'],
				':link' => $_POST['img_link']
			)
		);
		
		if($stmt->rowCount() > 0){
			echo "Image successfully added.";
		}
	}
	else{
		$query = "
			UPDATE
				facilities_images
			SET
				display_order = :display_order,
				header = :header,
				description = :description,
				img_link = :link
			WHERE
				fi_id = :facility_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':display_order' => $_POST['display_order'],
				':header' => $_POST['header'],
				':description' => $_POST['description'],
				':link' => $_POST['img_link'],
				':facility_id' => $_POST['facility_id']
			)
		);
		if($stmt->rowCount() > 0){
			echo "Image successfully updated.";
		}
	}
?>