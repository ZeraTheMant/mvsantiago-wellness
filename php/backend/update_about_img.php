<?php
	require '../dbconfig.php';
	
	
	if($_POST['caller'] === "POST"){
		$query = "SELECT * FROM about_logo LIMIT 1";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		
		if($stmt->rowCount() < 1){
			$query = "
				INSERT INTO
					about_logo
					(
						about_img_link
					)
				VALUES
					(
						:img_link
					)
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':img_link' => $_POST['img_link']
				)
			);
		}
		else{
			$query = "
				UPDATE
					about_logo
				SET
					about_img_link = :img_link
			";
			$stmt = $conn->prepare($query);
			$stmt->execute(
				array(
					':img_link' => $_POST['img_link']
				)
			);
		}
	}
	else{
		
		$query = "SELECT * FROM about_logo LIMIT 1";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		
		if($stmt->rowCount() < 1){
			echo "../../img/question_mark.png";
		}
		else{
			$query = "SELECT * FROM about_logo LIMIT 1";
			$stmt = $conn->prepare($query);
			$stmt->execute();
			
			$result = $stmt->fetch();
			
			echo $result['about_img_link'];
		}
	}

?>