<?php
	require '../dbconfig.php';
	
	function deleteFromClientServices($client_id, $conn){
		$query = "
			DELETE FROM customer_services WHERE client_id = :client_id
		";
		$stmt = $conn->prepare($query);
		$stmt->execute(
			array(
				':client_id' => $client_id
			)
		);
	}
	
	function insertToClientServices($client_id, $conn){	
		deleteFromClientServices($client_id, $conn);
	
		$kiko = '';
		
		$query = "
			INSERT INTO customer_services
			(
				client_id,
				service_id,
				category_id
			)
			VALUES
		";
		
		for($i=0; $i<count($_POST['categories_and_services']); $i++){
			if($i< count($_POST['categories_and_services'])-1){
				$kiko .= '
					(' . $client_id . ', ' . $_POST["categories_and_services"][$i]['service_id'] . ', ' . $_POST["categories_and_services"][$i]['category_id'] . '),
				';
			}
			else{
				$kiko .= '
					(' . $client_id. ', ' . $_POST["categories_and_services"][$i]['service_id'] . ', ' . $_POST["categories_and_services"][$i]['category_id'] . ')
				';
			}
		}
		
		$query .= $kiko;
		$stmt = $conn->prepare($query);
		$stmt->execute();
	}
	

	
	if(empty($_POST["categories_and_services"])){
		deleteFromClientServices($_POST['client_id'], $conn);
	}
	else{
		insertToClientServices($_POST['client_id'], $conn);
	}
?>