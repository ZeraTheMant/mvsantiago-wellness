<?php
	require '../dbconfig.php';

	if($_POST['action'] == 'insert'){
		$query = "
			INSERT INTO
				showcase_images
				(
					display_order,
					header,
					subheader,
					description,
					img_link,
					link_to
				)
			VALUES
				(
					:display_order,
					:header,
					:subheader,
					:description,
					:link,
					:links_to
				)
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':display_order' => $_POST['display_order'],
				':header' => $_POST['subheader'],
				':subheader' => $_POST['subheader'],
				':description' => $_POST['description'],
				':link' => $_POST['img_link'],
				':links_to' => $_POST['links_to']
			)
		);
		
		if($stmt->rowCount() > 0){
			echo "Image successfully added.";
		}
	}
	else{
		$query = "
			UPDATE
				showcase_images
			SET
				display_order = :display_order,
				header = :header,
				subheader = :subheader,
				description = :description,
				img_link = :link,
				link_to = :links_to
			WHERE
				showcase_id = :showcase_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':display_order' => $_POST['display_order'],
				':header' => $_POST['header'],
				':subheader' => $_POST['subheader'],
				':description' => $_POST['description'],
				':link' => $_POST['img_link'],
				':links_to' => $_POST['links_to'],
				':showcase_id' => $_POST['showcase_id']
			)
		);
		if($stmt->rowCount() > 0){
			echo "Image successfully updated.";
		}
	}
?>