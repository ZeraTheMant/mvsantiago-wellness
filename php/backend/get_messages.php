<?php
	require '../dbconfig.php';
	//$inipath = php_ini_loaded_file();

	$query = "
		SELECT 
			*
		FROM 
			message_inbox
		WHERE
			is_read = :is_read
		ORDER BY
			msg_id DESC
	";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':is_read' => $_GET['is_read']
		)
	);
	$output = '';
	
	if($stmt->rowCount() > 0){
		$result = $stmt->fetchAll();
				
		$output .= '
			<h3>List of Messages</h3>
			<p id="instruction">Double-click the row of the message that you wish to view.</p>
			<br>
			<table>
				<tr>
					<th>ID</th>
					<th>Date sent</th>
					<th>Sender</th>
					<th>Email</th>
					<th>Contact number</th>
					<th>Message</th>
				</tr>

		';
		foreach($result as $row){
			$output .= '
				<tr class="actual-row">
					<td>MSG_'. $row['msg_id'] . '</td>
					<td>'. $row['date_sent'] . '</td>
					<td>'. $row['msg_sender'] . '</td>
					<td>'. $row['sender_email'] . '</td>
					<td>'. $row['sender_contact'] . '</td>
					<td>'. substr($row['message'], 0, 70) . '...</td>
					<td class="not-displayed">' .$row['message'] . '</td>
					<td class="not-displayed">' .$row['msg_id'] . '</td>
				</tr>
			';
		}
		
		$output .= '</table>';
	}
	else{
		$output = '<p style="grid-column: 1/5;">No messages found in database.</p>';
	}
	
	echo $output;
?>