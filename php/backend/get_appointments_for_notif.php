<?php
	require '../dbconfig.php';
	require 'get_all_pending_appointments_of_user_functionned.php';

	echo getPendingAppointmentsOfUser($conn);
?>