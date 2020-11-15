<?php
	require '../dbconfig.php';

	function insertToCategoriesServices($service_ids, $category_id, $conn){
		for($i=0; $i<count($service_ids); $i++){
			$query = "
				UPDATE
					services
				SET	
					category_id = " . $category_id . "
				WHERE
					service_id = :service_id
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':service_id' => $service_ids[$i]
				)
			);
		}
	}
	
	if($_POST['action'] == 'insert'){
		$query = "
			INSERT INTO category
			(
				category_name,
				category_img,
				category_description
			)
			VALUES
			(
				:category_name,
				:category_img,
				:category_description
			)
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':category_name' => $_POST['category_name'],
				':category_img' => $_POST['category_img'],
				':category_description' => $_POST['category_description']
			)
		);
		if($stmt->rowCount() == 1){
			if(isset($_POST['service_ids'])){

				insertToCategoriesServices($_POST['service_ids'], "@@identity", $conn);
			}
	
			
			echo "New category added successfully.";
		}
	}
	else if($_POST['action'] == 'update'){
		$query = "
			UPDATE
				category
			SET
				category_name = :category_name,
				category_img = :category_img,
				category_description = :category_description
			WHERE
				category_id = :category_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':category_name' => $_POST['category_name'],
				':category_img' => $_POST['category_img'],
				':category_description' => $_POST['category_description'],
				':category_id' => $_POST['category_id']
			)
		);
		
		if(isset($_POST['service_ids'])){
			insertToCategoriesServices($_POST['service_ids'], $_POST['category_id'], $conn);
		}
		
			
		echo "Category information updated successfully.";
	}
	else{
		$query = "DELETE FROM category WHERE category_id = :category_id";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':category_id' => $_POST['category_id']
			)
		);
		if($stmt->rowCount() == 1){
			echo "Category deleted successfully.";
		}
	}
?>