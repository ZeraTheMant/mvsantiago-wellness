<?php
	require '../dbconfig.php';

	if($_POST['action'] == 'insert'){
		$query = "
			INSERT INTO providers
			(
				provider_fname,
				provider_lname,
				provider_mname,
				provider_name_ext,
				provider_img,
				provider_bio,
				provider_level,
				provider_gender,
				days_worked
			)
			VALUES
			(
				:provider_fname,
				:provider_lname,
				:provider_mname,
				:provider_name_ext,
				:provider_img,
				:provider_bio,
				:provider_level,
				:provider_gender,
				:days_worked
			)
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':provider_fname' => $_POST['provider_fname'],
				':provider_lname' => $_POST['provider_lname'],
				':provider_mname' => $_POST['provider_mname'],
				':provider_name_ext' => $_POST['provider_name_ext'],
				':provider_img' => $_POST['provider_img'],
				':provider_bio' => $_POST['provider_bio'],
				':provider_level' => $_POST['provider_level'],
				':provider_gender' => $_POST['provider_gender'],
				':days_worked' => json_encode($_POST['days_worked'])
			)
		);
		if($stmt->rowCount() == 1){
			insertToProviderServices("@@identity", $conn);
			echo "New provider added successfully.";
		}
	}
	else if($_POST['action'] == 'update'){
		$query = "
			UPDATE providers
			SET
			provider_fname = :provider_fname,
			provider_lname = :provider_lname,
			provider_mname = :provider_mname,
			provider_name_ext = :provider_name_ext,
			provider_img = :provider_img,
			provider_bio = :provider_bio,
			provider_level = :provider_level,
			provider_gender = :provider_gender,
			days_worked = :days_worked
			WHERE provider_id = :provider_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':provider_fname' => $_POST['provider_fname'],
				':provider_lname' => $_POST['provider_lname'],
				':provider_mname' => $_POST['provider_mname'],
				':provider_name_ext' => $_POST['provider_name_ext'],
				':provider_img' => $_POST['provider_img'],
				':provider_bio' => $_POST['provider_bio'],
				':provider_level' => $_POST['provider_level'],
				':provider_gender' => $_POST['provider_gender'],
				':provider_id' => $_POST['provider_id'],
				':days_worked' => json_encode($_POST['days_worked'])
			)
		);
		/*if($stmt->rowCount() == 1){
			insertToProviderServices($_POST['provider_id'], $conn);
			echo "Provider information updated successfully.";
		}*/
		insertToProviderServices($_POST['provider_id'], $conn);
		echo "Provider information updated successfully.";
	}
	else{
		$query = "DELETE FROM providers WHERE provider_id = :provider_id";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':provider_id' => $_POST['provider_id']
			)
		);
		if($stmt->rowCount() == 1){
			echo "Provider deleted successfully.";
		}
	}

	function insertToProviderServices($provider_id, $conn){
		$query = "
			DELETE FROM provider_services WHERE provider_id = :provider_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':provider_id' => $provider_id
			)
		);
		
		$kiko = '';
		
		for($i=0; $i<count($_POST['cat_and_serv_id']); $i++){
			if($i< count($_POST['cat_and_serv_id'])-1){
				$kiko .= '
					(' . $provider_id . ', ' . $_POST["cat_and_serv_id"][$i]['category_id'] . ', ' . $_POST["cat_and_serv_id"][$i]['service_id'] . '),
				';
			}
			else{
				$kiko .= '
					(' . $provider_id . ', ' . $_POST["cat_and_serv_id"][$i]['category_id'] . ', ' . $_POST["cat_and_serv_id"][$i]['service_id'] . ')
				';
			}
		}
		
		$query = "
			INSERT INTO provider_services
			(
				provider_id,
				category_id,
				service_id
			)
			VALUES
		";
		
		$query .= $kiko;
		$stmt = $conn->prepare($query);
		$stmt->execute();
	}
?>