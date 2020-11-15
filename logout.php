<?php
	require 'php/dbconfig.php';
	require 'php/backend/report_function.php';

	session_start();
	if(!isset($_SESSION['email'])){
		header("Location: http://mvsantiagowellness.com");
	}
	else{
		$date = new DateTime(null, new DateTimeZone('Asia/Manila'));
					
		//insertReport($conn, $_SESSION['fname'] . ' ' . $_SESSION['mname'] . ' ' . $_SESSION['lname'] . ' logged out.', '', $date->format('Y-m-d H:i:s'), $_SESSION['client_id'], 0, 'log');
		
		session_destroy();
		echo '
			<script type="text/javascript">
				alert("Logout successful. Goodbye");
				window.location = "http://mvsantiagowellness.com";
			</script>
		';
	}
?>