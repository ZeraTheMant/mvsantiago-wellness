<?php
//insert.php
	require '../dbconfig.php';

	
	$query = "SELECT COUNT(appointment_id) FROM appointments WHERE is_finished = 0 AND is_cancelled = 0 AND provider_id = :provider_id";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':provider_id' => $_GET['provider_id']
		)
	);
	$result = $stmt->fetch();
	
	$pendingApps = $result['COUNT(appointment_id)'];
	
	$query = "SELECT COUNT(appointment_id) FROM appointments WHERE is_finished = 1 AND is_cancelled = 0 AND provider_id = :provider_id";
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':provider_id' => $_GET['provider_id']
		)
	);
	$result = $stmt->fetch();
	
	$finishedApps = $result['COUNT(appointment_id)'];
	
	$query = "SELECT DISTINCT provider_id, client_id FROM appointments WHERE provider_id = :provider_id";
	
	$stmt = $conn->prepare($query);
	$stmt->execute(
		array(
			':provider_id' => $_GET['provider_id']
		)
	);
	$result = $stmt->fetchAll();
	
	$distinctUsers = count($result);

	
	$output = '
		<div class="dashboard-info-box" id="upcoming-apps-box">
			<a href="backend_provider_appointments.php" class="info-box-link"></a>
			<div class="inner-box">
				<div>
					<p class="fat-icon icon-calendar" id="appointments-icon"></p>
				</div>
				<div>
					<p class="number">' . $pendingApps . '</p>
					<p class="box-info">Upcoming Appointments</p>
				</div>
			</div>
		</div>	
		
		<div class="dashboard-info-box" id="upcoming-apps-box">
			<a href="backend_provider_appointments.php" class="info-box-link"></a>
			<div class="inner-box">
				<div>
					<p class="fat-icon icon-calendar" id="appointments-icon"></p>
				</div>
				<div>
					<p class="number">' . $finishedApps . '</p>
					<p class="box-info">Finished Appointments</p>
				</div>
			</div>
		</div>	
		
		<div class="dashboard-info-box" id="new-testimonials-box" >
			<a href="backend_provider_clients_services.php" class="info-box-link"></a>
			<div class="inner-box">
				<div>
					<p class="fat-icon icon-users" id="testimonials-icon"></p>
				</div>
				<div>
					<p class="number">' . $distinctUsers . '</p>
					<p class="box-info">Clients Serviced</p>
				</div>
			</div>
		</div>
	';
	
	echo $output;
?>