<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();


	if($_POST['action'] == 'mark as read'){
		$query = "
			UPDATE
				message_inbox
			SET
				is_read = 0
			WHERE
				msg_id = :msg_id
		";
	}
	else if($_POST['action'] == 'reply'){
		echo "wew";
	}
	else{
		$query = "
			DELETE FROM
				message_inbox
			WHERE
				msg_id = :msg_id
		";
	}
	
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':msg_id' => $_POST['msg_id']
		)
	);

?>